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

Route::get('/info', 'InfoController');

Route::resource('/world', 'WorldController')->only(['index']);

Route::get('/stat/{sign}', 'StatWorldController@index');
Route::get('/stat/{sign}/last', 'StatWorldController@last');
