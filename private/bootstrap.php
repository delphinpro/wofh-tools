<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


if (version_compare(PHP_VERSION, '7.2') < 0) {
    header('Content-Type: text/html; charset=utf-8');
    die(sprintf('Need version PHP 7.2 or higher. Your version: %s', PHP_VERSION));
}

require '../vendor/autoload.php';
require_once __DIR__.'/bootstrap/constants.php';
require_once __DIR__.'/bootstrap/global_functions.php';

/*==
 *== Session
 *== ======================================= ==*/

session_start();


/*==
 *== Load configuration
 *== ======================================= ==*/

loadGlobalConfiguration(realpath('../config'));

if (env('DEBUG')) {
    require_once __DIR__.'/lib/PHP_Exceptionizer/Exceptionizer.php';
    $exceptionizer = new PHP_Exceptionizer();
}


/*==
 *== Init application
 *== ======================================= ==*/

$app = new \Slim\App([
    'settings' => [
        'httpVersion'                       => env('httpVersion') ?: '1.1',
        'responseChunkSize'                 => env('responseChunkSize') ?: 4096,
        'outputBuffering'                   => env('outputBuffering') ?: 'append',
        'determineRouteBeforeAppMiddleware' => env('determineRouteBeforeAppMiddleware') ?: false,
        'displayErrorDetails'               => env('displayErrorDetails') ?: false,
        'addContentLengthHeader'            => env('addContentLengthHeader') ?: true,
        'routerCacheFile'                   => env('routerCacheFile') ?: false,
    ],
]);

$app->add(new \Tuupola\Middleware\JwtAuthentication([
    'secure' => false,
    'secret' => env('JWT_SECRET_KEY'),
    'path'   => [
        '/api/dashboard',
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

/*==
 *== Inject dependencies
 *== ======================================= ==*/

require __DIR__.'/bootstrap/dependencies.php';
require __DIR__.'/bootstrap/dep_controllers.php';


/*==
 *== Register middleware
 *== ======================================= ==*/

require __DIR__.'/bootstrap/middleware.php';


/*==
 *== Routing
 *== ======================================= ==*/

require __DIR__.'/bootstrap/routing.php';


/*==
 *== Start app
 *== ======================================= ==*/

try {

    $app->run();

} catch (Exception $e) {

    echo $e->getMessage();

}
