<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Measurement;
use Faker\Generator as Faker;

$factory->define(Measurement::class, function (Faker $faker) {
    return [
        'task_id' => $faker->,
        'start' => $faker->,
        'finish' => $faker->,
    ];
});
