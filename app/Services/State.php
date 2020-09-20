<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */


namespace App\Services;


class State
{
    private $state = [];
    private $wt = [];

    public function push(string $compositeKey, $data)
    {
        [$module, $key] = explode('.', $compositeKey, 2);
        if (!array_key_exists($module, $this->state)) $this->state[$module] = [];
        $this->state[$module][$key] = $data;
    }

    public function toArray()
    {
        return $this->state;
    }

    public function pushWt(string $compositeKey, $data)
    {
        if (strpos($compositeKey, '.') !== false) {
            [$module, $key] = explode('.', $compositeKey, 2);
            if (!array_key_exists($module, $this->wt)) $this->wt[$module] = [];
            $this->wt[$module][$key] = $data;
        } else {
            $this->wt[$compositeKey] = $data;
        }
    }

    public function getWt()
    {
        return $this->wt;
    }
}
