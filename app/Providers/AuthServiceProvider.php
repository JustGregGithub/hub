<?php

namespace App\Providers;

use App\Models\Hub\Application;
use App\Models\Hub\ApplicationCategory;
use App\Models\Hub\Ticket;
use App\Models\Hub\TicketCategory;
use App\Models\Staff\Server;
use App\Models\Staff\ServerRole;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        /**
         * General Policies
         */

        Gate::define('is-mgmt', function (User $user) {
            return $user->isManagement();
        });

        Gate::define('is-owner', function (User $user) {
            return $user->isOwner();
        });

        /**
         * Ticket Policies
         */
        Gate::define('view-ticket', function (User $user, Ticket $ticket) {
            if ($user->id === $ticket->user_id) {
                return true;
            }

            if ($user->isManagement()) {
                return true;
            }

            if($user->hasDiscordRole($ticket->category->guild, $ticket->category->role)) {
                return true;
            }

            if (in_array($user->id, $ticket->allowed_users)) {
                return true;
            }

            return false;
        });

        Gate::define('is-ticket-owner', function (User $user, Ticket $ticket) {
            return $user->id === $ticket->user_id;
        });

        Gate::define('is-not-allowed-user', function (User $user, Ticket $ticket) {

            if (in_array($user->id, $ticket->allowed_users)) {
                return false;
            }

            return true;
        });

        Gate::define('can-support', function (User $user, Ticket $ticket) {
            if ($user->isManagement()) return true;

            if ($user->id === $ticket->user_id) return false;;

            if($user->hasDiscordRole($ticket->category->guild, $ticket->category->role)) return true;

            return false;
        });

        Gate::define('has-any-category-role', function (User $user) {
            $categories = TicketCategory::all();

            foreach ($categories as $category) {
                if ($user->hasDiscordRole($category->guild, $category->role)) {
                    return true;
                }
            }

            return false;
        });

        Gate::define('has-category-role', function (User $user, TicketCategory $category) {
            if ($user->isManagement()) return true;

            return $user->hasDiscordRole($category->guild, $category->role);
        });

        /**
         * Application Policies
         */

        Gate::define('view-application', function (User $user, Application $application) {
            if ($user->isManagement()) return true;

            if ($user->id === $application->user_id) return true;

            if ($user->hasDiscordRole($application->category->guild, $application->category->manager_role)) return true;

            if ($user->hasDiscordRole($application->category->guild, $application->category->worker_role)) return true;

            return false;
        });

        Gate::define('is-application-manager-of-any', function (User $user) {
            if ($user->isManagement()) return true;

            $categories = ApplicationCategory::all();
            foreach ($categories as $category) {
                if ($user->hasDiscordRole($category->guild, $category->manager_role)) {
                    return true;
                }
            }

            return false;
        });

        Gate::define('is-application-manager-of', function (User $user, ApplicationCategory $category) {
            if ($user->isManagement()) return true;

            return $user->hasDiscordRole($category->guild, $category->manager_role);
        });

        Gate::define('is-application-worker-of-any', function (User $user) {
            if ($user->isManagement()) return true;

            $categories = ApplicationCategory::all();
            foreach ($categories as $category) {
                if ($user->hasDiscordRole($category->guild, $category->worker_role)) {
                    return true;
                }
            }

            return false;
        });

        Gate::define('is-application-worker-of', function (User $user, $categoryId) {
            if ($user->isManagement()) return true;

            $category = ApplicationCategory::findOrFail($categoryId);

            if ($user->hasDiscordRole($category->guild, $category->manager_role)) return true;

            return $user->hasDiscordRole($category->guild, $category->worker_role);
        });

        /**
         * Server Policies
         */

        Gate::define('can-view-server', function (User $user, Server $server) {
            if ($user->isOwner()) return true;
            if ($user->isManagement()) return true;

            $roles = ServerRole::where('server_id', $server->id)->get();

            foreach ($roles as $role) {
                //if the user has the role they are trying to view, they can view it.
                if ($user->hasDiscordRole($role->guild, $role->id)) {
                    return true;
                }
            }

            return false;
        });

        Gate::define('can-manage-roles', function (User $user, Server $server) {

            if ($user->isOwner()) return true;
            if ($user->isManagement()) return true;

            $roles = ServerRole::where('server_id', $server->id)->get();

            //each role has a priority, if the user has a role with a higher priority than the role they are trying to manage, they can manage it.
            foreach ($roles as $role) {
                //if the user has the role they are trying to manage, they can manage it.
                if ($user->hasDiscordRole($role->guild, $role->id)) {
                    if ($role->can_manage_permissions) return true;
                }
            }

            return false;
        });

        Gate::define('can-view-timeclock', function (User $user, Server $server) {
            if ($user->isOwner()) return true;
            if ($user->isManagement()) return true;

            $roles = ServerRole::where('server_id', $server->id)->get();

            foreach ($roles as $role) {
                if ($user->hasDiscordRole($role->guild, $role->id)) {
                    if ($role->can_view_timeclock) return true;
                }
            }
        });
    }
}
