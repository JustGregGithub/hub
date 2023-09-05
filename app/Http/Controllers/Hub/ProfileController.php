<?php

namespace App\Http\Controllers\Hub;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $response = Http::timeout(10)->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://staff.lynus.gg/api/bot/getProfile', [
            'discordId' => $request->user()->id,
            'discordCommunicationKey' => env('DISCORD_COMMUNICATION_KEY'),
        ]);

        return view('hub.profile.edit', [
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

        return Redirect::route('hub.profile.edit')->with('status', 'profile-updated');
    }

    public function post_signature(Request $request) {
        $request->validate([
            'signature' => 'max:150',
        ]);

        if ($request->user()->signature == $request->input('signature')) {
            return redirect()->route('hub.profile.edit')->withErrors( 'You cannot set your signature to the same as your current one!');
        }

        $request->user()->signature = $request->input('signature')?? null;
        $request->user()->save();

        return redirect()->route('hub.profile.edit')->with('success', 'Successfully updated your signature!');
    }
}
