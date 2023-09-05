<?php

namespace App\Http\Controllers;

use App\Models\Staff\Player;
use App\Models\Staff\PlayerActivity;
use App\Models\Staff\PlayerRecord;
use App\Models\Staff\Server;
use App\Models\Staff\ServerRole;
use App\Models\Staff\ServerTimeclock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Str;

class ApiController extends Controller
{

    // For use with discord bot.
    public function post_discord_roles(Request $request, User $user)
    {
        $body = $request->all();

        $validator = Validator::make($request->all(), [
            'guild' => 'required',
            'roles' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $json = json_decode($body['roles'], true);
        $guild = $body['guild'];
        $currentRoles = $user->roles;

        if (!isset($currentRoles[$guild])) {
            $currentRoles[$guild] = [];
        }

        $currentRoles[$guild] = $json;

        $user->roles = $currentRoles;
        $user->save();

        return response()->json([
            'success' => true,
            'guild' => $guild,
            'user' => $user->id,
            'roles' => $user->roles,
        ]);
    }

    public function get_quote()
    {
        $request = Http::withHeaders([
            'X-Api-Key' => env('API_NINJA_KEY'),
        ])->get('https://api.api-ninjas.com/v1/quotes?category=success');

        $request = $request->json();

        return response()->json([
            'quote' => $request[0]['quote'] . ' - ' . $request[0]['author'],
        ]);
    }

    /**
     * Server API
     */

    public function server(Request $request, Server $server)
    {
        $server->isValidKey();
        return response()->json([
            'server' => [
                'name' => $server->name,
                'ip' => $server->ip,
                'port' => $server->port,
                'created_at' => $server->created_at,
                'is_online' => $server->isOnline(),
            ],
            'players' => [
                'online' => $server->players()->where('online', true)->count(),
                'total' => $server->players()->count(),
            ],
        ]);
    }

    public function player(Request $request, Server $server, $type, $identifier)
    {
        if ($type === 'discord' || $type === 'license') {
            $player = Player::where($type, $identifier)->first();

            if ($player) {
                return response()->json([
                    'player' => $player,
                    'record' => $player->record,
                ]);
            } else {
                // Player not found, return a 404 response
                return response()->json(['error' => 'Player not found'], 404);
            }
        } else {
            // Invalid player type, return a 400 response
            return response()->json(['error' => 'Invalid player type'], 400);
        }
    }

    public function patch_player(Request $request, Server $server, Player $player) {
        $server->isValidKey();
        $input = $request->all();
        $rules = [
            'online' => 'required|boolean',
            'activity' => 'required|integer',
            'message' => 'string',
            'playtime' => 'integer'
        ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $player->online = $input['online'];

        if ($request->filled('playtime')) {
            $player->playtime = $player->playtime + $input['playtime'];
        }

        if($input['activity'] == PlayerActivity::TYPES['Leave'] && !$request->filled('message')) {
            return response()->json([
                'success' => false,
                'message' => 'Message is required.',
            ]);
        }

        $player->activity()->create([
            'server_id' => $server->id,
            'player_id' => $player->id,
            'message' => $input['message'] ?? 'Player Joined',
            'type' => $input['activity'],
        ]);

        $player->save();
        return response()->json([
            'success' => true,
            'player' => $player,
        ]);
    }

    public function post_player(Request $request, Server $server) {
        $server->isValidKey();
        $input = $request->all();
        $request->validate([
            'name' => 'required|string',
            'identifiers' => 'required'
        ]);

        //check if the players license is already in the database
        $player = Player::where('license', $input['identifiers']['license'])->first();

        //if the player is not in the database, create a new player
        if(!$player) {
            $player = new Player;
            $player->server_id = $server->id;
            $player->name = $input['name'];
            $player->license = $input['identifiers']['license'];
            $player->steam = $input['identifiers']['steam'];
            $player->discord = $input['identifiers']['discord'];
            $player->ip = $input['identifiers']['ip'];
            $player->save();
        } else {
            //if the name has changed, update the name
            if($player->name != $input['name']) {
                $player->name = $input['name'];
                $player->save();
            }

            //if the steam has changed, update the steam
            if($player->steam != $input['identifiers']['steam']) {
                $player->steam = $input['identifiers']['steam'];
                $player->save();
            }

            //if the discord has changed, update the discord
            if($player->discord != $input['identifiers']['discord']) {
                $player->discord = $input['identifiers']['discord'];
                $player->save();
            }

            //if the ip has changed, update the ip
            if($player->ip != $input['identifiers']['ip']) {
                $player->ip = $input['identifiers']['ip'];
                $player->save();
            }

            //if the license has changed, update the license
            if($player->license != $input['identifiers']['license']) {
                $player->license = $input['identifiers']['license'];

                //add the old license to the old_licenses column
                $player->old_licenses = array_merge($player->old_licenses, [$player->license]);
                $player->save();
            }
        }

        return response()->json([
            'banned' => $player->isBanned(),
            'player' => $player,
        ]);
    }

    public function post_report(Request $request, Server $server, Player $player) {
        $server->isValidKey();
        $input = $request->all();
        $request->validate([
            'reported_license' => 'required',
            'message' => 'required|string',
        ]);

        $reported = Player::where('license', $input['reported_license'])->first();

        if(!$reported) {
            return response()->json([
                'success' => false,
                'message' => 'Player not found.',
            ]);
        }

        $report = $server->reports()->create([
            'player_id' => $player->id,
            'reported_id' => $reported->id,
            'reason' => $input['message']
        ]);

        return response()->json([
            'success' => true,
            'report' => $report,
        ]);
    }

    public function post_chat(Request $request, Server $server, Player $player) {
        $server->isValidKey();
        $input = $request->all();
        $request->validate([
            'message' => 'required|string',
        ]);

        $chat = $server->chats()->create([
            'player_id' => $player->id,
            'message' => $input['message'],
        ]);

        return response()->json([
            'chat' => $chat,
        ]);
    }

    public function post_death(Request $request, Server $server, Player $player) {
        $server->isValidKey();
        $input = $request->all();

        $request->validate([
            'killer_license' => 'required|string',
            'death_reason' => 'required|string',
            'weapon' => 'required|string',
        ]);

        $death = $player->deaths()->create([
            'server_id' => $server->id,
            'player_id' => $player->id,
            'killer_id' => $player->byLicense($input['killer_license'])->id,
            'cause' => $input['death_reason'] . ' - ' . $input['weapon'],
        ]);


        return response()->json([
            'death' => $death,
        ]);
    }

    public function post_punish(Request $request, Server $server, Player $player) {
        $server->isValidKey();
        $input = $request->all();

        $request->validate([
            'type' => 'required|integer',
            'staff_license' => 'required|string',
            'duration' => 'integer',
            'duration_type' => [
                'required_with:duration',
                'string',
                Rule::in(['second', 'minute', 'hour', 'day', 'week', 'month', 'year']),
            ],
            'message' => 'required|string',
        ]);

        $staff = $player->byLicense($input['staff_license']);
        $user = User::where('id', $staff->discord)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Staff member not found.',
            ]);
        }

        $role = $user->getHighestRole($server);
        $type = Str::lower(array_search($input['type'], PlayerRecord::TYPES));

        //check if type is valid
        if(!$type) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid record type.',
            ]);
        }

        if($input['type'] == PlayerRecord::TYPES['Ban']) {
            $ban = $player->isBanned();

            if($ban) {
                return response()->json([
                    'success' => false,
                    'message' => 'Player is already banned.',
                ]);
            }
        }

        if ($role->{'can_' . $type}) {
            $record = $player->record()->create([
                'server_id' => $server->id,
                'player_id' => $player->id,
                'staff_id' => $user->id,
                'type' => $input['type'],
                'message' => $input['message'],
            ]);

            //if it is a ban
            if($input['type'] == PlayerRecord::TYPES['Ban']) {
                $player->ban()->create([
                    'server_id' => $server->id,
                    'player_id' => $player->id,
                    'reason' => $input['message'],
                    'expiration_date' => Carbon::now()->add($input['duration'], $input['duration_type']),
                ]);
            }

            return response()->json([
                'success' => true,
                'record' => $record,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'You do not have permission to ' . $type . '.',
        ], 403);
    }

    public function post_duty(Request $request, Server $server, Player $player) {
        $server->isValidKey();
        $input = $request->all();
        $isStaff = false;

        $request->validate([
            'type' => 'required|integer',
            'staff_license' => 'required|string',
            'minutes_active' => 'integer',
        ]);

        if(!array_search($input['type'], ServerTimeclock::TYPES)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid timeclock type.',
            ]);
        }

        if ($input['type'] == ServerTimeclock::TYPES['clock_out'] && !$request->filled('minutes_active')) {
            return response()->json([
                'success' => false,
                'message' => 'Minutes active is required.',
            ]);
        }

        $staff = $player->byLicense($input['staff_license']);
        $user = User::where('id', $staff->discord)->first();

        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Staff member not found.',
            ]);
        }

        //check if the has atleast one role in the server.
        foreach ($server->roles()->get() as $role)
        {
            if ($user->hasDiscordRole($role->guild, $role->id)) {
                $isStaff = true;
                break;
            }
        }

        if (!$isStaff) {
            return response()->json([
                'success' => false,
                'message' => 'You are not a staff member!',
            ], 403);
        }

        $timeclock = ServerTimeclock::create([
            'server_id' => $server->id,
            'user_id' => $user->id,
            'type' => $input['type'],
            'time' => $input['minutes_active'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'timeclock' => $timeclock,
        ]);
    }
}
