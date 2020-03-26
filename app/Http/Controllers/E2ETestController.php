<?php

namespace App\Http\Controllers;

use App\Attempt;
use App\Task;
use App\User;
use Illuminate\Support\Facades\Hash;

class E2ETestController extends Controller
{
    public function constructor()
    {
        if (config('e2e.allow') !== true || request()->get('token') !== config('e2e.token')) {
            abort(403);
        }
    }

    public function create()
    {
        $preparation = request()->input('preparation');
        $password = config('e2e.password');
        $email = config('e2e.email');
        $data = [];

        if ($user = User::where('email', $email)->first()) {
            $user->delete();
        }

        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
            'verified' => true,
        ]);

        if ($preparation === "createTask") {
            $task = factory(Task::class)->make();
            $user->tasks()->save($task);
            $data['task'] = $task;
        } else if ($preparation === "prepareSearch") {
            $task = $user->tasks()->create([
                'name' => 'Lorem',
            ]);
            Attempt::create([
                'task_id' => $task->id,
                'description' => 'Lorem',
            ]);
            $user->tags()->create([
                'color' => '000000',
                'name' => 'Lorem',
                'description' => 'Lorem',
            ]);
        }

        return compact('password', 'email', 'data');
    }

    public function wipe()
    {
        if($user = User::where('email', config('e2e.email'))->first()) {
            $user->delete();
        }

        return response()->noContent();
    }
}
