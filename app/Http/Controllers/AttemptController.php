<?php

namespace App\Http\Controllers;

use App\Attempt;
use App\Http\Requests\AttemptRequest;
use App\Task;

class AttemptController extends Controller
{
    public function index(Task $task)
    {
        $attempts = $task->attempts;

        return $attempts;
    }

    public function store(Task $task, AttemptRequest $request)
    {
        $attempt = $task->attempts()->create($request->validated());

        return $attempt->id;
    }

    public function edit(Task $task, Attempt $attempt)
    {
        $attempt->appendHidden(['task']);
        return $attempt;
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
}
