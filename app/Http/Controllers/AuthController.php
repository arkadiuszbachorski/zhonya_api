<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([], 401);
        }

        $body = [
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

        $data = json_decode($response->getBody()->getContents());


        return response()->json([
            'access_token' => $data->access_token,
            'scope' => $user->scope,
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return response()->noContent(201);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->noContent();
    }

}
