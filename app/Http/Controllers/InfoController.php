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
use App\Settings;


class InfoController extends Controller
{
    public function __invoke(Json $json, Settings $settings)
    {
        $pkg = $this->getPackageJson($json);
        return response()->json([
            'project'    => [
                'name'      => $pkg['productName'],
                'version'   => $pkg['version'],
                'updatedAt' => $this->getLastUpdateTime(),
            ],
            'yaCounter'  => $this->getYandexCounterParams($settings),
            'yaInformer' => $this->getYandexInformerParams($settings),
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

    private function getYandexCounterParams(Settings $settings)
    {
        $id = $settings->find('yaCounterId');
        $src = $settings->find('yaCounterSrc');

        return [
            'id'  => $id ? $id->value : null,
            'src' => $src ? $src->value : null,
        ];
    }

    /**
     * @param \App\Settings $settings
     * @return array
     */
    private function getYandexInformerParams(Settings $settings): array
    {
        $link = $settings->find('yaInformerLink');
        $img = $settings->find('yaInformerImg');
        return [
            // 'link' => $link ? $link->value : '',
            'img'  => $img ? $img->value : '',
        ];
    }

}
