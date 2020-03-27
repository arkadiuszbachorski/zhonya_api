<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Tag;
use Faker\Generator as Faker;

$factory->define(Tag::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement([$faker->sentence(3), null]),
        'description' => $faker->randomElement([$faker->text(180), null]),
        'color' => $faker->hexColor,
        'user_id' => 1,
    ];
});
