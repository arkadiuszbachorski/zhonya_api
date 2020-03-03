<?php

namespace App\Http\Controllers;

use App\Notifications\DeleteUser;
use App\Notifications\VerifyUser;
use App\Rules\MatchesUserPassword;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => ['required', new MatchesUserPassword($user)],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($request->input('new_password'));

        $user->save();

        return response()->noContent();
    }

    public function updateEmail(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        $user = Auth::user();
        $user->fill($data);
        $user->generateVerificationToken();
        $user->save();

        $user->notify(new VerifyUser());

        return response()->noContent();
    }

    public function sendDelete()
    {
        $user = Auth::user();

        $user->generateDeleteToken();
        $user->save();

        $user->notify(new DeleteUser());

        return response()->noContent();
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'delete_token' => 'required',
        ]);

        $user = Auth::user();

        if (Carbon::now()->diffInMinutes($user->delete_token_generated_at) > 5 || $request->delete_token !== $user->delete_token) {
            throw new AuthenticationException;
        }

        $user->delete();

        return response()->noContent();
    }
}
