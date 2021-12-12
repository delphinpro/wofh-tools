<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\DataStorage;

use App\Console\Statistic\Data\Account;
use App\Console\Statistic\Data\Country;
use App\Console\Statistic\Data\Town;

/**
 * Trait Normalizer
 *
 * @package App\Console\Traits
 * @property $towns
 * @property $accounts
 * @property $countries
 */
trait Normalizer
{
    private function collectTowns()
    {
        $this->towns = collect($this->raw['towns'])->map(function ($town, $id) {
            return new Town($id, $town);
        });
        // Убрать города с нулевым населением
        // и варварские (аккаунт = 0)
        // if ($this->isTownNullPopulation($town) or $this->isTownBarbarian($town)) {
        //     continue;
        // }
    }

    private function collectAccounts()
    {
        $this->accounts = collect($this->raw['accounts'])->map(function ($account, $id) {
            return new Account($id, $account);
        });
    }

    private function collectCountries()
    {
        $this->countries = collect($this->raw['countries'])->map(function ($country, $id) {
            return new Country($id, $country);
        });
    }
}
