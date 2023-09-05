<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff\Player;
use App\Models\Staff\PlayerRecord;
use App\Models\Staff\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class ServerController extends Controller
{
    public function server(Request $request, Server $server)
    {
        return view('staff.server.home', [
            'server' => $server,
        ]);
    }

    public function post_server(Request $request) {
        Gate::authorize('is-owner');

        $request->validate([
            'name' => 'required',
            'ip' => 'required',
            'port' => 'required',
        ]);

        $server = new Server;
        $server->name = $request->name;
        $server->ip = $request->ip;
        $server->port = $request->port;

        $secret = Str::random(32);
        $server->secret = Crypt::encryptString($secret);

        $server->save();

        return redirect()->back()->with('success', 'Server added successfully!')->with('secret', $secret);
    }

    public function delete_server(Server $server) {
        Gate::authorize('is-owner');

        $server->delete();

        return redirect()->back()->with('success', 'Server deleted successfully!');
    }

    public function search(Server $server) {
        return view('staff.server.search', [
            'server' => $server,
        ]);
    }

    public function players(Server $server) {
        return view('staff.server.players', [
            'server' => $server,
        ]);
    }

    public function player(Server $server, Player $player) {
        $steam = Http::get('https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . env('STEAM_API_KEY') . '&steamids=' . hexdec($player->steam))->json();

        return view('staff.server.player', [
            'server' => $server,
            'player' => $player,
            'steam' => $steam['response']['players'][0],
        ]);
    }

    public function post_player(Request $request, Server $server, Player $player) {
        $input = $request->all();
        $request->validate([
            'type' => 'required',
            'duration' => [
                'integer',
                Rule::requiredIf(function () use ($request) {
                    return $request->input('type') === PlayerRecord::TYPES['Ban'];
                }),
            ],
            'duration_type' => [
                'string',
                Rule::in(['second', 'minute', 'hour', 'day', 'week', 'month', 'year']),
                Rule::requiredIf(function () use ($request) {
                    return $request->input('type') === PlayerRecord::TYPES['Ban'];
                }),
            ],
            'message' => 'required',
        ]);

        if (!in_array($input['type'], PlayerRecord::TYPES)) {
            return redirect()->back()->with('error', 'Invalid type!');
        }

        $role = $request->user()->getHighestRole($server);
        if ($role->can($input['type'])) {

            //If it is not a ban. E.G. Kick, Warn, etc.
            if ($input['type'] != PlayerRecord::TYPES['Ban']) {
                PlayerRecord::create([
                    'server_id' => $server->id,
                    'staff_id' => request()->user()->id,
                    'player_id' => $player->id,
                    'type' => $input['type'],
                    'message' => $input['message'],
                    'expiration_date' => isset($input['duration_type']) ? now()->add($input['duration'], $input['duration_type']) : null,
                ]);

                // if it is a kick
                if ($input['type'] == PlayerRecord::TYPES['Kick']) {
                    try {
                        $kick = Http::timeout(2)->withHeaders([
                            'secret' => Crypt::decryptString($server->secret),
                            'license' => $player->license,
                            'type' => 'kick',
                            'reason' => $input['message'],
                        ])->post('http://' . $server->ip . ':' . $server->port . '/staffpanel/drop');

                        if ($kick->successful()) {
                            return redirect()->back()->with('success', 'Successfully punished player!');
                        } else {
                            return redirect()->back()->withErrors('Successfully punished player, but could not kick them from the server!');
                        }
                    } catch (Throwable $e) {
                        return redirect()->back()->withErrors('Successfully punished player, but could not kick them from the server!');
                    }
                }

                return redirect()->back()->with('success', 'Successfully punished player!');
            }


            //If it is a ban.
            if ($player->isBanned()) {
                return redirect()->back()->withErrors('Player is already banned!');
            }

            PlayerRecord::create([
                'server_id' => $server->id,
                'staff_id' => request()->user()->id,
                'player_id' => $player->id,
                'type' => $input['type'],
                'message' => $input['message'],
                'expiration_date' => now()->add($input['duration'], $input['duration_type']),
            ]);

            try {
                $kick = Http::timeout(2)->withHeaders([
                    'secret' => Crypt::decryptString($server->secret),
                    'license' => $player->license,
                    'type' => 'ban',
                    'reason' => $input['message'],
                ])->post('http://' . $server->ip . ':' . $server->port . '/staffpanel/drop');

                if ($kick->successful()) {
                    return redirect()->back()->with('success', 'Player banned successfully!');
                } else {
                    return redirect()->back()->with('error', 'Player banned successfully, but could not kick them from the server!');
                }
            } catch (Throwable $e) {
                return redirect()->back()->with('error', 'Player banned successfully, but could not kick them from the server!');
            }

        } else {
            return redirect()->back()->with('error', 'You do not have permission to do this!');
        }
    }

    public function chats(Server $server) {
        return view('staff.server.chats', [
            'server' => $server,
        ]);
    }

    public function deaths(Server $server) {
        return view('staff.server.deaths', [
            'server' => $server,
        ]);
    }

    public function reports(Server $server) {
        return view('staff.server.reports', [
            'server' => $server,
        ]);
    }
}
