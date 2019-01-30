<?php

namespace WofhTools\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;
use WofhTools\Core\BaseController;


/**
 * Class UserController
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Controllers
 */
final class UserController extends BaseController
{
    public function dispatch(Request $request, Response $response, $args)
    {
        return $this->renderApp($request, $response);
    }
}
