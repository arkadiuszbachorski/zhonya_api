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

        if ($request->has('task')) $attempts->where('task_id', $request->query('task'));
        if ($request->has('search')) $attempts->search($request->query('search'));
        if ($request->has('active')) $attempts->active();

        $attempts = $attempts->get()
            ->hideInEach('laravel_through_key', 'description', 'task')
            ->appendToEach('short_description', 'task_name');

        return $attempts;
    }

    public function create()
    {
        $tasks = auth()->user()->tasks()
            ->get(['tasks.name', 'tasks.id', 'tasks.updated_at']);

        return $tasks;
    }
}
