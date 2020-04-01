<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\VerifyUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDataController extends Controller
{
    public function data()
    {
        $user = Auth::user();

        return [
            'verified' => $user->verified,
            'scope' => $user->scope,
        ];
    }
}
