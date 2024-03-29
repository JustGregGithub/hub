<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Models\Hub\ApplicationCategory;
use App\Models\Hub\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SupportController extends Controller
{

    /*
     * Tickets
     */

    public function ticket_support(Request $request) {
        Gate::authorize('has-any-category-role');

        $user = $request->user();
        $user_categories = [];

        if (!$user->isOwner()) {
            foreach ($user->getDiscordRoles() as $guildId => $roles) {
                foreach ($roles as $role) {
                    // Check if there are any categories that match the current guild and role
                    $matchingCategories = TicketCategory::where('guild', $guildId)
                        ->where('role', $role)
                        ->get();

                    // Add the matching categories to the user_categories array
                    $user_categories = array_merge($user_categories, $matchingCategories->all());
                }
            }
        } else {
            $user_categories = TicketCategory::all();
        }

        return view('hub.ticket.support', [
            'categories' => $user_categories
        ]);
    }

    public function ticket_support_category(TicketCategory $ticketCategory) {
        Gate::authorize('has-category-role', $ticketCategory);

        return view('hub.ticket.support_tickets', [
            'category' => $ticketCategory,
            'tickets' => $ticketCategory->tickets()
        ]);
    }

    /*
     * Applications
     */

    public function application_support(Request $request) {
        Gate::authorize('is-application-worker-of-any');

        $user = $request->user();

        if ($user->isOwner()) {
            $user_categories = ApplicationCategory::all();
        } else {
            $discordRoles = $user->getDiscordRoles();
            $roleIds = [];

            foreach ($discordRoles as $roleIdsArray) {
                $roleIds = array_merge($roleIds, $roleIdsArray);
            }

            $user_categories = ApplicationCategory::whereIn('worker_role', $roleIds)->get();
        }

        return view('hub.application.support', [
            'categories' => $user_categories
        ]);
    }

    public function application_support_category(ApplicationCategory $application_categories) {
        Gate::authorize('is-application-worker-of', $application_categories->id);

        return view('hub.application.support_applications', [
            'category' => $application_categories,
            'applications' => $application_categories->applications()->get()
        ]);
    }
}
