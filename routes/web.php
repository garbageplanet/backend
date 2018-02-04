<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| Make sure to run 'artisan config:clear' and 'artisan optimize' if changing the values
|
*/

Route::group(['prefix' => '' . env('APP_ADMIN_PATH', 'admin') ], function () {
    Voyager::routes();
});
