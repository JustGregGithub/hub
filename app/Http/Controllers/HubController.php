<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;

class HubController extends Controller
{
    public function index()
    {
        return view('hub.index', [
            // get the first row from the configurations table
            'announcement' => Configuration::first()
        ]);
    }

    public function patch_announcement() {

    }
}
