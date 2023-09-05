<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff\Server;
use App\Models\Staff\ServerRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class StaffController extends Controller
{
    /**
     * Timeclock
     */

    public function timeclock(Request $request, Server $server) {
        Gate::authorize('can-view-timeclock', $server);
        return view('staff.staff.timeclock', [
            'server' => $server,
        ]);
    }


    /**
     * Permissions
     */

    public function permissions(Request $request, Server $server) {
        Gate::authorize('can-manage-roles', $server);
        return view('staff.staff.permissions', [
            'server' => $server,
            'roles' => ServerRole::where('server_id', $server->id)->get(),
        ]);
    }

    public function post_permissions(Request $request, Server $server) {
        Gate::authorize('can-manage-roles', $server);
        $request->validate([
            'name' => 'required',
            'guild' => 'required|min:17|max:20',
            'role' => 'required|min:17|max:20',
        ]);

        $role = ServerRole::where('server_id')->where('id', $request->role)->first();
        if ($role) {
            return redirect()->back()->with('error', 'This role already exists!');
        }

        $userRole = Auth::user()->getHighestRole($server);
        $priority = $userRole->priority - 1;

        if ($priority <= 0) $priority = 1;

        //get the users highest role. check if the priority is higher than the new role
        if ($userRole->priority <= $priority) {
            return redirect()->back()->withErrors('You cannot create a role with a higher priority than you!');
        }

        ServerRole::create([
            'server_id' => $server->id,
            'name' => $request->name,
            'guild' => $request->guild,
            'id' => $request->role,
            'priority' => $priority,
        ]);

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }

    public function delete_permissions(Request $request, Server $server) {
        Gate::authorize('can-manage-roles', $server);

        $request->validate([
            'role' => 'required|min:17|max:20',
            'new_role' => 'required|min:17|max:20',
        ]);

        $role = ServerRole::where('id', $request->role)->first();
        if (!$role) {
            return redirect()->back()->with('error', 'This role does not exist!');
        }

        $role->delete();

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }

    public function permission(Request $request, Server $server, ServerRole $role) {
        Gate::authorize('can-manage-roles', $server);

        if (Auth::user()->getHighestRole($server)->priority <= $role->priority && !$request->user()->isOwner()) {
            return redirect()->route('staff.permissions', $server)->withErrors('You cannot edit a role with a higher priority than you!');
        }

        return view('staff.staff.permission', [
            'server' => $server,
            'role' => $role
        ]);
    }

    public function patch_permission (Request $request, Server $server, ServerRole $role) {
        Gate::authorize('can-manage-roles', $server);

        $request->validate([
            'name' => 'required',
            'priority' => 'required',
            'guild' => 'required|min:17|max:20',
            'role' => 'required|min:17|max:20',
        ]);

        //only update the fields if they are different
        if ($role->name != $request->name) $role->name = $request->name;
        if ($role->priority != $request->priority) $role->priority = $request->priority;
        if ($role->guild != $request->guild) $role->guild = $request->guild;
        if ($role->id != $request->role) $role->id = $request->role;

        $role->save();

        return redirect()->route('staff.permission', ['server' => $server->id, 'role' => $role->id])->with('success', 'Permissions updated successfully.');
    }
}
