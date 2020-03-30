<?php

namespace App\Http\Controllers\Auth;

use App\Services\FacebookAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['verified'] = false;

        $user = User::create($data);

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json([
            'access_token' => $token,
            'scope' => $user->scope,
            'verified' => $user->verified,
        ]);
    }

    public function registerFacebook(FacebookAuth $facebookAuth, Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'access_token' => ['required', 'string'],
        ]);

        if (!$facebookAuth->checkIfTokenIsValid($data['access_token'])) {
            abort(403);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            $data['password'] = Str::random();
            $data['verified'] = false;
            $user = User::create($data);
        }

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;

        return response()->json([
            'access_token' => $token,
            'scope' => $user->scope,
            'verified' => $user->verified,
        ]);
    }
}
