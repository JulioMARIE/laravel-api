<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/users', 'UserController')->except(['create', 'edit', 'index']);
Route::group(['prefix' => 'users'], function(){
    Route::apiResource('/{user}/associations', 'AssociationController')->except(['create', 'edit']);
    Route::apiResource('/{user}/associations/{association}/membres', 'MembreController')->except(['create', 'edit']);
    Route::apiResource('/{user}/associations/{association}/reunions', 'ReunionController')->except(['create', 'edit', 'store']);
    
});
Route::post('/users/{user}/associations/{association}/membres/{membre}/reunions', 'ReunionController@store')->name('reunions.store');
Route::put('/users/{user}/associations/{association}/membres/{membre}/reunions/{reunion}', 'MembreReunionController@update')->name('membrereunions.update');