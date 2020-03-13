<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\FacebookAuth;
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

        /*$body = [
            'client_id' => env('API_CLIENT_ID'),
            'client_secret' => env('API_CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'grant_type' => 'password',
            'scope' => '',
        ];

        $url = url('') . '/oauth/token';

        $client = new Client();

        try {
            $response = $client->post($url, [
                'form_params' => $body,
            ]);
        } catch (\Exception $exception) {
            return response()->json([], 401);
        }

        $data = json_decode($response->getBody()->getContents());*/

        $token = $user->createToken('Laravel Password Grant Client')->accessToken;


        return response()->json([
            'access_token' => $token,
            'scope' => $user->scope,
            'verified' => $user->verified,
        ]);
    }

    public function loginFacebook(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'access_token' => ['required'],
        ]);

        if (!(new FacebookAuth())->checkIfTokenIsValid($request->input('access_token'))) {
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
