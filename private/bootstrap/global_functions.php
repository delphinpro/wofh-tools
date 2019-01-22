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
 * @return bool|string
 */
function prepareTwigCachePath(string $cachePath, string $rootPath)
{
    return realpath(
        $rootPath
        .DIRECTORY_SEPARATOR
        .trim(
            str_replace('/', DIRECTORY_SEPARATOR, $cachePath),
            DIRECTORY_SEPARATOR
        )
    );
}

/**
 * @param string $configPath
 * @param string $rootPath
 * @return array
 */
function loadConfig(string $configPath, string $rootPath): array
{
    /** @noinspection PhpIncludeInspection */
    $env = require $configPath.DIRECTORY_SEPARATOR.'env.php';
    $configPath = $configPath.DIRECTORY_SEPARATOR.$env['env'];
    /** @noinspection PhpIncludeInspection */
    $config = require $configPath.DIRECTORY_SEPARATOR.'application.php';
    $config['path.root'] = $rootPath;

    return $config;
}
