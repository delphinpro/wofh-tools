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


    public function push($mutation, $data)
    {
        $this->state[$mutation] = $data;
    }


    public function toArray()
    {
        return $this->state;
    }
}
