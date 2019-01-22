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
 * Class HomeController
 * @package WofhTools\Controllers
 */
final class HomeController extends BaseController
{

    public function dispatch(Request $request, Response $response, $args)
    {
        $this->logger->info("Home page action dispatched");

        return $this->renderClientApp($request, $response);
    }
}
