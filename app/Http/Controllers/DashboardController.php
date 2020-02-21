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
            ->get()
            ->hideInEach('description');

        $tasks = $user->tasks()->latest('updated_at')
            ->limit(self::SCORE_LIMIT)
            ->get()
            ->hideInEach('description');

        $attempts = $user->attempts()
            ->with('task')
            ->latest('updated_at')
            ->active()
            ->limit(self::SCORE_LIMIT)
            ->get()
            ->each(function ($attempt) {
                $attempt->append('short_description', 'relative_time');
                $attempt->addHidden('laravel_through_key', 'description');
                $attempt->task->addHidden('description', 'updated_at');
            });

        return compact("tags", "tasks", "attempts");
    }
}
