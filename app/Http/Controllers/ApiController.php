<?php

namespace App\Http\Controllers;

use App\Models\User;
use Faker\Core\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{

    // For use with discord bot.
    public function post_discord_roles(Request $request, User $user)
    {
        $body = $request->all();

        $validator = Validator::make($request->all(), [
            'guild' => 'required',
            'roles' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        $json = json_decode($body['roles'], true);
        $guild = $body['guild'];
        $currentRoles = $user->roles;

        if (!isset($currentRoles[$guild])) {
            $currentRoles[$guild] = [];
        }

        $currentRoles[$guild] = $json;

        $user->roles = $currentRoles;
        $user->save();

        return response()->json([
            'success' => true,
            'guild' => $guild,
            'user' => $user->id,
            'roles' => $user->roles,
        ]);
    }
}
