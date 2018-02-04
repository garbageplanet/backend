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

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

  Route::post('/login', 'AuthenticateController@authenticate');
  Route::get('/user', 'AuthenticateController@getAuthenticatedUser');
  Route::post('/register', 'AuthenticateController@postRegister');
  Route::post('/delete', 'AuthenticateController@deauthenticate');
  Route::post('/logout', 'AuthenticateController@getLogout');

});

Route::group(['prefix' => 'trashes', 'namespace' => 'Trashes'], function () {

  // Route::get('TrashesController@index');
  Route::get('withinbounds', 'TrashesController@withinBounds');
  Route::get('{id}', 'TrashesController@show')->where('id', '[0-9]+');
  Route::post('', 'TrashesController@store');
  Route::put('{id}', 'TrashesController@update')->where('id', '[0-9]+');
  Route::put('confirm/{id}', 'TrashesController@confirm')->where('id', '[0-9]+');
  Route::put('clean/{id}', 'TrashesController@clean')->where('id', '[0-9]+');
  Route::delete('{id}', 'TrashesController@destroy')->where('id', '[0-9]+');

});

Route::group(['prefix' => 'cleanings', 'namespace' => 'Cleanings'], function () {

  // Route::get('CleaningsController@index');
  Route::get('withinbounds', 'CleaningsController@withinBounds');
  Route::get('{id}', 'CleaningsController@show')->where('id', '[0-9]+');
  Route::post('', 'CleaningsController@store');
  Route::put('{id}', 'CleaningsController@update')->where('id', '[0-9]+');
  Route::put('attend/{id}', 'CleaningsController@attend');
  Route::delete('{id}', 'CleaningsController@destroy')->where('id', '[0-9]+');

});

Route::group(['prefix' => 'litters', 'namespace' => 'Litters'], function () {

  // Route::get('LittersController@index');
  Route::get('withinbounds', 'LittersController@withinBounds');
  Route::get('{id}', 'LittersController@show')->where('id', '[0-9]+');
  Route::post('', 'LittersController@store');
  Route::put('{id}', 'LittersController@update')->where('id', '[0-9]+');
  Route::put('clean/{id}', 'LittersController@clean')->where('id', '[0-9]+');
  Route::delete('{id}', 'LittersController@destroy')->where('id', '[0-9]+');
  Route::put('confirm/{id}', 'LittersController@confirm')->where('id', '[0-9]+');

});

Route::group(['prefix' => 'areas', 'namespace' => 'Areas'], function () {

  // Route::get('AreasController@index');
  Route::get('withinbounds', 'AreasController@withinBounds');
  Route::get('list/{id}', 'AreasController@indexWithinBounds')->where('id', '[0-9]+');
  Route::get('{id}', 'AreasController@show')->where('id', '[0-9]+');
  Route::post('', 'AreasController@store');
  Route::put('{id}', 'AreasController@update')->where('id', '[0-9]+');
  Route::delete('{id}', 'AreasController@destroy')->where('id', '[0-9]+');

});

Route::group(['prefix' => 'glome', 'namespace' => 'Glome'], function () {

  Route::post('create', 'GlomeController@createSoftAccount');
  Route::get('show/{id}', 'GlomeController@showSoftAccount')->where('id', ':alnum:+');

});

// TODO
// https://laravel.com/docs/5.4/scout#introduction
// https://www.algolia.com/doc/api-client/laravel/algolia-and-scout/
// insert search in each feature route above:
// Route::group(['namespace' => 'Search'], function () {
//
//   Route::get('/search', function (Request $request) {
//
//       return App\FeatureCollection::search($request->search)->get();
//
//     });
//
// });
