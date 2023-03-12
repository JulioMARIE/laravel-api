<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Model\Reunion;
use App\Model\Membre;
use App\Model\Association;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        /* Membre::deleted(function(){
            Reunion::all()->each(function ($reunion) {
                if ($reunion->membres()->count() == 0) {
                    $reunion->delete();
                }
            });
        });
        Association::deleted(function(){
            Reunion::all()->each(function ($reunion) {
                if ($reunion->membres()->count() == 0) {
                    $reunion->delete();
                }
            });
        }); */
        
    }
}
