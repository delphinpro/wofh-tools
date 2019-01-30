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
use WofhTools\Helpers\Http;
use WofhTools\Helpers\Json;
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

$dic['config'] = function () use ($config) {
    return $config;
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


/*==
 *== Database
 *== ======================================= ==*/

$dic['db'] = function (\Slim\Container $c) {

    $c->get('logger')->info('Database init');

    /**  @var AppSettings $config */
    $config = $c->get('config');

    $capsule = new \Illuminate\Database\Capsule\Manager();
    $capsule->addConnection($config->db);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

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
        new Http(),
        new Json()
    );
};

/*==
 *== Not found handler
 *== ======================================= ==*/

$dic['notFoundHandler'] = function (\Slim\Container $c) {
    return function (ServerRequestInterface $request, ResponseInterface $response) use ($c) {
        $body = (new NotFoundController($c))->dispatch($request, $response);

        /** @noinspection PhpUndefinedMethodInspection */
        return $response
            ->withStatus(404, 'Page not found')
            ->withHeader('Content-Type', 'text/html')
            ->write($body);
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
