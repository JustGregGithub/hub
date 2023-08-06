<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TicketsController;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () { return view('welcome'); });

//redirect / to /home - Temporary
Route::redirect('/', '/home', 302);

Route::get('/store', function () { return Redirect::to('https://store.lynus.gg'); })->name('store');

/*
    Logged in area.
*/
Route::middleware('auth')->group(function () {
    Route::get('/home',                                            [HomeController::class, 'home'])                ->name('home');

    Route::prefix('/tickets')->group(function () {
        Route::get('/',                                            [TicketsController::class, 'index'])             ->name('tickets.home');
        Route::get('/all/{page}',                                  [TicketsController::class, 'all'])               ->name('tickets.all');
        Route::get('/create',                                      [TicketsController::class, 'create'])            ->name('tickets.create');
        Route::post('/create',                                     [TicketsController::class, 'post_create'])       ->name('tickets.create.post');
        Route::post('/reply/{ticket:slug}',                        [TicketsController::class, 'post_reply'])        ->name('tickets.reply.post');
        Route::post('/update/{ticket:slug}',                       [TicketsController::class, 'post_update'])       ->name('tickets.update.post');
        Route::patch('/user/{ticket:slug}',                        [TicketsController::class, 'patch_user'])        ->name('tickets.user.patch');
        Route::delete('/user/{ticket:slug}',                       [TicketsController::class, 'delete_user'])       ->name('tickets.user.delete');
        Route::patch('/pin/{ticket:slug}',                         [TicketsController::class, 'patch_pin'])         ->name('tickets.pin.patch');

        //View ticket
        Route::get('/{ticket:slug}',                               [TicketsController::class, 'view'])              ->name('tickets.view');
   });

    Route::prefix('/applications')->group(function () {
        Route::get('/',                                            [ApplicationController::class, 'index'])         ->name('applications.home');

        Route::get('/view/{application:id}',                       [ApplicationController::class, 'view'])          ->name('applications.view');
        Route::patch('/status/{application:id}',                   [ApplicationController::class, 'patch_status'])  ->name('applications.status.patch');

        Route::get('/{application_categories:id}',                 [ApplicationController::class, 'apply'])         ->name('applications.apply');
        Route::post('/{application_categories:id}',                [ApplicationController::class, 'post_apply'])    ->name('applications.apply.post');
    });

    /*
        Settings
     */
    Route::prefix('/ticketsettings')->group(function () {
        Route::get('/',                                            [TicketsController::class, 'settings'])          ->name('tickets.settings');
        Route::patch('/category',                                  [TicketsController::class, 'patch_category'])    ->name('tickets.category.patch');
        Route::delete('/category',                                 [TicketsController::class, 'delete_category'])   ->name('tickets.category.delete');
    });

    Route::prefix('/applicationsettings')->group(function () {
        Route::get('/',                                            [ApplicationController::class, 'settings'])      ->name('applications.settings');
        Route::get('/edit/{application_categories:id}',            [ApplicationController::class, 'edit'])          ->name('applications.settings.edit');

        Route::prefix('/section')->group(function () {
            Route::post('/create',                                 [ApplicationController::class, 'post_create_application_section'])   ->name('applications.settings.section.create');
            Route::patch('/default/{application_sections:id}',     [ApplicationController::class, 'patch_set_default_application_section'])   ->name('applications.settings.section.default');
            Route::patch('/rename/{application_sections:id}',      [ApplicationController::class, 'patch_rename_application_section'])   ->name('applications.settings.section.rename');
            Route::patch('/colour/{application_sections:id}',      [ApplicationController::class, 'patch_colour_application_section'])   ->name('applications.settings.section.colour');
            Route::delete('/delete/{application_sections:id}',     [ApplicationController::class, 'delete_delete_application_section'])    ->name('applications.settings.section.delete');
        });

        Route::prefix('/application')->group(function () {
            Route::post('/create',                                 [ApplicationController::class, 'post_create_application_category'])   ->name('applications.settings.application.create');
            Route::patch('/interview/{application_categories:id}', [ApplicationController::class, 'patch_interview_application_category'])                ->name('applications.settings.application.interview');
            Route::patch('/info/{application_categories:id}',      [ApplicationController::class, 'patch_info_application_category'])                ->name('applications.settings.application.information');
            Route::delete('/delete/{application_categories:id}',   [ApplicationController::class, 'delete_delete_application_category'])    ->name('applications.settings.application.delete');
        });

        Route::prefix('/question')->group(function () {
            Route::post('/create/{application_categories:id}',     [ApplicationController::class, 'post_create_question'])   ->name('applications.settings.question.create');
            Route::patch('/move/{application_categories:id}',      [ApplicationController::class, 'patch_move_question'])    ->name('applications.settings.question.move');
            Route::patch('/rename/{application_categories:id}',    [ApplicationController::class, 'patch_rename_question'])    ->name('applications.settings.question.rename');
            Route::patch('/type/{application_categories:id}',      [ApplicationController::class, 'patch_type_question'])    ->name('applications.settings.question.type');
            Route::delete('/delete/{application_categories:id}',   [ApplicationController::class, 'delete_delete_question'])    ->name('applications.settings.question.delete');
        });
    });

    /*
        Support
    */
    Route::get('/ticket-support',                                         [SupportController::class, 'ticket_support'])           ->name('tickets.support');
    Route::get('/ticket-support/{ticket_category:id}',                    [SupportController::class, 'ticket_support_category'])   ->name('tickets.support_tickets');

    Route::get('/application-support',                                    [SupportController::class, 'application_support'])           ->name('application.support');
    Route::get('/application-support/{application_categories:id}',        [SupportController::class, 'application_support_category'])   ->name('application.support_applications');

    /*
        Profile
    */
    Route::get('/profile',                                         [ProfileController::class, 'edit'])              ->name('profile.edit');
    Route::post('/profile/signature',                              [ProfileController::class, 'post_signature'])    ->name('profile.signature.post');
});

/*
    Api area
*/
Route::middleware('ApiAuth')->prefix('/api')->group(function () {
    Route::post('/discord/roles/{user:id}',                        [ApiController::class, 'post_discord_roles'])   ->name('api.discord.roles');
});
