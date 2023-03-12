<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Model\Association;
use App\Model\Membre;
use App\Model\Reunion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Association::truncate();
        Membre::truncate();
        Reunion::truncate();
        DB::table('membre_reunion')->truncate();

        factory(User::class, 10)->create();
        factory(Association::class, 30)->create();
        factory(Membre::class, 100)->create();
        
        factory(Reunion::class, 50)->create()->each(
            function($reunion){
                
                $membres = Membre::all()->random(mt_rand(1, 100))->pluck('id');
                $membres_reunions = [];
                $faker = new Faker;
                foreach ($membres as $membre) {  
                    $pres = Arr::random([true, false]);
                    $membres_reunions += [$membre => ['present' => $pres,
                    'perm' => $pres ? false : Arr::random([true, false]),
                    'mtcot' => $faker->numberBetween(0, 10000000),
                  ],];
                }
                $reunion->membres()->sync($membres_reunions);
            }
        );
        

    }
}
