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
use WofhTools\Controllers\Statistic\AccountsController;


$app->get('/', HomeController::class.':dispatch')->setName('pageHome');

$app->get('/login', UserController::class.':dispatch')->setName('pageLogin');

$app->group('/stat', function () use ($app) {

    $app->get('', AccountsController::class.':dispatch');

});

$app->group('/api', function () use ($app) {

    $app->post('/login', UserController::class.':doLogin');

    $app->group('/dashboard', function () use ($app) {

        $app->get('/worlds', DashboardController::class.':listWorlds');
        $app->post('/check', DashboardController::class.':checkWorlds');

    });

    $app->group('/stat', function () use ($app) {

        $app->get('', AccountsController::class.':dispatch');

    });

    $app->get('/test', WofhController::class.':test');

});

//        self::$app->get('/', function (Request $request, Response $response, $args) {
//
//            $settings = $this->settings;
//
//            return $this->view->render($response, 'ssr.twig', [
//                'name' => $args['name'],
//                'sett' => $settings,
//            ]);
//
//        });

//        self::$app->get('/api/{name}', function (Request $request, Response $response, $args) {
//
//            return $this->view->render($response, 'ssr.twig', [
//                'name' => $args['name'],
//            ]);
//
//        });
