<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = auth()->user()->tasks();

        if ($request->has('search')) $tasks->search($request->query('search'));
        if ($request->has('active')) $tasks->active();
        if ($request->has('tag')) $tasks->tag($request->query('tag'));

        if ($request->has('withTags')) {
            $tags = auth()->user()->tags;
        } else {
            $tags = null;
        }

        $tasks = $tasks->with('tags')->get();

        return [
            'tasks' => $tasks,
            'tags' => $tags,
        ];
    }

    public function store(TaskRequest $request)
    {
        $task = auth()->user()->tasks()->create($request->validated());

        return $task->id;
    }

    public function edit(Task $task)
    {
        $tags = auth()->user()->tags()->withoutTask($task->id)->get();
        $task->load('tags');

        return [
            'tags' => $tags,
            'task' => $task,
        ];
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return response()->noContent();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }
}
