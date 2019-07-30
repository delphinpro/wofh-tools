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
 * @property string  publicHtmlDir
 * @property boolean ssrEnabled
 * @property string  ssrBundle
 * @property boolean twigCacheEnabled
 * @property string  twigCachePath
 * @property array   db
 * @property string  statisticPath
 * @property int     statLoadInterval
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

            'statisticPath'    => 'data/statistic',
            'statLoadInterval' => 6,

            'db' => [],
        ];

        $this->data = array_merge($defaultSettings, $data);
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
