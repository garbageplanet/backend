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
    
    //trashes
    
    Route::get('trashes', 'TrashesController@index');
    Route::get('trashes/withinbounds', 'TrashesController@withinBounds');
    Route::get('trashes/{id}', 'TrashesController@show');

    Route::post('trashes', 'TrashesController@store');
    Route::put('trashes', 'TrashesController@update');
    Route::delete('trashes/{id}', 'TrashesController@destroy');
    Route::post('userlesstrash', 'TrashesController@storeWithoutUser');
  
    //monitoring tiles
    Route::get('monitoringtiles', 'MonitoringTilesController@listByUser');
    Route::post('monitoringtiles', 'MonitoringTilesController@store');
    Route::delete('monitoringtiles/{id}', 'MonitoringTilesController@destroy');
    Route::get('monitoringtiles/{id}', 'MonitoringTilesController@trashesInTile');
    
});
Route::get('/welcome', function () {
    return view('welcome');
});

