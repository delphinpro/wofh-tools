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

$config = loadConfig(DIR_CONFIG, DIR_ROOT);


/*==
 *== Init application
 *== ======================================= ==*/

$app = new \Slim\App([
    'settings' => $config,
]);


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

} catch (\Exception $e) {

    echo $e->getMessage();

}
