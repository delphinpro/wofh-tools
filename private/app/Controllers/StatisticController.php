<?php

namespace WofhTools\Controllers;


use Slim\Http\Request;
use Slim\Http\Response;
use WofhTools\Models\Statistic;
use WofhTools\Core\BaseController;


/**
 * Class StatisticController
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Controllers
 */
final class StatisticController extends BaseController
{
    public function dispatch(Request $request, Response $response, $args)
    {
        return $this->sendRequest($request, $response);
    }


    public function common(Request $request, Response $response, $args)
    {
        $this->push('args', $args);
//        $worldId = $this->wofh->signToId();
        $statistic = new Statistic($this->DIContainer);
        $this->push('commonStat', $statistic->getCommonStat($args['sign']));
        $this->push('countries', $statistic->getCountriesList($args['sign']));

        return $this->sendRequest($request, $response);
    }
}
