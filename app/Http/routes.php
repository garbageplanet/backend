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
    Route::get('trashes/withinbounds', 'TrashesController@withinBounds');
    Route::get('trashes/{id}', 'TrashesController@show');
    Route::get('trashes', 'TrashesController@index');
    Route::post('trashes', ['middleware' => 'jwt.auth', 'uses' => 'TrashesController@store']);
    Route::put('trashes/{id}', ['middleware' => 'jwt.auth', 'uses' => 'TrashesController@update']);
    Route::delete('trashes/{id}', ['middleware' => 'jwt.auth', 'uses' => 'TrashesController@destroy']);

    
});
Route::get('/welcome', function () {
    return view('welcome');
});
