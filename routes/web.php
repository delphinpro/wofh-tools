<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'register' => false,
]);

Route::get('/', 'IndexController@show')->name('home');
Route::get('/stat', 'StatController@index')->name('stat');

Route::group(['prefix' => 'api'], function () {
    Route::get('/worlds', 'ApiController@worlds');
});

Route::group(['prefix' => 'admin'], function () {
    /** @noinspection PhpUndefinedMethodInspection */
    Voyager::routes();
});
