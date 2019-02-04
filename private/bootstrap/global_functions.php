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
