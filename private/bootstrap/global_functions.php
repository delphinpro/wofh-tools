<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

/**
 * @param string $cachePath
 * @param string $rootPath
 *
 * @return bool|string
 */
function prepareTwigCachePath(string $cachePath, string $rootPath)
{
    return realpath(
        $rootPath
        .DIRECTORY_SEPARATOR
        .trim(str_replace('/', DIRECTORY_SEPARATOR, $cachePath), DIRECTORY_SEPARATOR)
    );
}


function loadGlobalConfiguration($configDirectory)
{
    if (!is_dir($configDirectory)) {
        echo 'Invalid config directory or not exists';
        die;
    }

    $env = \Dotenv\Dotenv::create($configDirectory);
    $env->load();
    $env->required('ENV_LOCATION')->notEmpty();

    $dotenv = \Dotenv\Dotenv::create($configDirectory, getenv('ENV_LOCATION'));
    $dotenv->overload();

    $dotenv->required('httpVersion')->notEmpty();
    $dotenv->required('responseChunkSize')->isInteger();
    $dotenv->required('outputBuffering')->notEmpty()->allowedValues(['false', 'append', 'prepend']);
    $dotenv->required('determineRouteBeforeAppMiddleware')->isBoolean();
    $dotenv->required('displayErrorDetails')->isBoolean();
    $dotenv->required('addContentLengthHeader')->isBoolean();
    $dotenv->required('routerCacheFile')->isBoolean();

    $_ENV['responseChunkSize'] = (int)$_ENV['responseChunkSize'];
    $_ENV['outputBuffering'] = $_ENV['outputBuffering'] === 'false' ? false
        : $_ENV['outputBuffering'];
    $_ENV['determineRouteBeforeAppMiddleware'] = castStringToBoolean($_ENV['determineRouteBeforeAppMiddleware']);
    $_ENV['displayErrorDetails'] = castStringToBoolean($_ENV['displayErrorDetails']);
    $_ENV['addContentLengthHeader'] = castStringToBoolean($_ENV['addContentLengthHeader']);
    $_ENV['routerCacheFile'] = castStringToBoolean($_ENV['routerCacheFile']);
}


/**
 * @param array $settings
 *
 * @return \Illuminate\Database\Capsule\Manager
 */
function bootEloquent(array $settings): \Illuminate\Database\Capsule\Manager
{
    $capsule = new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($settings);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
}
