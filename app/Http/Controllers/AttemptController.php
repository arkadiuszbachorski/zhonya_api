<?php

namespace App\Http\Controllers;

use App\Attempt;
use App\Http\Requests\AttemptRequest;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttemptController extends Controller
{
    public function index(Task $task, Request $request)
    {
        $attempts = $task->attempts();

        if ($request->has('search')) $attempts->search($request->query('search'));
        if ($request->has('active')) $attempts->active();

        $attempts = $attempts->get()->appendToEach('active', 'relative_time');

        return $attempts;
    }

    public function store(Task $task, AttemptRequest $request)
    {
        $attempt = $task->attempts()->create($request->validated());

        return $attempt->id;
    }

    public function edit(Task $task, Attempt $attempt)
    {
        return $attempt;
    }

    public function name(Task $task, Attempt $attempt)
    {
        return Str::limit($attempt->description, 30);
    }

    public function update(Task $task, Attempt $attempt, AttemptRequest $request)
    {
        $attempt->update($request->validated());

        return response()->noContent();
    }

    public function destroy(Task $task, Attempt $attempt)
    {
        $attempt->delete();

        return response()->noContent();
    }

    public function measurementSave(Task $task, Attempt $attempt, Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'relative_time' => 'nullable|integer',
        ]);

        if ($attempt->active && $request->has('relative_time')) {
            $attempt->saved_relative_time = $request->get('relative_time');
            $attempt->started_at = null;
        } else if ($attempt->active)  {
            $attempt->saved_relative_time = Carbon::parse($request->get('date'))->diffInSeconds($attempt->started_at);
            $attempt->started_at = null;
        } else {
            $attempt->started_at = Carbon::parse($request->get('date'));
        }

        $attempt->save();

        return response()->noContent();
    }

    public function measurement(Task $task, Attempt $attempt)
    {
        $attempt->append(['active', 'relative_time']);

        return $attempt->only(['id', 'active', 'relative_time', 'started_at']);
    }
}
