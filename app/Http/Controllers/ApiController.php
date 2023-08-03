<?php

namespace App\Http\Controllers;

use App\Models\User;
use Faker\Core\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{

    // For use with discord bot.
    public function post_discord_roles(Request $request, User $user)
    {
        $body = $request->getContent();
        $json = json_decode($body, true);
        $guild = env("DISCORD_GUILD_ID");

        // override all rode data and only save for configured guild.
        $user->update([
            'roles' => [$guild => $json]
        ]);

        // reply some info thats actually useful.
        return response()->json([
            'success' => true,
            'guild' => $guild,
            'user' => $user->id,
            'roles' => $user->roles,
        ]);
    }
}
