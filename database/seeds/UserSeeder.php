<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected function generateTestUser()
    {
        User::firstOrCreate([
            'email' => 'test@test.com',
        ], [
            'password' => Hash::make('test1234'),
            'verified' => true,
        ]);
    }

    public function run()
    {
        $this->generateTestUser();


        factory(User::class, 5)->create();
    }
}
