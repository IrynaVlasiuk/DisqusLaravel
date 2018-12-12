<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'message' => $faker->sentence,
        'parent_id' => 0,
    ];
});