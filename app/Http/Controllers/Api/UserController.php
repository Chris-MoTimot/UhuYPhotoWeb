<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(User $user)
    {
        return response()->json($user->load('pins', 'boards', 'followers', 'following'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string',
            'username' => 'string|unique:users,username,' . auth()->id(),
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|url',
        ]);

        auth()->user()->update($validated);
        return response()->json(auth()->user());
    }

    public function follow(User $user)
    {
        auth()->user()->following()->toggle($user->id);
        return response()->json(['following' => auth()->user()->following()->where('users.id', $user->id)->exists()]);
    }
}
