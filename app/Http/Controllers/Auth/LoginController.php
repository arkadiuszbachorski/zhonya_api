<?php

namespace App\Http\Controllers\Auth;

use App\Services\FacebookAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'errors' => [
                    'email' => [trans('auth.failed')],
                ]
            ], 401);
        }

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;


        return response()->json([
            'access_token' => $token,
            'scope' => $user->scope,
            'verified' => $user->verified,
        ]);
    }

    public function loginFacebook(FacebookAuth $facebookAuth, Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'access_token' => ['required'],
        ]);

        if (!$facebookAuth->checkIfTokenIsValid($request->input('access_token'))) {
            abort(403);
        }

        $user = User::where('email', $request->input('email'))->firstOrFail();

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;


        return response()->json([
            'access_token' => $token,
            'scope' => $user->scope,
            'verified' => $user->verified,
        ]);
    }
}
