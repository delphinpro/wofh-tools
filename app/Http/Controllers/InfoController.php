<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Http\Controllers;


use App\Exceptions\JsonServiceException;
use App\Services\Json;


class InfoController extends Controller
{
    public function __invoke(Json $json)
    {
        $pkg = $this->getPackageJson($json);
        return response()->json([
            'project'    => [
                'name'      => $pkg['productName'],
                'version'   => $pkg['version'],
                'updatedAt' => $this->getLastUpdateTime(),
            ],
        ]);
    }

    private function getPackageJson(Json $json)
    {
        $file = base_path('package.json');
        if (file_exists($file)) {
            try {
                return $json->decode(file_get_contents($file));
            } catch (JsonServiceException $e) {
            }
        }
        return null;
    }

    private function getLastUpdateTime()
    {
        $file = base_path('.git/logs/HEAD');
        if (file_exists($file)) return filemtime($file);
        return false;
    }
}
