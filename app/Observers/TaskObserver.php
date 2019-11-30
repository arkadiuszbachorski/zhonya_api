<?php

namespace App\Observers;

use App\Task;

class TaskObserver
{
    /**
     * Handle the tag "deleting" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleting(Task $task)
    {
//        todo: Delete everything that is assigned to Task
        $task->tags()->detach();
    }
}
