<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
            'email_verified_at' => $user->email_verified_at,
        ]);
    }
}
