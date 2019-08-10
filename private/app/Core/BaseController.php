<?php

namespace WofhTools\Core;


use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Http\Message\UriInterface;
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
 * @property \WofhTools\Tools\Wofh                wofh
 */
class BaseController
{
    /** @var \Slim\Container */
    protected $DIContainer;

    /** @var array */
    protected $states;


    /**
     * BaseController constructor.
     *
     * @param \Slim\Container $DIContainer
     */
    public function __construct(\Slim\Container $DIContainer)
    {
        $this->DIContainer = $DIContainer;
        $this->bootEloquent();

        $this->states = [
            'default' => [],
        ];
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


    protected function push(string $key, $data, string $stateModule = 'default'): void
    {
        if (!array_key_exists($stateModule, $this->states)) {
            $this->states[$stateModule] = [];
        }

        $this->states[$stateModule][$key] = $data;
    }


    /**
     * @param UriInterface $uri
     * @param array        $state
     *
     * @return string
     * @throws \V8JsScriptException
     */
    protected function fetchClientApp(UriInterface $uri, array $state): string
    {
        $ssrHtml = '';
        $stateAsString = "{}; /* Default */";

        if ($this->config->ssrEnabled) {

            try {
                $stateAsString = $this->json->encode($state, false, false);
            } catch (JsonCustomException $e) {
                $stateAsString = "{}; /* {$e->getMessage()} */";
            }

            $renderer = new VueRenderer(DIR_ROOT.DIRECTORY_SEPARATOR.'node_modules');
            $ssrHtml = $renderer->render($this->config->ssrBundle, [
                'URL'   => $uri->getPath(),
                'STATE' => $state,
            ]);
        }

        return $this->view->fetch('ssr.twig', [
            'SSR_HTML' => $ssrHtml,
            'STATE'    => $stateAsString, // todo не нужно
        ]);
    }


    /**
     * Send request
     *
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param Request  $request
     * @param Response $response
     * @param bool     $status
     * @param string   $message
     *
     * @return Response
     */
    protected function sendRequest(
        Request $request,
        Response $response,
        bool $status = true,
        string $message = ''
    ): Response {
        if ($request->isXhr()) {
            $response = $response->withJson([
                'status'  => $status,
                'message' => $message,
                'payload' => $this->states['default'],
            ]);
        } else {
            /** @noinspection PhpUnhandledExceptionInspection */
            $body = $this->fetchClientApp($request->getUri(), $this->states);
            $response->write($body);
        }


        return $response;
    }
}
