<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


use Slim\Http\Request;
use Slim\Http\Response;
use WofhTools\Controllers\NotFoundController;
use WofhTools\Core\AppSettings;
use WofhTools\Tools\Wofh;


$dic = $app->getContainer();

/*==
 *== Application
 *== ======================================= ==*/

$dic['app'] = function () use ($app) {
    return $app;
};

/*==
 *== Application config
 *== ======================================= ==*/

$dic['config'] = function () {
    $ssrBundle = DIR_ROOT
        .DIRECTORY_SEPARATOR.trim(env('publicHtmlDir'), '\\/')
        .DIRECTORY_SEPARATOR.trim(env('ssrBundle'), '\\/');

    return new AppSettings([
        'httpVersion'                       => env('httpVersion'),
        'responseChunkSize'                 => env('responseChunkSize'),
        'outputBuffering'                   => env('outputBuffering'),
        'determineRouteBeforeAppMiddleware' => env('determineRouteBeforeAppMiddleware'),
        'displayErrorDetails'               => env('displayErrorDetails'),
        'addContentLengthHeader'            => env('addContentLengthHeader'),
        'routerCacheFile'                   => env('routerCacheFile'),

        'debug' => env('debug'),

        'ssrEnabled' => env('ssrEnabled'),
        'ssrBundle'  => $ssrBundle,

        'twigCacheEnabled' => env('twigCacheEnabled'),
        'twigCachePath'    => env('twigCachePath'),

        'statisticPath'    => env('statisticPath'),
        'statLoadInterval' => env('statLoadInterval'),

        'rootPath'      => DIR_ROOT,
        'publicHtmlDir' => env('publicHtmlDir'),

        'db' => [
            'driver'    => env('DB_DRIVER'),
            'host'      => env('DB_HOST'),
            'database'  => env('DB_DATABASE'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD'),
            'charset'   => env('DB_CHARSET'),
            'collation' => env('DB_COLLATION'),
            'prefix'    => env('DB_PREFIX'),
        ],
    ]);
};

/*==
 *== Logger
 *== ======================================= ==*/

$dic['logger'] = function () {

    $logger = new \Monolog\Logger('app');
    $fileHandler = new \Monolog\Handler\StreamHandler(DIR_LOGS.DIRECTORY_SEPARATOR.'app.log');
    $logger->pushHandler($fileHandler);

    return $logger;
};

$dic['json'] = function () {
    return new \WofhTools\Helpers\Json();
};

$dic['http'] = function () {
    return new \WofhTools\Helpers\Http();
};


/*==
 *== Database
 *== ======================================= ==*/

$dic['db'] = function (\Slim\Container $c) {

    $c->get('logger')->info('Database init');

    /**  @var AppSettings $config */
    $config = $c->get('config');

    $capsule = bootEloquent($config->db);

    return $capsule;
};


/*==
 *== View
 *== ======================================= ==*/

$dic['view'] = function (\Slim\Container $c) {

    /**  @var AppSettings $config */
    $config = $c->get('config');

    /** @var \Slim\Http\Uri $uri */
    $uri = $c->get('request')->getUri();

    $pathTwigCache = prepareTwigCachePath($config->twigCachePath, $config->rootPath);
    $twigCache = !$config->twigCacheEnabled || !$pathTwigCache ? false : $pathTwigCache;

    $view = new \Slim\Views\Twig(
        DIR_TWIG_TEMPLATES,
        [
            'debug' => $config->debug,
            'cache' => $twigCache,
        ]
    );

    $basePath = rtrim($uri->getBasePath(), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c->get('router'), $basePath));
    $view->addExtension(new \Twig_Extension_Debug());

    return $view;
};


/*==
 *== Wofh
 *== ======================================= ==*/

$dic['wofh'] = function (\Slim\Container $c) {
    return new Wofh(
        $c->get('http'),
        $c->get('json')
    );
};

/*==
 *== Not found handler
 *== ======================================= ==*/

$dic['notFoundHandler'] = function (\Slim\Container $c) {
    return function (Request $request, Response $response) use ($c) {
        return (new NotFoundController($c))->dispatch($request, $response);
    };
};


/*==
 *== Error handler
 *== ======================================= ==*/

//$dic['errorHandler'] = function (\Slim\Container $c) {
//    return function ($request, $response, \Exception $exception) use ($c) {
//        $data = [
//            'code'    => $exception->getCode(),
//            'message' => $exception->getMessage(),
//            'file'    => $exception->getFile(),
//            'line'    => $exception->getLine(),
//            'trace'   => explode("\n", $exception->getTraceAsString()),
//        ];
//
//        return $c->get('response')->withStatus(500)
//            ->withHeader('Content-Type', 'application/json')
//            ->write(json_encode($data));
//    };
//};
