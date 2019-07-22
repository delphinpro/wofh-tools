<?php

namespace WofhTools\Core;


use Psr\Http\Message\UriInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use WofhTools\Helpers\JsonCustomException;


/**
 * Class BaseController
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Core
 *
 * @property \Illuminate\Database\Capsule\Manager db
 * @property \Slim\App                            app
 * @property \Slim\Views\Twig                     view
 * @property \WofhTools\Core\AppSettings          config
 * @property \WofhTools\Helpers\Json              json
 */
class BaseController
{
    /** @var \Slim\Container */
    protected $DIContainer;


    /**
     * BaseController constructor.
     *
     * @param \Slim\Container $DIContainer
     */
    public function __construct(\Slim\Container $DIContainer)
    {
        $this->DIContainer = $DIContainer;
    }


    /**
     * @param string $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $id)
    {
        if ($this->DIContainer->has($id)) {
            return $this->DIContainer[$id];
        }

        throw new \Exception('Invalid DI container key: '.$id);
    }


    protected function bootEloquent()
    {
        $this->app->getContainer()->get('db');
    }


    /**
     * @param UriInterface $uri
     * @param array        $state
     *
     * @return string
     */
    protected function fetchClientApp(UriInterface $uri, array $state): string
    {
        $ssrHtml = '';
        $stateAsString = "{}; /* Default */";

        if ($this->config->ssrEnabled) {

            try {
                $stateAsString = $this->json->encode($state, false, true);
            } catch (JsonCustomException $e) {
                $stateAsString = "{}; /* {$e->getMessage()} */";
            }

            $renderer = new VueRenderer(DIR_ROOT.DIRECTORY_SEPARATOR.'node_modules');
            $ssrHtml = $renderer->render($this->config->ssrBundle, [
                'URL'   => $uri->getPath(),
                'STATE' => $stateAsString,
            ]);
        }

        return $this->view->fetch('ssr.twig', [
            'SSR_HTML' => $ssrHtml,
            'STATE'    => $stateAsString, // todo не нужно
        ]);
    }


    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $state
     * @param bool     $status
     * @param string   $message
     *
     * @return Response
     */
    protected function sendRequest(
        Request $request,
        Response $response,
        array $state = [],
        bool $status = true,
        string $message = ''
    ): Response {
        if ($request->isXhr()) {
            $response = $response->withJson([
                'status'  => $status,
                'message' => $message,
                'payload' => $state,
            ]);
        } else {
            $body = $this->fetchClientApp($request->getUri(), $state);
            $response->write($body);
        }


        return $response;
    }
}
