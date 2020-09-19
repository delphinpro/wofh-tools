<?php


use Illuminate\Support\Facades\Route;


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
Route::get('/stat/{sign}', 'StatController@world')->name('statWorld');


/*=========================================================*\
 |                                                         |
 |           00            000000            00            |
 |          00            00    00          00             |
 |         00    00      00    0 00        00    00        |
 |        00     00     00    00  00      00     00        |
 |       00      00     00   00   00     00      00        |
 |      00       00     00  00    00    00       00        |
 |     00000000000000    00 0    00    00000000000000      |
 |               00       00    00               00        |
 |               00        000000                00        |
 |                                                         |
\*=========================================================*/


Route::fallback(function () {
    if (request()->isXmlHttpRequest()) {
        return response()->view('errors.api-404', [
            'data' => [
                'status'  => false,
                'message' => 'Requested resource does not exist',
            ],
        ], 404);
    }

    return response()->view('errors.404', [], 404);
});
