<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    public function servers()
    {
        Gate::authorize('is-owner');

        return view('staff.settings.servers', [
            'servers' => Server::all(),
        ]);
    }

    public function server(Server $server) {
        Gate::authorize('is-owner');

        return view('staff.settings.server', [
            'server' => $server,
        ]);
    }

    public function patch_server(Server $server, Request $request) {
        Gate::authorize('is-owner');

        $server->update($request->validate([
            'name' => 'required|string',
            'ip' => 'required|string|ipv4|unique:servers,ip,' . $server->id . ',id',
            'port' => 'required|integer',
        ]));

        return redirect()->back()->with('success', 'Server updated successfully!');
    }
}
