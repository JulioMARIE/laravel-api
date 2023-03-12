<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Association;
use Faker\Generator as Faker;
use App\User;

$factory->define(Association::class, function (Faker $faker) {
    return [
        'denomination' => $faker->word,
        'user_id' => User::all()->random()->id,
    ];
});
