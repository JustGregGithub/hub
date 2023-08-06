<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAiInput;
use App\Models\Application;
use App\Models\ApplicationCategory;
use App\Models\ApplicationQuestion;
use App\Models\ApplicationSection;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Spatie\DiscordAlerts\Facades\DiscordAlert;

class ApplicationController extends Controller
{
    /*
     * Frontend
     */

    public function index(Request $request) {
        $user = $request->user();
        $applicationSections = ApplicationSection::with('categories')->get();

        return view('application.home', [
            'application_sections' => $applicationSections,
            'all_applications' => ApplicationCategory::all(),
            'applications' => $user->applications()->get()
        ]);
    }

    public function apply(Request $request, ApplicationCategory $application_categories) {
        $user = $request->user();

        if (Application::where('application_category_id', $application_categories->id)->where('user_id', $user->id)->where('status', Application::STATUSES['Under Review'])->exists()) {
            return redirect()->route('applications.home')->withErrors('You already have an application in this category!');
        }

        return view('application.apply', [
            'application' => $application_categories,
        ]);
    }

    public function view(Request $request, Application $application) {
        Gate::authorize('view-application', $application);

        return view('application.view', [
            'application' => $application,
        ]);
    }

    public function settings(Request $request) {
        Gate::authorize('is-application-manager-of-any');

        $categories = [];

        foreach (ApplicationCategory::all() as $category) {
            if ($request->user()->isManagement()) {
                $categories[] = $category;
                continue;
            }

            if ($request->user()->hasDiscordRole($category->guild, $category->manager_role)) {
                $categories[] = $category;
            }
        }

        return view('application.settings', [
            'sections' => ApplicationSection::all(),
            'categories' => $categories
        ]);
    }

    public function edit(Request $request, ApplicationCategory $application_categories) {
        Gate::authorize('is-application-manager-of', $application_categories);

        return view('application.settings-view', [
            'category' => $application_categories,
            'ticketCategories' => TicketCategory::all(),
            'questions' => $application_categories->questions()->get()
        ]);
    }

    /*
     * Backend - Application Categories
     */

    public function post_create_application_category(Request $request) {
        Gate::authorize('is-mgmt');

        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'manager_role' => 'required|string',
            'worker_role' => 'required|string',
        ]);

        if (!ApplicationSection::where('is_default', true)->exists()) {
            ApplicationSection::create([
                'name' => 'Default',
                'description' => 'The default section for applications.',
                'is_default' => true
            ]);
        }

        $input = $request->all();
        $default_section = ApplicationSection::where('is_default', true)->first();

        ApplicationCategory::create([
            'name' => $input['name'],
            'description' => $input['description'],
            'manager_role' => $input['manager_role'],
            'worker_role' => $input['worker_role'],
            'application_section_id' => $default_section->id,
        ]);

        return redirect()->route('applications.settings')->with('success', 'Application category created!');
    }

    public function patch_interview_application_category(Request $request, ApplicationCategory $application_categories) {
        Gate::authorize('is-application-manager-of', $application_categories);

        $request->validate([
            'interviewTicket' => 'required|boolean',
            'category' => 'required|integer|exists:ticket_categories,id',
        ]);

        $input = $request->all();

        //if the new stats are the same as the old ones, do nothing
        if ($application_categories->interview_ticket == $input['interviewTicket'] && $application_categories->interview_ticket_category_id == $input['category']) {
            return redirect()->back()->with('success', 'Application category interview settings changed!');
        }

        $application_categories->create_interview = $input['interviewTicket'];
        $application_categories->interview_category = $input['category'];
        $application_categories->save();

        return redirect()->back()->with('success', 'Application category interview settings changed!');
    }

    public function patch_info_application_category(Request $request, ApplicationCategory $application_categories) {
        Gate::authorize('is-application-manager-of', $application_categories);

        $request->validate([
            'information' => strip_tags($request->input('content'), Ticket::ALLOWED_TAGS)
        ]);

        $input = $request->all();

        $application_categories->information = $input['information'];
        $application_categories->save();

        return redirect()->back()->with('success', 'Application category info changed!');
    }

    public function delete_delete_application_category(ApplicationCategory $application_categories) {
        Gate::authorize('is-mgmt');

        foreach ($application_categories->applications()->get() as $application) {
            $application->delete();
        }

        foreach ($application_categories->questions()->get() as $question) {
            $question->delete();
        }

        $application_categories->delete();

        return redirect()->route('applications.settings')->with('success', 'Application category deleted!');
    }

    /*
     * Backend - Application Sections
     */

    public function post_create_application_section(Request $request) {
        Gate::authorize('is-mgmt');

        $request->validate([
            'name' => 'required|string',
            'colour_left' => 'required|string',
            'colour_right' => 'required|string',
        ]);

        $input = $request->all();
        $default = false;

        //check whether a default is already set. If not, create a variable for it
        if (!ApplicationSection::where('is_default', true)->exists()) {
            $default = true;
        }

        ApplicationSection::create([
            'name' => $input['name'],
            'colour_left' => $input['colour_left'],
            'colour_right' => $input['colour_right'],
            'is_default' => $default,
        ]);

        return redirect()->back()->with('success', 'Application section created!');
    }

    public function patch_set_default_application_section(Request $request, ApplicationSection $application_sections) {
        Gate::authorize('is-mgmt');

        $sections = ApplicationSection::all();
        foreach ($sections as $section) {
            $section->is_default = false;
            $section->save();
        }

        $application_sections->is_default = true;
        $application_sections->save();

        return redirect()->back()->with('success', 'Application section set as default!');
    }

    public function patch_rename_application_section(Request $request, ApplicationSection $application_sections) {
        Gate::authorize('is-mgmt');

        $request->validate([
            'name' => 'required|string',
        ]);

        $input = $request->all();

        $application_sections->name = $input['name'];
        $application_sections->save();

        return redirect()->back()->with('success', 'Application section renamed!');
    }

    public function patch_colour_application_section(Request $request, ApplicationSection $application_sections) {
        Gate::authorize('is-mgmt');

        $request->validate([
            'colour_left' => 'required|string',
            'colour_right' => 'required|string',
        ]);

        $input = $request->all();

        //check if colour is valid using hex codes with #
        if (!preg_match('/^#[a-f0-9]{6}$/i', $input['colour_left']) || !preg_match('/^#[a-f0-9]{6}$/i', $input['colour_right'])) {
            return redirect()->back()->withErrors('Please enter a valid hex code!');
        }

        $application_sections->colour_left = $input['colour_left'];
        $application_sections->colour_right = $input['colour_right'];
        $application_sections->save();

        return redirect()->back()->with('success', 'Application section colours changed!');
    }

    public function delete_delete_application_section(Request $request, ApplicationSection $application_sections) {
        Gate::authorize('is-mgmt');

        $categories = ApplicationCategory::where('application_section_id', $application_sections->id)->get();
        foreach ($categories as $category) {
            $category->section_id = null;
            $category->save();
        }

        $section = ApplicationSection::where('id', $application_sections->id)->first();

        if ($section->is_default) {
            return redirect()->back()->withErrors('Please set another section as default before deleting this section!');
        }

        $section->delete();

        return redirect()->back()->with('success', 'Application section deleted!');
    }

    /*
     * Backend - Application Questions
     */

    public function post_create_question(Request $request, ApplicationCategory $application_categories) {
        Gate::authorize('is-application-manager-of', $application_categories);

        $request->validate([
            'category_id' => 'required|integer|exists:application_categories,id',
            'question' => 'required|string',
            'position' => 'required|integer',
        ]);

        $input = $request->all();

        //check if position is already taken
        foreach ($application_categories->questions()->get() as $question) {
            if ($question->position == $input['position']) {
                foreach ($application_categories->questions()->get() as $question) {
                    if ($question->position >= $input['position']) {
                        $question->position++;
                        $question->save();
                    }
                }
            }
        }

        $application_categories->questions()->create([
            'application_category_id' => $input['category_id'],
            'question' => $input['question'],
            'position' => $input['position']
        ]);

        return redirect()->route('applications.settings.edit', $application_categories->id)->with('success', 'Question created!');
    }

    public function patch_move_question(Request $request, ApplicationCategory $application_categories) {
        Gate::authorize('is-application-manager-of', $application_categories);

        $request->validate([
            'question_id' => 'required|string',
            'position' => 'required|integer',
        ]);

        $input = $request->all();
        $targetPosition = $input['position'];

        // Find the question you want to move
        $targetQuestion = null;
        foreach ($application_categories->questions()->get() as $question) {
            if ($question->id == $input['question_id']) {
                $targetQuestion = $question;
                break;
            }
        }

        if ($targetQuestion) {
            // Check if there is already a question at the target position
            $existingQuestionAtTargetPosition = $application_categories->questions()->where('position', $targetPosition)->first();

            if ($existingQuestionAtTargetPosition) {
                // If there's a question at the target position, swap their positions
                $existingPosition = $existingQuestionAtTargetPosition->position;
                $existingQuestionAtTargetPosition->position = $targetQuestion->position;
                $existingQuestionAtTargetPosition->save();

                $targetQuestion->position = $existingPosition;
                $targetQuestion->save();
            } else {
                // If there's no question at the target position, simply move the target question to that position
                $targetQuestion->position = $targetPosition;
                $targetQuestion->save();
            }
        }

        $question = $application_categories->questions()->where('id', $input['question_id'])->first();
        $question->position = $input['position'];
        $question->save();

        return redirect()->route('applications.settings.edit', $application_categories->id)->with('success', 'Question moved to position ' . $input['position'] . '!');
    }

    public function patch_rename_question(Request $request, ApplicationCategory $application_categories) {
        Gate::authorize('is-application-manager-of', $application_categories);

        $request->validate([
            'question_id' => 'required|string',
            'question' => 'required|string',
        ]);

        $input = $request->all();

        $question = $application_categories->questions()->where('id', $input['question_id'])->first();
        $question->question = $input['question'];
        $question->save();

        return redirect()->route('applications.settings.edit', $application_categories->id)->with('success', 'Question renamed!');
    }

    public function patch_type_question(Request $request, ApplicationCategory $application_categories)
    {
        Gate::authorize('is-application-manager-of', $application_categories);
        $input = $request->all();
        $positionedInputs = [];

        $request->validate([
            'question_id' => 'required|string',
            'type' => 'required|string',
        ]);

        if ($request->type == ApplicationQuestion::OPTION_TYPES['Select'] || $request->type == ApplicationQuestion::OPTION_TYPES['Radio']) {
            $request->validate([
                'input.*' => 'required|string',
            ]);

            $position = 1;

            foreach ($input['input'] as $option) {
                $positionedInputs[$option] = $position;
                $position++;
            }
        }

        $question = $application_categories->questions()->where('id', $input['question_id'])->first();
        $question->type = $input['type'];
        $question->options = $positionedInputs;
        $question->save();

        return redirect()->route('applications.settings.edit', $application_categories->id)->with('success', 'Question type changed!');

    }

    public function delete_delete_question(Request $request, ApplicationCategory $application_categories) {
        Gate::authorize('is-application-manager-of', $application_categories);

        $request->validate([
            'question_id' => 'required|string',
        ]);

        $input = $request->all();

        $question = $application_categories->questions()->where('id', $input['question_id'])->first();
        $question->delete();

        // Reorder the questions
        $position = 1;
        foreach ($application_categories->questions()->get() as $question) {
            $question->position = $position;
            $question->save();
            $position++;
        }

        return redirect()->route('applications.settings.edit', $application_categories->id)->with('success', 'Question deleted!');
    }

    /*
     * Backend - Applications
     */

    public function post_apply(Request $request, ApplicationCategory $application_categories) {
        $user = $request->user();
        $input = $request->all();
        $validationRules = [];

        if (Application::where('user_id', $user->id)->where('application_category_id', $application_categories->id)->where('status', Application::STATUSES['Under Review'])->first()) {
            return redirect()->back()->withErrors('You have already applied for this category!');
        }

        foreach ($application_categories->questions()->get() as $question) {
            switch ($question->type) {
                case ApplicationQuestion::OPTION_TYPES['Input'] || ApplicationQuestion::OPTION_TYPES['Textarea']:
                    $validationRules["questions.{$question->id}"] = 'required|string';
                    break;

                case ApplicationQuestion::OPTION_TYPES['Radio'] || ApplicationQuestion::OPTION_TYPES['Select']:
                    $validationRules["questions.{$question->id}"] = 'required';
                    break;
            }
        }

        $request->validate($validationRules);

        $application = Application::create([
            'user_id' => $user->id,
            'application_category_id' => $application_categories->id,
            'questions' => $application_categories->questions()->get(),
            'content' => $input['questions'],
            'status' => Application::STATUSES[Application::DEFAULT_STATUS],
        ]);

        ProcessAiInput::dispatch($application);

        return redirect()->route('applications.home')->with('success', 'Application submitted!');
    }

    public function patch_status(Request $request, Application $application) {
        Gate::authorize('is-application-worker-of', $application->application_category_id);

        $user = $request->user();
        $input = $request->all();
        $category = $application->category()->first();

        if ($user->id == $application->user_id) {
            return redirect()->route('applications.view', $application->id)->withErrors('You cannot edit your own applications status!');
        }

        $request->validate([
            'status' => 'required|string|in:' . implode(',', Application::STATUSES),
            'reason' => 'required_if:status,' . Application::STATUSES['Denied'] . '|string',
        ]);

        $application->status = $input['status'];
        $application->worker_id = $user->id;
        if ($input['status'] == Application::STATUSES['Denied']) $application->reason = $input['reason'];

        $application->save();

        if ($application->status == Application::STATUSES['Denied']) {
            $message = 'Please check your application for more information. Denied by ' . $user->displayName() . '.';;
            $colour = '#ff0000';
        } else if ($application->status == Application::STATUSES['Accepted']) {
            $colour = '#00ff00';

            if ($category->create_interview) {
                $message = 'Congratulations! You have been accepted! An interview request has been sent to you in the form of a ticket. Accepted by <@' . $user->id . '>!';;
                $ticket = Ticket::create([
                    'user_id' => $application->user_id,
                    'category_id' => $category->interview_category,
                    'title' => 'Interview Request - ' . $category->name,
                    'content' => 'Congratulations! Your application for ' . $category->name . ' has been accepted! Please reply to this ticket with your availability for an interview. Remember to include the date, time, and timezone.',
                    'status' => Ticket::STATUSES['Open']
                ]);

                $ticket->slug = $ticket->makeSlug();
                $ticket->save();
            } else {
                $message = 'Congratulations! You have been accepted by <@' . $user->id . '>!';
            }
        } else if ($application->status == Application::STATUSES['Under Review']) {
            $message = 'Your application is now under review by <@' . $user->id . '>!';;
            $colour = '#ffff00';
        }

        DiscordAlert::message('<@' . $application->user_id . '>', [
            [
                'title' => 'Application Update',
                'description' => 'Your application for ' . $application->category->name . ' has been ' . Application::status($application->status) . '! ' . $message,
                'color' => $colour,
                'url' => route('applications.view', $application->id),
                'timestamp' => Carbon::now()->toIso8601String(),
                'footer' => [
                    'text' => 'Lynus.gg Hub'
                ]
            ]
        ]);

        return redirect()->back()->with('success', 'Application status changed!');
    }
}
