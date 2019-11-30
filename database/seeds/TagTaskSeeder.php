<?php

use App\Task;
use App\User;
use Illuminate\Database\Seeder;

class TagTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $users->each(function (User $user) {
            $tags = $user->tags;

            $user->tasks()->each(function (Task $task) use ($tags) {
                if (rand(1, 4) === 4) {
                    $tag = $tags->random();
                    $task->tags()->attach($tag);
                }
            });
        });
    }
}
