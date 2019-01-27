<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use WofhTools\Controllers\NotFoundController;


$dic = $app->getContainer();

/*==
 *== Application
 *== ======================================= ==*/

$dic['app'] = function () use ($app) {
    return $app;
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

    $db = $c->get('settings')->db;

    $dsn = 'mysql:host='.$db['host'].';dbname='.$db['name'];
    $pdo = new \PDO($dsn, $db['user'], $db['pass']);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

    return $pdo;
};


/*==
 *== View
 *== ======================================= ==*/

$dic['view'] = function ($c) {

    $rootPath = $c->settings->get('path.root');
    $debugMode = $c->settings->get('debug');
    $useTwigCache = $c->settings->get('twig.cache');
    $pathTwigCache = prepareTwigCachePath($c->settings->get('twig.cache.path'), $rootPath);

    $twigCache = !$useTwigCache || !$pathTwigCache ? false : $pathTwigCache;

    $view = new \Slim\Views\Twig(DIR_TWIG_TEMPLATES, [
        'debug' => $debugMode,
        'cache' => $twigCache,
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $c->request->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));
    $view->addExtension(new \Twig_Extension_Debug());

    return $view;
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
