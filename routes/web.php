<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Hub\ApplicationController;
use App\Http\Controllers\Hub\HomeController;
use App\Http\Controllers\Hub\ProfileController;
use App\Http\Controllers\Hub\SupportController;
use App\Http\Controllers\Hub\TicketsController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\Staff\ServerController;
use App\Http\Controllers\Staff\SettingsController;
use App\Http\Controllers\Staff\StaffController;
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

/*
|--------------------------------------------------------------------------
| Index Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/home', 302);
Route::get('/store', function () { return Redirect::to('https://store.rockfordrp.com'); })->name('store');

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
*/

//Route::middleware('auth')->group(function () {
//    Route::middleware('staff')->group(function () {
//        Route::get('/staff',                                           [HomeController::class, 'staff_home'])->name('staff.home');
//
//        Route::get('/staff/server-settings',                           [SettingsController::class, 'servers'])->name('staff.settings.servers');
//        Route::get('/staff/server-settings/{server:id}',               [SettingsController::class, 'server'])->name('staff.settings.server');
//        Route::patch('/staff/server-settings/{server:id}',             [SettingsController::class, 'patch_server'])->name('staff.settings.server.patch');
//        Route::delete('/staff/server-settings/{server:id}',            [SettingsController::class, 'delete_server'])->name('staff.settings.server.delete');
//
//        Route::get('/staff/{server:id}',                               [ServerController::class, 'server'])->name('staff.server');
//        Route::delete('/staff/{server:id}',                            [ServerController::class, 'delete_server'])->name('staff.server.delete');
//        Route::post('/staff/create',                                   [ServerController::class, 'post_server'])->name('staff.server.post');
//
//        Route::get('/staff/{server:id}/search',                        [ServerController::class, 'search'])->name('staff.server.search');
//        Route::get('/staff/{server:id}/players',                       [ServerController::class, 'players'])->name('staff.server.players');
//        Route::get('/staff/{server:id}/players/{player:license}',      [ServerController::class, 'player'])->name('staff.server.player');
//        Route::post('/staff/{server:id}/players/{player:license}',     [ServerController::class, 'post_player'])->name('staff.server.player.post');
//        Route::get('/staff/{server:id}/chats',                         [ServerController::class, 'chats'])->name('staff.server.chats');
//        Route::get('/staff/{server:id}/deaths',                        [ServerController::class, 'deaths'])->name('staff.server.deaths');
//        Route::get('/staff/{server:id}/reports',                       [ServerController::class, 'reports'])->name('staff.server.reports');
//
//        Route::get('/staff/{server:id}/timeclock',                     [StaffController::class, 'timeclock'])->name('staff.server.timeclock');
//
//        Route::get('/staff/{server:id}/permissions',                   [StaffController::class, 'permissions'])->name('staff.permissions');
//        Route::post('/staff/{server:id}/permissions',                  [StaffController::class, 'post_permissions'])->name('staff.permissions.post');
//        Route::delete('/staff/{server:id}/permissions',                [StaffController::class, 'delete_permissions'])->name('staff.permissions.delete');
//        Route::get('/staff/{server:id}/permissions/{role:id}',         [StaffController::class, 'permission'])->name('staff.permission');
//        Route::patch('/staff/{server:id}/permissions/{role:id}',       [StaffController::class, 'patch_permission'])->name('staff.permission.patch');
//    });
//});

/*
|--------------------------------------------------------------------------
| Hub Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/home',                                            [HomeController::class, 'hub_home'])                ->name('home');

    Route::prefix('/tickets')->group(function () {
        Route::get('/',                                            [TicketsController::class, 'index'])             ->name('tickets.home');
        Route::get('/all/{page}',                                  [TicketsController::class, 'all'])               ->name('tickets.all');
        Route::get('/create',                                      [TicketsController::class, 'create'])            ->name('tickets.create');

        Route::middleware('throttle:5,60')->group(function () {
            Route::post('/create',                                     [TicketsController::class, 'post_create'])       ->name('tickets.create.post');
        });

        Route::post('/reply/{ticket:slug}',                        [TicketsController::class, 'post_reply'])        ->name('tickets.reply.post');
        Route::post('/update/{ticket:slug}',                       [TicketsController::class, 'post_update'])       ->name('tickets.update.post');
        Route::patch('/user/{ticket:slug}',                        [TicketsController::class, 'patch_user'])        ->name('tickets.user.patch');
        Route::delete('/user/{ticket:slug}',                       [TicketsController::class, 'delete_user'])       ->name('tickets.user.delete');
        Route::patch('/pin/{ticket:slug}',                         [TicketsController::class, 'patch_pin'])         ->name('tickets.pin.patch');

        Route::get('/{ticket:slug}',                               [TicketsController::class, 'view'])              ->name('tickets.view');
   });

    Route::prefix('/applications')->group(function () {
        Route::get('/',                                            [ApplicationController::class, 'index'])         ->name('applications.home');

        Route::get('/view/{application:id}',                       [ApplicationController::class, 'view'])          ->name('applications.view');
        Route::patch('/status/{application:id}',                   [ApplicationController::class, 'patch_status'])  ->name('applications.status.patch');

        Route::get('/{application_categories:id}',                 [ApplicationController::class, 'apply'])         ->name('applications.apply');
        Route::post('/{application_categories:id}',                [ApplicationController::class, 'post_apply'])    ->name('applications.apply.post');
    });

    /**
        Settings
     */
    Route::prefix('/hub-settings')->group(function () {
        Route::get('/',                                            [HubController::class, 'index'])              ->name('hub.settings');
        Route::patch('/announcement',                              [HubController::class, 'patch_announcement']) ->name('hub.settings.announcement.patch');
    });

    Route::prefix('/ticket-settings')->group(function () {
        Route::get('/',                                            [TicketsController::class, 'settings'])          ->name('tickets.settings');
        Route::patch('/category',                                  [TicketsController::class, 'patch_category'])    ->name('tickets.category.patch');
        Route::delete('/category',                                 [TicketsController::class, 'delete_category'])   ->name('tickets.category.delete');
    });

    Route::prefix('/application-settings')->group(function () {
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

    /**
     * Support Routes
     */
    Route::get('/ticket-support',                                  [SupportController::class, 'ticket_support'])           ->name('tickets.support');
    Route::get('/ticket-support/{ticket_category:id}',             [SupportController::class, 'ticket_support_category'])   ->name('tickets.support_tickets');

    Route::get('/application-support',                             [SupportController::class, 'application_support'])           ->name('application.support');
    Route::get('/application-support/{application_categories:id}', [SupportController::class, 'application_support_category'])   ->name('application.support_applications');

    /**
        Profile
    */
    Route::get('/profile',                                         [ProfileController::class, 'edit'])              ->name('hub.profile.edit');
    Route::post('/profile/signature',                              [ProfileController::class, 'post_signature'])    ->name('profile.signature.post');
});

/*
    Global Api area
*/
Route::middleware('ApiAuth')->prefix('/api')->group(function () {
    Route::post('/discord/roles/{user:id}',                        [ApiController::class, 'post_discord_roles'])   ->name('api.discord.roles');
});

Route::prefix('/api')->group(function () {
    Route::get('quote',                                            [ApiController::class, 'get_quote'])           ->name('api.quote');
});

/*
    External Staff Api area
*/

Route::prefix('/api/server/{server:id}')->middleware('server')->group(function () {
    Route::get('/',                                     [ApiController::class, 'server'])           ->name('staff.api.server');
    Route::get('/player/{type}/{player}',               [ApiController::class, 'player'])           ->name('staff.api.player');
    Route::patch('/player/{player:license}',            [ApiController::class, 'patch_player'])      ->name('staff.api.player.patch');
    Route::post('/player',                              [ApiController::class, 'post_player'])      ->name('staff.api.player.post');

    Route::post('/report/{player:license}',             [ApiController::class, 'post_report'])      ->name('staff.api.report.post');
    Route::post('/chat/{player:license}',               [ApiController::class, 'post_chat'])      ->name('staff.api.chat.post');
    Route::post('/death/{player:license}',              [ApiController::class, 'post_death'])      ->name('staff.api.death.post');
    Route::post('/punish/{player:license}',             [ApiController::class, 'post_punish'])      ->name('staff.api.punish.post');
    Route::post('/duty/{player:license}',               [ApiController::class, 'post_duty'])      ->name('staff.api.duty.post');
});
