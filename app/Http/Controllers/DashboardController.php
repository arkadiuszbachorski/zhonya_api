<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    const SCORE_LIMIT = 5;

    public function dashboard(Request $request)
    {
        $user = $request->user();

        $tags = $user->tags()->withCount('tasks')
            ->orderBy('tasks_count', 'DESC')
            ->limit(self::SCORE_LIMIT)
            ->get();

        $tasks = $user->tasks()->latest('updated_at')
            ->limit(self::SCORE_LIMIT)
            ->get();

        $attempts = $user->attempts()
            ->latest('updated_at')
            ->active()
            ->limit(self::SCORE_LIMIT)
            ->get();

        return compact("tags", "tasks", "attempts");
    }
}
