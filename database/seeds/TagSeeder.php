<?php

use App\Tag;
use App\User;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $users->each(function(User $user) {
            $user->tags()->saveMany(
                factory(Tag::class, 3)->make()
            );
        });
    }
}
