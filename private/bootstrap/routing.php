<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


use WofhTools\Controllers\HomeController;
use WofhTools\Controllers\UserController;
use WofhTools\Controllers\DashboardController;
use WofhTools\Controllers\WofhController;


$app->get('/', HomeController::class.':dispatch');

$app->get('/login', UserController::class.':dispatch');

$app->group('/stat', function () use ($app) {

    $app->get('', AccountsController::class.':dispatch');

});

$app->group('/api', function () use ($app) {

    $app->post('/login', UserController::class.':doLogin');

    $app->group('/user', function () use ($app) {

        $app->get('/profile', UserController::class.':profile');
        $app->post('/profile/save', UserController::class.':doSave');

    });

    $app->group('/dashboard', function () use ($app) {

        $app->get('/worlds', DashboardController::class.':listWorlds');
        $app->post('/check', DashboardController::class.':checkWorlds');

    });

    $app->group('/wofh', function () use ($app) {

        $app->get('/worlds', WofhController::class.':worlds');

    });

    $app->group('/stat', function () use ($app) {

        $app->get('', AccountsController::class.':dispatch');

    });

    $app->get('/test', DashboardController::class.':test');

});
