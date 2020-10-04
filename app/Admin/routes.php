<?php


use App\Admin\Controllers\SettingsController;
use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router;


Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as'         => config('admin.route.prefix').'.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('dashboard');

    $router->resource('worlds', 'WorldController')->names('worlds');
    $router->resource('stat-logs', 'StatLogController')->names('stat-logs');
    $router->get('settings', 'SettingsController@settings');

});
