<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Attempt;
use Faker\Generator as Faker;

$factory->define(Attempt::class, function (Faker $faker) {
    return [
        'description' => $faker->text(200),
    ];
});
