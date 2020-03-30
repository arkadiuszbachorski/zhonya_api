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
        $this->call([
            UserSeeder::class,
            TaskSeeder::class,
            AttemptSeeder::class,
            TagSeeder::class,
            TagTaskSeeder::class,
        ]);
    }
}
