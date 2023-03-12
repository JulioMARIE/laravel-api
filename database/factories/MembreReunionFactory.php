<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\MembreReunion;
use Faker\Generator as Faker;
use App\Model\Membre;
use App\Model\Reunion;

$factory->define(MembreReunion::class, function (Faker $faker) {
    return [
        'present' => $pres = $faker->randomElement([true, false]),
        'perm' => $pres ? false : $faker->randomElement([true, false]),
        'mtcot' => $faker->numberBetween(0, 10000000),
    ];
});
