<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\DataStorage;


/**
 * Trait Assertion
 *
 * @package App\Console\Statistic\DataStorage
 * @property \Carbon\Carbon                 time
 * @property \Illuminate\Support\Collection towns
 * @property \Illuminate\Support\Collection accounts
 * @property \Illuminate\Support\Collection countries
 */
trait Assertion
{
    public function hasData() { return !is_null($this->time); }

    public function hasTown(int $id) { return $this->towns->has($id); }

    public function hasAccount(int $id) { return $this->accounts->has($id); }

    public function hasCountry(int $id) { return $this->countries->has($id); }

    public function hasCountries() { return $this->countries->count() > 0; }
}
