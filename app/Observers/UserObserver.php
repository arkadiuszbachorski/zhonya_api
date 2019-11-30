<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function deleting(User $user)
    {
        $user->tasks()->delete();
        $user->tags()->delete();
    }
}
