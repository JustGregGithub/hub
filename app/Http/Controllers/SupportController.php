<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationCategory;
use App\Models\Ticket;
use App\Models\TicketCategory;
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

        if ($user->isManagement()) {
            $user_categories = TicketCategory::all();
        } else {
            $user_categories = TicketCategory::whereIn('role', $user->getDiscordRoles())->get();
        }

        return view('ticket.support', [
            'categories' => $user_categories
        ]);
    }

    public function ticket_support_category(TicketCategory $ticketCategory) {
        Gate::authorize('has-category-role', $ticketCategory);

        return view('ticket.support_tickets', [
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

        if ($user->isManagement()) {
            $user_categories = ApplicationCategory::all();
        } else {
            $user_categories = ApplicationCategory::whereIn('worker_role', $user->getDiscordRoles())->get();
        }

        return view('application.support', [
            'categories' => $user_categories
        ]);
    }

    public function application_support_category(ApplicationCategory $application_categories) {
        Gate::authorize('is-application-worker-of', $application_categories->id);

        return view('application.support_applications', [
            'category' => $application_categories,
            'applications' => $application_categories->applications()->get()
        ]);
    }
}
