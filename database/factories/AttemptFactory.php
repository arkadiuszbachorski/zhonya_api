<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Attempt;
use Faker\Generator as Faker;

$factory->define(Attempt::class, function (Faker $faker) {
    return [
        'description' => $faker->text(200),
    ];
});

$factory->state(Attempt::class, 'active', function (Faker $faker) {
    return [
        'saved_relative_time' => $faker->numberBetween(0, 3600),
        'started_at' => now()->subSeconds($faker->numberBetween(0, 60 * 60 * 24))
    ];
});

$factory->state(Attempt::class, 'nonActive', function (Faker $faker) {
    return [
        'saved_relative_time' => $faker->numberBetween(0, 3600),
        'started_at' => null,
    ];
});

$factory->state(Attempt::class, 'new', function (Faker $faker) {
    return [
        'saved_relative_time' => 0,
        'started_at' => null,
    ];
});
