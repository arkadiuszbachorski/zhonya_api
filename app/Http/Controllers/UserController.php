<?php

namespace App\Http\Controllers;

use App\Rules\MatchesUserPassword;
use App\Rules\Test;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'oldPassword' => ['required', new MatchesUserPassword($user)],
            'newPassword' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'password' => Hash::make($request->input('newPassword')),
        ];

        $user->update($data);

        return response()->noContent();
    }

    public function updateEmail(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $user = auth()->user();

        $user->update($data);

        return response()->noContent();
    }

    public function destroy()
    {
        $user = auth()->user();

        $user->delete();

        return response()->noContent();
    }
}
