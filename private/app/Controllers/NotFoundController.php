<?php

namespace WofhTools\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;
use WofhTools\Core\BaseController;


/**
 * Class NotFoundController
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Controllers
 */
final class NotFoundController extends BaseController
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return string
     */
    public function dispatch(Request $request, Response $response)
    {
        return $this->fetchClientApp($request->getUri(), []);
    }
}
