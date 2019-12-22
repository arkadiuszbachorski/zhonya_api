<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Task;

class TagTaskController extends Controller
{
    public function attach(Tag $tag, Task $task)
    {
        $task->tags()->attach($tag);

        return response()->noContent();
    }

    public function detach(Tag $tag, Task $task)
    {
        $task->tags()->detach($tag);

        return response()->noContent();
    }

}
