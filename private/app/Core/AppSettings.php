<?php

namespace WofhTools\Core;


/**
 * Class AppSettings
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Core
 *
 * @property boolean debug
 * @property string  rootPath
 * @property boolean ssrEnabled
 * @property string  ssrBundle
 * @property boolean twigCacheEnabled
 * @property string  twigCachePath
 * @property array   db
 */
class AppSettings
{
    /** @var array */
    private $data;


    public function __construct(array $data)
    {
        $defaultSettings = [
            'httpVersion'                       => '1.1',
            'responseChunkSize'                 => 4096,
            'outputBuffering'                   => 'append',
            'determineRouteBeforeAppMiddleware' => false,
            'displayErrorDetails'               => false,
            'addContentLengthHeader'            => true,
            'routerCacheFile'                   => false,

            'debug' => false,

            'ssrEnabled' => true,
            'ssrBundle'  => 'static/js/server.js',

            'twigCacheEnabled' => true,
            'twigCachePath'    => '.cache/twig',

            'db' => [],
        ];

        $this->data = array_merge($defaultSettings, $data);
    }


    /**
     * @param string $configPath
     * @param string $rootPath
     *
     * @return AppSettings
     */
    public static function loadConfig(string $configPath, string $rootPath): AppSettings
    {
        /** @noinspection PhpIncludeInspection */
        $env = include $configPath.DIRECTORY_SEPARATOR.'env.php';
        $configPath = $configPath.DIRECTORY_SEPARATOR.$env['env'];
        /** @noinspection PhpIncludeInspection */
        $config = include $configPath.DIRECTORY_SEPARATOR.'application.php';
        $config['rootPath'] = $rootPath;

        return new AppSettings($config);
    }


    public function getSlimSettings(): array
    {
        $slimSettings = [
            'httpVersion'                       => '1.1',
            'responseChunkSize'                 => 4096,
            'outputBuffering'                   => 'append',
            'determineRouteBeforeAppMiddleware' => false,
            'displayErrorDetails'               => false,
            'addContentLengthHeader'            => true,
            'routerCacheFile'                   => false,
        ];

        foreach ($this->data as $key => $option) {
            if (array_key_exists($key, $slimSettings)) {
                $slimSettings[$key] = $option;
            }
        }

        return $slimSettings;
    }


    public function __get($key)
    {
        return (array_key_exists($key, $this->data)) ? $this->data[$key] : null;
    }
}
