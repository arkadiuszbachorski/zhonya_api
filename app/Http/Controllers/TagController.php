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
        $tags = auth()->user()->tags()->withCount('tasks');

        if ($request->has('search')) $tags->search($request->query('search'));

        $tags = $tags->get();

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

    public function attachTasks(Tag $tag)
    {
        $tags = auth()->user()->tasks()->get()->each(function (Task $task) use ($tag) {
            $task->appendHasQueriedTagAttribute($tag->id);
        });

        return $tags;
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
