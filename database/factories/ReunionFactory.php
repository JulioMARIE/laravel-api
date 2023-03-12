<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Reunion;
use App\Model\Association;
use Faker\Generator as Faker;

$factory->define(Reunion::class, function (Faker $faker) {
    return [
        'datereunion' => $faker->date,
        'mtcot' => $faker->numberBetween(50, 10000000),
        'association_id' => Association::all()->random()->id,
    ];
});
