<?php


use App\Http\Controllers\InfoController;
use App\Http\Controllers\StatWorldController;
use App\Http\Controllers\WorldController;
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

Route::get('/info', InfoController::class);

Route::resource('/world', WorldController::class)->only(['index']);

Route::get('/stat/{sign}', [StatWorldController::class, 'index']);
Route::get('/stat/{sign}/last', [StatWorldController::class, 'last']);
