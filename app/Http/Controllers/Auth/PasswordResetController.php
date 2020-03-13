<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetUser;
use Illuminate\Http\Request;
use App\User;

class PasswordResetController extends Controller
{

    public function reset(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->input('email'))->firstOrFail();
        $user->tokens()->get()->each(function ($token) {
            $token->revoke();
        });
        $notHashedPassword = $user->resetPassword();
        $user->save();

        $user->notify(new PasswordResetUser($notHashedPassword));

    }
}
