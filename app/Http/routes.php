<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function() {
    //Auth (login, authenticate, register)
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/user', 'AuthenticateController@getAuthenticatedUser');
    Route::post('register', 'AuthenticateController@postRegister');

    //glome
    Route::post('glome/create', 'GlomeController@createSoftAccount');
    Route::get('glome/show/{id}', 'GlomeController@showSoftAccount');

    //trashes
    Route::get('trashes', 'TrashesController@index');
    Route::get('trashes/withinbounds', 'TrashesController@withinBounds');
    Route::get('trashes/{id}', 'TrashesController@show');

    Route::post('trashes', 'TrashesController@store');
    Route::put('trashes', 'TrashesController@update');
    Route::delete('trashes/{id}', 'TrashesController@destroy');

    // Litters (polylines)
    Route::get('litters', 'LittersController@index');
    Route::get('litters/withinbounds', 'LittersController@withinBounds');
    Route::get('litters/{id}', 'LittersController@show');
    
    Route::post('litters', 'LittersController@store');
    Route::put('litters', 'LittersController@update');
    Route::delete('litters/{id}', 'LittersController@destroy');
    
    // Areas (polygons)
    Route::get('areas', 'AreasController@index');
    Route::get('areasareas/withinbounds', 'AreasController@withinBounds');
    Route::get('areasareas/{id}', 'AreasController@show');
    
    Route::post('areas', 'AreasController@store');
    Route::put('areas', 'AreasController@update');
    Route::delete('areas/{id}', 'AreasController@destroy');
    
    // Features inside an area
    Route::get('areas/{id}', 'ShapesController@trashesInArea');
    Route::get('areas/{id}', 'ShapesController@littersInArea');
    Route::get('areas/{id}', 'ShapesController@cleaningsInArea');
    
    // Cleanings aka meetings
    Route::get('cleanings', 'CleaningsController@index');
    Route::get('cleanings/withinbounds', 'CleaningsController@withinBounds');
    Route::get('cleanings/{id}', 'CleaningsController@show');

    Route::post('cleanings', 'CleaningsController@store');
    Route::put('cleanings', 'CleaningsController@update');
    Route::delete('cleanings/{id}', 'CleaningsController@destroy');

});
Route::get('/welcome', function () {
    return view('welcome');
});
