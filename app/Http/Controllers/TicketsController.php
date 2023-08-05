<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

class TicketsController extends Controller
{
    public function index(Request $request) {
        return view('ticket.home', [
            'tickets' => $request->user()->tickets()->limit(3)->orderByDesc('created_at')->get(),
            'ticketCount' => $request->user()->tickets()->count(),
            'pinnedTickets' => $request->user()->tickets()->where('pinned', true)->orderByDesc('created_at')->get(),
            'pinnedTicketCount' => $request->user()->tickets()->where('pinned', true)->count(),
        ]);
    }

    //TODO: There defo is a better way to do this
    public function all(Request $request) {
        $page = $request->route('page');
        if (!$page || $page < 1) $page = 1;
        $offset = ($page - 1) * 10;

        $tickets = $request->user()->tickets()->orderByDesc('updated_at')->skip($offset)->take(10)->get();

        if ($tickets->count() == 0) abort(404);

        return view('ticket.all', [
            'tickets' => $tickets,
            'ticketsOnPage' => $request->user()->tickets()->orderByDesc('created_at')->paginate($page * 10)->count(),
            'totalTickets' => $request->user()->tickets()->count()
        ]);
    }

    public function post_create(Request $request) {
        $request->validate([
            'title' => 'required|min:10|max:100',
            'category' => 'required|integer|exists:ticket_categories,id',
            'content' => 'required|min:50|max:5000'
        ]);

        //remove any script tags from the content
        $request->merge([
            'content' => strip_tags($request->input('content'), Ticket::ALLOWED_TAGS)
        ]);

        $input = $request->all();

        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'category_id' => $input['category'],
            'title' => $input['title'],
            'content' => $input['content'],
        ]);

        // Add slug and save.
        $ticket->slug = $ticket->makeSlug();
        $ticket->save();

        DiscordAlert::message('<@' . $ticket->user->id . '>', [
            [
                'title' => 'Ticket Creation',
                'description' => 'Your ticket has been created! Please wait for a response from <@&' . $ticket->category->role . '>.',
                'color' => '#25DC00',
                'url' => route('tickets.view', $ticket->slug),
                'timestamp' => Carbon::now()->toIso8601String(),
                'footer' => [
                    'text' => 'Lynus.gg Hub'
                ]
            ]
        ]);

        return redirect()->route('tickets.view', $ticket->slug)->with('success', 'Successfully created the ticket!');
    }

    public function post_reply(Request $request, Ticket $ticket) {
        Gate::authorize('view-ticket', $ticket);

        $request->validate([
            'reply' => 'required|max:5000',
            'type' => 'integer|in:0,1'
        ]);

        //remove any script tags from the content
        $request->merge([
            'reply' => strip_tags($request->input('reply'), Ticket::ALLOWED_TAGS)
        ]);

        $input = $request->all();

        //if type is undefined, then set it to 0
        if (!isset($input['type'])) $input['type'] = 0;

        $ticket->replies()->create([
            'user_id' => $request->user()->id,
            'type' => $input['type'],
            'content' => $input['reply']
        ]);

        //if the reply is not from the user, or any sub user, then send a discord message
        if ($ticket->user->id != $request->user()->id && array_search($request->user()->id, (array)$ticket->allowed_users) === false) {
            DiscordAlert::message('<@' . $ticket->user->id . '>', [
                [
                    'title' => 'Ticket Reply',
                    'description' => 'Your ticket has received a reply from <@&' . $ticket->category->role . '>.',
                    'color' => '#B700DC',
                    'url' => route('tickets.view', $ticket->slug),
                    'timestamp' => Carbon::now()->toIso8601String(),
                    'footer' => [
                        'text' => 'Lynus.gg Hub'
                    ]
                ]
            ]);
        }

        //if the reply user is the ticket owner or sub user, set it to Awaiting Reply
        if ($ticket->user->id == $request->user()->id || array_search($request->user()->id, (array)$ticket->allowed_users) !== false) {
            $ticket->status = Ticket::STATUSES['Awaiting Reply'];
            $ticket->save();
        } else {
            $ticket->status = Ticket::STATUSES['Answered'];
            $ticket->save();
        }

        return redirect()->route('tickets.view', $ticket->slug)->with('success', 'Successfully replied to the ticket!');
    }

    public function post_update(Request $request, Ticket $ticket) {
        Gate::authorize('view-ticket', $ticket);

        $request->validate([
            'category' => [
                'required',
                'integer',
                Rule::in(TicketCategory::all()->pluck('id')->toArray())
            ],

            'status' => [
                'required',
                'integer',
                Rule::in(Ticket::STATUSES)
            ],

            'assigned_person' => [
                'sometimes'
            ]
        ]);

        $input = $request->all();
        $user = $request->user();

        //check whether the status has changed
        if ($ticket->status != $input['status']) {
            if ($input['status'] == Ticket::STATUSES['Closed']) {
                $message = 'Your ticket has been closed by <@' . $user->id . '>.';
            } else if ($input['status'] == Ticket::STATUSES['Transferred']) {
                $message = 'Your ticket has been transferred by <@' . $user->id . '> to <@&' . TicketCategory::getRole($input['category']) . '>.';
            } else {
                $message = 'Your ticket may have been updated by <@' . $user->id . '>.';
            }
            $colour = $input['status'] == Ticket::STATUSES['Closed'] ? '#DC0000' : '#25DC00';

            DiscordAlert::message('<@' . $ticket->user->id . '>', [
                [
                    'title' => 'Ticket Update',
                    'description' => 'Your ticket has been marked as ' . Ticket::status($input['status']) . '! ' . $message,
                    'color' => $colour,
                    'url' => route('tickets.view', $ticket->slug),
                    'timestamp' => Carbon::now()->toIso8601String(),
                    'footer' => [
                        'text' => 'Lynus.gg Hub'
                    ]
                ]
            ]);
        }

        $ticket->category_id =  $input['category'];
        $ticket->status = $input['status'];

        //if the assigned person value is -1, there should be no one as the assigned person

        if ($input['assigned_person'] == -1) {
            $ticket->assigned_person = null;
        } else {
            $ticket->assigned_person = $input['assigned_person'] ?: $ticket->assigned_person;
        }


        $ticket->save();

        return redirect()->route('tickets.view', $ticket->slug)->with('success', 'Successfully updated the ticket!');
    }

    public function patch_user(Request $request, Ticket $ticket) {
        Gate::authorize('view-ticket', $ticket);

        $request->validate([
            'userid' => [
                'required',
                'exists:users,id'
            ],
        ]);

        $input = $request->all();

        if ($ticket->user_id == $input['userid']) {
            return redirect()->route('tickets.view', $ticket->slug)->withErrors('You cannot add the ticket creator as an additional person!');
        }

        if (in_array($input['userid'], json_decode($ticket->allowed_users, true))) {
            return redirect()->route('tickets.view', $ticket->slug)->withErrors('This user already has access to the ticket!');
        }

        if (User::findOrFail($input['userid'])->hasDiscordRole($ticket->category->role)) {
            return redirect()->route('tickets.view', $ticket->slug)->withErrors('This user already has access to the ticket!');
        }

        $allowed_users = json_decode($ticket->allowed_users, true);
        $allowed_users[] = $input['userid'];

        $ticket->allowed_users = json_encode($allowed_users);
        $ticket->save();

        return redirect()->route('tickets.view', $ticket->slug)->with('success', 'Successfully added the user to the ticket!');
    }

    public function delete_user(Request $request, Ticket $ticket) {
        Gate::authorize('is-ticket-owner', $ticket);
        Gate::authorize('can-support', $ticket);

        $request->validate([
            'userid' => [
                'required',
                'exists:users,id'
            ],
        ]);

        $input = $request->all();

        $allowed_users = json_decode($ticket->allowed_users, true);
        $allowed_users = array_diff($allowed_users, [$input['userid']]);
        $ticket->allowed_users = json_encode($allowed_users);
        $ticket->save();

        return redirect()->route('tickets.view', $ticket->slug)->with('success', 'Successfully removed the user from the ticket!');
    }

    public function patch_pin(Ticket $ticket) {
        Gate::authorize('is-ticket-owner', $ticket);

        $ticket->pinned = $ticket->pinned ? false : true;

        if ($ticket->pinned && $ticket->user->tickets()->where('pinned', true)->count() >= 5) {
            return redirect()->route('tickets.home', $ticket->slug)->withErrors('You cannot pin more than 5 tickets!');
        }

        $ticket->save();

        return redirect()->route('tickets.home', $ticket->slug)->with('success', $ticket->pinned ? 'Successfully pinned the ticket!' : 'Successfully unpinned the ticket!');
    }

    public function create() {
        return view('ticket.create', [
            'categories' => TicketCategory::all()
        ]);
    }

    public function view(Ticket $ticket) {
        Gate::authorize('view-ticket', $ticket);

        $createdat = (new Carbon($ticket->created_at))->format('m/d/Y H:i');
        $updatedat = (new Carbon($ticket->updated_at))->format('m/d/Y H:i');

        $allowed_users = User::whereIn('id', $ticket->allowed_users)->get();

        return view('ticket.view', [
            'ticket' => $ticket,
            'created_at' => $createdat,
            'updated_at' => $updatedat,
            'allowed_users' => $allowed_users
        ]);
    }

    // Category Routes

    public function patch_category(Request $request) {
        Gate::authorize('is-mgmt');

        $request->validate([
            'category' => 'required',
            'newname' => 'required_if:category,new|max:100',
            'role' => 'required_if:category,new',
            'isHidden' => 'boolean|nullable'
        ]);

        $input = $request->all();
        $isHidden = 0;

        if($input['role']) $request->validate(['role' => 'numeric|min_digits:17|max_digits:20']);
        if (isset($input['isHidden'])) {
            $isHidden = 1;
        }

        // -2 is for creating a new category
        if ($input['category'] == "new") {

            TicketCategory::create([
                'role' => $input['role'],
                'name' => $input['newname'],
                'is_hidden' => $isHidden
            ]);

            return redirect()->route('tickets.settings')->with('success', 'Successfully created a new category!');
        } else {
            // when tis not a new category we need to validate that its a number.
            $request->validate(['category' => 'numeric']);
        }

        $category = TicketCategory::findOrFail($input['category']);

        if ($input['newname']) $category->name = $input['newname'];
        if ($input['role']) $category->role = $input['role'];
        $category->is_hidden = $isHidden;

        $category->save();

        return redirect()->route('tickets.settings')->with('success', 'You have successfully changed the category details!');
    }

    public function delete_category(Request $request) {
        Gate::authorize('is-owner');

        $request->validate([
            'category' => 'required|integer'
        ]);

        $input = $request->all();
        $category = TicketCategory::findOrFail($input['category']);

        if (!$category->deletable) {
            return redirect()->route('tickets.settings')->withErrors('You cannot delete this category!');
        }

        $tickets = $category->tickets()->get();
        $default = TicketCategory::getDefault();

        foreach ($tickets as $ticket) {
            $ticket->category_id = $default->id;
            $ticket->save();
        }

        $category->delete();

        return redirect()->route('tickets.settings')->with('success', 'Successfully deleted the category!');
    }


    // Management Routes

    public function settings() {
        Gate::authorize('is-owner');

        return view('ticket.settings', [
            'defaultCategory' => TicketCategory::getDefault(),
            'categories' => TicketCategory::all()
        ]);
    }
}
