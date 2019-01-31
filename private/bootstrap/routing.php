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


$app->get('/', HomeController::class.':dispatch')->setName('pageHome');

$app->get('/login', UserController::class.':dispatch')->setName('pageLogin');

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
