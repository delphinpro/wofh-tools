<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

$app->add(new \Tuupola\Middleware\JwtAuthentication([
    'secure' => false,
    'secret' => env('JWT_SECRET_KEY'),
    'path'   => [
        '/api/dashboard',
        '/api/profile',
    ],
    'error'  => function (\Slim\Http\Response $response, $arguments) {
        return $response
            ->withHeader("Content-Type", "application/json")
            ->withJson([
                'status'  => false,
                'message' => $arguments['message'],
                'payload' => [],
            ]);
    },
]));
