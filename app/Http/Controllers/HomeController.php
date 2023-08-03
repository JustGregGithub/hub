<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        return view('home', [
            'pinned_tickets' => Ticket::where('pinned', true)->where('user_id', Auth::user()->id)->get(),
            'applications' => Application::where('user_id', Auth::user()->id)->limit(5)->orderBy('updated_at')->get(),
        ]);
    }
}
