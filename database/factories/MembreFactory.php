<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Membre;
use Faker\Generator as Faker;
use App\Model\Association;

$factory->define(Membre::class, function (Faker $faker) {
    return [
        'nom' => $faker->name,
        'prenom' => $faker->name,
        'addresse' => $faker->word,
        'contact' => $faker->randomNumber(8),
        'mdp' => bcrypt($faker->word),
        'datenaissance' => $faker->date,
        'photoprofil' => $faker->word,
        'profession' => $faker->word,
        'situationmatri' => $faker->word,
        'association_id' => Association::all()->random()->id,
    ];
});
