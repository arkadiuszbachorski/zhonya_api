<?php

namespace App\Observers;

use App\Attempt;
use Illuminate\Support\Facades\Log;

class AttemptObserver
{
    public function creating(Attempt $attempt)
    {
        if (!$attempt->description) {
            $attempt->generateDescription(1);
        }
    }

    public function updating(Attempt $attempt)
    {
        if (!$attempt->description) {
            $attempt->generateDescription();
        }
    }
}
