<?php

namespace WofhTools\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;
use WofhTools\Models\Worlds;
use WofhTools\Core\BaseController;


/**
 * Class WofhController
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Controllers
 */
class WofhController extends BaseController
{
    public function worlds(Request $request, Response $response, $args)
    {
        $worlds = Worlds::getAll();

        $this->push('worlds', $worlds);

        return $this->sendRequest($request, $response);
    }


    public function activeWorlds(Request $request, Response $response, $args)
    {
        $worlds = Worlds::getWorking();

        $this->push('worlds', $worlds);

        return $this->sendRequest($request, $response);
    }
}
