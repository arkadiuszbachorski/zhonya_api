<?php

use App\Task;
use App\Attempt;
use Illuminate\Database\Seeder;

class AttemptSeeder extends Seeder
{
    protected function generateState()
    {
        $rand = rand(1, 8);

        if ($rand === 1) {
            return 'active';
        } else if ($rand === 2) {
            return 'new';
        }

        return 'nonActive';
    }

    public function run()
    {
        Task::all()->each(function (Task $task) {
            $attempts = collect();
            for ($i = 0; $i < rand(0, 10); $i++) {
                $state = $this->generateState();
                $attempt = factory(Attempt::class)->state($state)->make();
                $attempts->push($attempt);
            }
            $task->attempts()->saveMany($attempts);
        });
    }
}
