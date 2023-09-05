<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Models\Hub\Application;
use App\Models\Hub\Ticket;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function hub_home()
    {
        return view('hub.home', [
            'pinned_tickets' => Ticket::where('pinned', true)->where('user_id', Auth::user()->id)->get(),
            'applications' => Application::where('user_id', Auth::user()->id)->limit(5)->orderBy('updated_at')->get(),
        ]);
    }

    public function staff_home() {
        return view('staff.home');
    }
}
