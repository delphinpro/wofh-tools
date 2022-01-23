<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

use App\Console\Statistic\Data\Account;
use App\Console\Statistic\Data\Country;
use App\Console\Statistic\Data\Town;

/**
 * Trait Normalizer
 * @property \Illuminate\Support\Collection|Town[] $towns
 * @property \Illuminate\Support\Collection|Account[] $accounts
 * @property \Illuminate\Support\Collection|Country[] $countries
 */
trait Normalizer
{
    public function collectData()
    {
        $this->collectTowns();
        $this->collectAccounts();
        $this->collectCountries();
    }

    private function collectTowns()
    {
        $this->towns = collect($this->raw['towns'])
            ->map(fn($town, $id) => new Town($id, $town, $this->time))
            // Убрать города с нулевым населением
            ->filter(fn(Town $town) => $town->pop > 0);
        // и варварские (аккаунт = 0)
        // if ($this->isTownNullPopulation($town) or $this->isTownBarbarian($town)) {
        //     continue;
        // }
    }

    private function collectAccounts()
    {
        $this->accounts = collect($this->raw['accounts'])->map(fn($account, $id) => new Account($id, $account));
    }

    private function collectCountries()
    {
        $this->countries = collect($this->raw['countries'])->map(fn($country, $id) => new Country($id, $country));
    }
}
