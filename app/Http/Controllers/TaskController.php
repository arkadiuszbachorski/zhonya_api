<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Tag;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = auth()->user()->tasks();

        if ($request->has('search')) $tasks->search($request->query('search'));
        if ($request->has('active')) $tasks->active();
        if ($request->has('tag')) $tasks->withTag($request->query('tag'));

        if ($request->has('withTags')) {
            $tags = auth()->user()->tags;
        } else {
            $tags = null;
        }

        $tasks = $tasks->orderBy('updated_at', 'desc')
            ->with('attempts')
            ->get()
            ->appendToEach('attempts_statistics', 'tags_colors', 'short_description', 'active')
            ->hideInEach('description');

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
        return $task;
    }

    public function name(Task $task)
    {
        return $task->name;
    }

    public function attachTags(Task $task)
    {
        $tags = auth()->user()->tags()->get()->each(function (Tag $tag) use ($task) {
            $tag->appendHasQueriedTaskAttribute($task->id);
        });

        return $tags;
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
