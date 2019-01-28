<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace WofhTools\Core;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface as Logger;
use Slim\Views\Twig;


/**
 * Class BaseController
 * @package WofhTools\Core
 */
class BaseController
{
    /** @var \Slim\App */
    protected $app;

    /** @var Twig */
    protected $view;

    /** @var Logger */
    protected $logger;

    /**
     * BaseController constructor.
     * @param \Slim\Container $dic
     */
    public function __construct(\Slim\Container $dic)
    {
        $this->app = $dic['app'];
        $this->view = $dic['view'];
        $this->logger = $dic['logger'];
    }

    /**
     * @param UriInterface $uri
     * @param array $state
     * @return string
     */
    protected function fetchClientApp(UriInterface $uri, array $state): string
    {

        $serverBundlePath = $this->app->getContainer()->get('settings')->get('serverBundlePath');

        $renderer = new VueRenderer(DIR_ROOT.DIRECTORY_SEPARATOR.'node_modules');
        $ssrHtml = $renderer->render($serverBundlePath, [
            'URL'   => $uri->getPath(),
            'STATE' => $state,
        ]);

        return $this->view->fetch('ssr.twig', [
            'SSR_HTML' => $ssrHtml,
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $state
     * @return Response
     */
    protected function renderClientApp(Request $request, Response $response, array $state = [])
    {
        $body = $this->fetchClientApp($request->getUri(), $state);
        $response->getBody()->write($body);

        return $response;
    }
}