<?php

use App\User;
use App\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class, 3)->create();
        $users->each(function(User $user) {
            $user->tags()->saveMany(
                factory(Tag::class, 3)->make()
            );
        });
    }
}
