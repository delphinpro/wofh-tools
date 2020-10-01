<?php


use Illuminate\Support\Facades\Route;


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

Route::get('/info', 'Api\InfoController');

Route::resource('/world', 'Api\WorldController')->only(['index']);

Route::get('/stat/{sign}', 'Api\StatWorldController@index');
Route::get('/stat/{sign}/last', 'Api\StatWorldController@last');
