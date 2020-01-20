<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Tag;
use App\Task;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = auth()->user()->tags();

        if ($request->has('search')) $tags->search($request->query('search'));

        $tags = $tags->withCount('tasks')
            ->orderBy('tasks_count', 'desc')
            ->get()
            ->hideInEach('description')
            ->appendToEach('short_description');

        return $tags;
    }

    public function store(TagRequest $request)
    {
        $tag = auth()->user()->tags()->create($request->validated());

        return $tag->id;
    }

    public function edit(Tag $tag)
    {
        return $tag;
    }

    public function name(Tag $tag)
    {
        return $tag->name;
    }

    public function attachTasks(Tag $tag)
    {
        $tasks = auth()->user()->tasks()->get()->each(function (Task $task) use ($tag) {
            $task->appendHasQueriedTagAttribute($tag->id);
        });

        return $tasks;
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());

        return response()->noContent();
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return response()->noContent();
    }
}
