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
    Route::post('userlesstrash', 'TrashesController@storeWithoutUser'); // this needs to be removed

    // Litter (polylines)
    Route::get('litters', 'LittersController@listByUser');
    Route::get('litters/withinbounds', 'LittersController@withinBounds');
    Route::get('Shapes/{id}', 'LittersController@show');
    
    Route::post('litters', 'LittersController@store');
    Route::put('litters', 'LittersController@update');
    Route::delete('litters/{id}', 'LittersController@destroy');
    
    // Areas (polygons)
    Route::get('areas', 'AreasController@listByUser');
    Route::get('areasareas/withinbounds', 'AreasController@withinBounds');
    Route::get('areasareas/{id}', 'AreasController@show');
    
    Route::post('areas', 'AreasController@store');
    Route::put('areas', 'AreasController@update');
    Route::delete('areas/{id}', 'AreasController@destroy');
    
    // Features inside an area
    Route::get('shapes/{id}', 'ShapesController@trashesInArea');
    Route::get('shapes/{id}', 'ShapesController@littersInArea');
    Route::get('shapes/{id}', 'ShapesController@cleaningsInArea');
    
    // Cleanings
    Route::get('cleaning', 'CleaningController@index');
    Route::get('cleaning/withinbounds', 'CleaningController@withinBounds');
    Route::get('cleaning/{id}', 'CleaningController@show');

    Route::post('cleaning', 'CleaningController@store');
    Route::put('cleaning', 'CleaningController@update');
    Route::delete('cleaning/{id}', 'CleaningController@destroy');

});
Route::get('/welcome', function () {
    return view('welcome');
});
