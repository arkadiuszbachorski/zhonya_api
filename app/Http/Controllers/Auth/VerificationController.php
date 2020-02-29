<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\VerifyUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function send()
    {
        $user = Auth::user();

        $user->generateVerificationToken();

        $user->notify(new VerifyUser());
    }

    public function verify(Request $request)
    {
        $request->validate([
            'verification_token' => 'string|required'
        ]);

        $user = Auth::user();

        if ($user->verification_token !== $request->verification_token) {
            throw new AuthorizationException;
        }

        $user->verify();
    }
}
