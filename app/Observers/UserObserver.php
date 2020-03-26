<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function deleting(User $user)
    {
        $user->tokens()->delete();
        $user->tasks()->get()->each->delete();
        $user->tags()->delete();
    }
}
