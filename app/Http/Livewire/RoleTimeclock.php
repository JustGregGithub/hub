<?php

// app/Http/Livewire/RoleTimeComponent.php
namespace App\Http\Livewire;

use App\Models\Staff\Server;
use App\Models\Staff\ServerRole;
use App\Models\Staff\ServerTimeclock;
use Livewire\Component;

class RoleTimeclock extends Component
{
    public Server $server;
    public $selectedRole;
    public $selectedRoleName;
    public $time;
    public $eligibleUsers;

    public function mount()
    {
        // Get the initial role and set it to selectedRole
        $initialRole = ServerRole::where('server_id', $this->server->id)->first();
        $this->selectedRole = $initialRole->id;
        $this->selectedRoleName = $initialRole->name;
//
//        // Set the initial time based on the initial role
        $this->time = $initialRole->timeclock_requirements;
        $this->eligibleUsers = $this->eligibleUsers();
    }

    public function updatedSelectedRole($value)
    {
        // Update the time based on the selected role
        $role = ServerRole::where('server_id', $this->server->id)->find($value);
        $this->selectedRoleName = $role->name;
        $this->time = $role->timeclock_requirements;
        $this->eligibleUsers = $this->eligibleUsers();
    }

    public function updatedTime($value)
    {
        if (!is_numeric($value)) {
            session()->flash('timeclock-error', 'Time must be a number!');
            return;
        }

        // Update the time in the database for the selected role
        $role = ServerRole::where('server_id', $this->server->id)->find($this->selectedRole);
        $role->timeclock_requirements = $value; // Update the attribute
        $role->save(); // Save the changes to the database

        $this->eligibleUsers = $this->eligibleUsers();

        session()->flash('timeclock-success', 'Time updated successfully!');
    }

    public function eligibleUsers()
    {
        $users = ServerRole::usersWithRole($this->server->id, $this->selectedRole);
        $eligibleUsers = [];

        foreach ($users as $user) {
            //get the timeclocks for this user. We only care about clocked out timeclocks. They are in minutes.
            $timeclocks = ServerTimeclock::where('server_id', $this->server->id)
                ->where('user_id', $user->id)
                ->where('type', ServerTimeclock::TYPES['clock_out'])
                ->get();

            $totalTime = 0;

            foreach ($timeclocks as $timeclock) {
                $totalTime += $timeclock->time;
            }

            // $this->time is in hours, so we need to convert it to minutes
            if ($totalTime >= $this->time * 60) {
                $eligibleUsers[] = (object) [
                    'id' => (string)$user->id,
                    'name' => $user->displayName(),
                    'time' => gmdate('z \D\a\y\s, H \H\o\u\r\s, i \M\i\n\u\t\e\s', $totalTime * 60)
                ];
            }
        }

        return $eligibleUsers;
    }


    public function render()
    {
        $roles = ServerRole::all();

        return view('livewire.role-timeclock', compact('roles'));
    }
}
