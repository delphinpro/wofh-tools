<?php

namespace WofhTools\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;
use WofhTools\Core\BaseController;


/**
 * Class HomeController
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Controllers
 */
final class HomeController extends BaseController
{

    public function dispatch(Request $request, Response $response, $args)
    {
        return $this->renderApp($request, $response);
    }
}
