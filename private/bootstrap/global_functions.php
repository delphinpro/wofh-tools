<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

const CAST_TO_INT = 1;
const CAST_TO_FLOAT = 2;
const CAST_TO_BOOL = 3;

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


/**
 * @param string $var
 * @param int    $castTo
 *
 * @return mixed
 */
function castVar(string $var, int $castTo)
{
    $filters = [
        CAST_TO_INT   => FILTER_VALIDATE_INT,
        CAST_TO_FLOAT => FILTER_VALIDATE_FLOAT,
        CAST_TO_BOOL  => FILTER_VALIDATE_BOOLEAN,
    ];

    $filter = array_key_exists($castTo, $filters) ? $filters[$castTo] : FILTER_DEFAULT;

    return filter_var($var, $filter);
}


function loadGlobalConfiguration(string $configDirectory): void
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

    $_ENV['responseChunkSize'] = castVar($_ENV['responseChunkSize'], CAST_TO_INT);
    $_ENV['outputBuffering'] = $_ENV['outputBuffering'] === 'false' ? false : $_ENV['outputBuffering'];
    $_ENV['determineRouteBeforeAppMiddleware'] = castVar($_ENV['determineRouteBeforeAppMiddleware'], CAST_TO_BOOL);
    $_ENV['displayErrorDetails'] = castVar($_ENV['displayErrorDetails'], CAST_TO_BOOL);
    $_ENV['addContentLengthHeader'] = castVar($_ENV['addContentLengthHeader'], CAST_TO_BOOL);
    $_ENV['routerCacheFile'] = castVar($_ENV['routerCacheFile'], CAST_TO_BOOL);
    $_ENV['DEBUG'] = castVar($_ENV['DEBUG'], CAST_TO_BOOL);
    $_ENV['ssrEnabled'] = castVar($_ENV['ssrEnabled'], CAST_TO_BOOL);
    $_ENV['twigCacheEnabled'] = castVar($_ENV['twigCacheEnabled'], CAST_TO_BOOL);
    $_ENV['statLoadInterval'] = castVar($_ENV['statLoadInterval'], CAST_TO_INT);
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
