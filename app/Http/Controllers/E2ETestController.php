<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Hash;

class E2ETestController extends Controller
{
    public function constructor()
    {
        if (config('e2e.allow') !== true || request()->get('token') !== config('e2e.token')) {
            abort(403);
        }
    }

    public function create()
    {
        $password = config('e2e.password');
        $email = config('e2e.email');

        User::firstOrCreate([
            'email' => $email,
        ], [
            'password' => Hash::make($password),
            'verified' => true,
        ]);

        return compact('password', 'email');
    }

    public function wipe()
    {
        $user = User::where('email', config('e2e.email'))->first();

        if($user) {
            $user->delete();
        }

        return response()->noContent();
    }
}
