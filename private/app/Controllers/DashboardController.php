<?php

namespace WofhTools\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;
use WofhTools\Tools\Wofh;
use WofhTools\Models\Worlds;
use WofhTools\Core\BaseController;
use WofhTools\Tools\WofhException;


/**
 * Class WofhController
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Controllers
 */
final class DashboardController extends BaseController
{
    public function listWorlds(Request $request, Response $response)
    {
        $token = $request->getAttribute("token");
        $this->bootEloquent();
        $worlds = Worlds::getAll();

//        return $response->withStatus(401, 'Unauthorized');
        return $this->sendRequest($request, $response, [
            'worlds' => $worlds,
            'token'  => $token,
        ]);
    }


    public function checkWorlds(Request $request, Response $response)
    {
        $message = 'The status of worlds has been updated successfully';
        $checkStatus = true;

        try {

            /** @var Wofh $wofh */
            $wofh = $this->app->getContainer()->get('wofh');
            $this->bootEloquent();

            $links = $wofh->getAllStatusLinks();
            $wofh->check($links);

        } catch (WofhException $e) {

            $checkStatus = false;
            $message = 'Error updating the status of game worlds: '.$e->getMessage();

        }

        $worlds = Worlds::getAll();

        return $this->sendRequest($request, $response, [
            'worlds' => $worlds,
        ], $checkStatus, $message);
    }


    public function test(Request $request, Response $response)
    {
        /** @var Wofh $wofh */
        $wofh = $this->app->getContainer()->get('wofh');
        $this->bootEloquent();

        ob_start();

        $links = $wofh->getAllStatusLinks();
        $wofh->check($links);


        return ob_get_clean();
    }
}
