<?php

namespace App\Policies;

use App\Attempt;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttemptPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can do anything with tag
     *
     * @param  \App\User  $user
     * @param  \App\Attempt  $attempt
     * @return mixed
     */
    public function manage(User $user, Attempt $attempt)
    {
        return $user->id === $attempt->task->user_id;
    }
}
