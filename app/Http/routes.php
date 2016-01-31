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

    // Shapes (polylines and polygons)
    Route::get('shapes', 'ShapesController@listByUser');
    Route::get('Shapes/withinbounds', 'ShapesController@withinBounds');
    Route::get('Shapes/{id}', 'ShapesController@show');
    
    Route::post('shapes', 'ShapesController@store');
    Route::put('shapes', 'ShapesController@update');
    Route::delete('shapes/{id}', 'ShapesController@destroy');
    
    // Garbage inside an area
    Route::get('shapes/{id}', 'ShapesController@trashesInTile');
    
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
