<?php

namespace App\Http\Controllers;

use App\Attempt;
use App\Http\Requests\AttemptRequest;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndependentAttemptController extends Controller
{
    public function index(Request $request)
    {
        $attempts = auth()->user()->attempts()->with('task');

        if ($request->has('withTasks')) {
            $tasks = auth()->user()->tasks()->has('attempts')
                ->get(['tasks.name', 'tasks.id']);
        } else {
            $tasks = null;
        }

        if ($request->has('task')) $attempts->where('task_id', $request->query('task'));
        if ($request->has('search')) $attempts->search($request->query('search'));
        if ($request->has('active')) $attempts->active();

        $attempts = $attempts->orderBy('updated_at', 'desc')
            ->get()
            ->each(function ($attempt) {
                $attempt->append('short_description', 'relative_time', 'active');
                $attempt->addHidden('laravel_through_key', 'description');
                $attempt->task->addHidden('description', 'updated_at');
            });

        return compact('attempts', 'tasks');
    }

    public function create()
    {
        $tasks = auth()->user()->tasks()
            ->get(['tasks.name', 'tasks.id', 'tasks.updated_at']);

        return $tasks;
    }
}
