<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 *
 * @var \Slim\App $app
 */


use WofhTools\Controllers\HomeController;
use WofhTools\Controllers\UserController;
use WofhTools\Controllers\WofhController;
use WofhTools\Controllers\DashboardController;


$app->get('/', HomeController::class.':dispatch');

$app->get('/login', UserController::class.':dispatch');


$app->group('/stat', function () use ($app) {
    $app->get('', AccountsController::class.':dispatch');
});


//==
//== API interface
//== ======================================= ==//

$app->group('/api', function () use ($app) {

    //==
    //== User area
    //== ======================================= ==//

    $app->post('/login', UserController::class.':doLogin');
    $app->group('/user', function () use ($app) {

        //==
        //== Private user area
        //== ======================================= ==//

        $app->group('/profile', function () use ($app) {
            $app->get('', UserController::class.':profile');
            $app->post('/save', UserController::class.':doSave');
        });
    });


    //==
    //== Admin area
    //== ======================================= ==//

    $app->group('/dashboard', function () use ($app) {
        $app->group('/worlds', function () use ($app) {
            $app->get('', DashboardController::class.':listWorlds');
            $app->post('/check', DashboardController::class.':checkWorlds');
        });
    });

    $app->group('/wofh', function () use ($app) {
        $app->get('/worlds', WofhController::class.':worlds');
        $app->get('/worlds/active', WofhController::class.':activeWorlds');
    });

    $app->group('/stat', function () use ($app) {
        $app->get('', AccountsController::class.':dispatch');
    });

    $app->get('/test', DashboardController::class.':test');

});
