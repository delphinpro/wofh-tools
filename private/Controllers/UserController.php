<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace WofhTools\Controllers;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use WofhTools\App\BaseController;


/**
 * Class UserController
 * @package WofhTools\Controllers
 */
final class UserController extends BaseController
{
    public function dispatch(Request $request, Response $response, $args)
    {
        $this->logger->info("Login page action dispatched");

        return $this->renderClientApp($request, $response);
    }
}
