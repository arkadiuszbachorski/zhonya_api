<?php

use App\Task;
use Illuminate\Database\Seeder;
use App\User;

class TaskSeeder extends Seeder
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
            $user->tasks()->createMany(
                factory(Task::class, 5)->make()
            );
        });
    }
}
