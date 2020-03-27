<?php

use App\Task;
use App\User;
use Illuminate\Database\Seeder;

class TagTaskSeeder extends Seeder
{
    protected function shouldAttachTag()
    {
        return rand(1, 4) === 4;
    }

    public function run()
    {
        User::all()->each(function (User $user) {
            $tags = $user->tags;

            $user->tasks()->each(function (Task $task) use ($tags) {
                if ($this->shouldAttachTag()) {
                    $task->tags()->attach($tags->random());
                }
            });
        });
    }
}
