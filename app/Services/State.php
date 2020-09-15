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
}
