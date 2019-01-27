<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace WofhTools\Controllers;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use WofhTools\App\BaseController;


/**
 * Class NotFoundController
 * @package WofhTools\Controllers
 */
final class NotFoundController extends BaseController
{
    /**
     * @param Request $request
     * @param Response $response
     * @return string
     */
    public function dispatch(Request $request, Response $response)
    {
        $this->logger->info("Page not found action dispatched");

        return $this->fetchClientApp($request->getUri(), []);
    }
}
