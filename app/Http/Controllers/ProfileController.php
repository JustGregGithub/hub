<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://staff.lynus.gg/api/bot/getProfile', [
            'discordId' => $request->user()->id,
            'discordCommunicationKey' => env('DISCORD_COMMUNICATION_KEY'),
        ]);

        return view('profile.edit', [
            'user' => $request->user(),
            'record' => $response->json(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function post_signature(Request $request) {
        $request->validate([
            'signature' => 'max:150',
        ]);

        if ($request->user()->signature == $request->input('signature')) {
            return redirect()->route('profile.edit')->withErrors( 'You cannot set your signature to the same as your current one!');
        }

        $request->user()->signature = $request->input('signature')?? null;
        $request->user()->save();

        return redirect()->route('profile.edit')->with('success', 'Successfully updated your signature!');
    }
}
