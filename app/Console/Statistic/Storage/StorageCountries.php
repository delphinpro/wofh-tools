<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

use App\Console\Statistic\Data\Account;
use App\Console\Statistic\Data\Country;
use App\Console\Statistic\StorageProcessor\StorageProcessor;
use Illuminate\Support\Facades\DB;

/**
 * Trait StorageCountries
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\EventProcessor\EventProcessor $eventProcessor
 * @property \App\Models\World world
 */
trait StorageCountries
{
    public function updateTableCountries()
    {
        $time = microtime(true);
        $this->updateCountriesCreated();
        $this->updateCountriesDeleted();
        $this->updateCountries();
        $this->insertCountriesStatistic();
        $this->console->line('    countries: '.round(microtime(true) - $time, 3).'s');
    }

    private function updateCountriesCreated()
    {
        $countries = $this->eventProcessor->getCountriesForInsert()->toArray();
        DB::table('countries')->insert($countries);
    }

    private function updateCountriesDeleted()
    {
        DB::table('countries')
            ->whereIn('id', $this->eventProcessor->getDeletedCountiesIds())
            ->update([
                'diplomacy'  => json_encode(null),
                'pop'        => 0,
                'accounts'   => 0,
                'towns'      => 0,
                'science'    => 0,
                'production' => 0,
                'attack'     => 0,
                'defense'    => 0,
                'active'     => 0,
            ]);
    }

    private function updateCountries()
    {
        $this->eventProcessor->getCountriesForUpdate()->each(function (Country $country) {
            DB::table('countries')
                ->where('id', $country->id)
                ->update($country->toArray());
        });
    }

    /** @noinspection DuplicatedCode */
    private function insertCountriesStatistic()
    {
        $tableName = 'z_'.$this->world->sign.'_countries_data';
        $columns = collect([
            'state_at',
            'id',
            'pop',
            'accounts',
            'towns',
            'science',
            'production',
            'attack',
            'defense',
            'delta_pop',
            'delta_accounts',
            'delta_towns',
            'delta_science',
            'delta_production',
            'delta_attack',
            'delta_defense',
        ])->map(fn($s) => "`$s`")->join(',');

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $queryStringCommon = "INSERT INTO `$tableName` ($columns) VALUES ";

        $countries = $this->eventProcessor->getCountries();
        $first = true;
        $counter = 0;
        $queryStringValues = '';

        foreach ($countries as $country) {
            if (!$first) $queryStringValues .= ',';
            $first = false;

            $queryStringValues .= '('."'{$this->getTime()}'";
            $queryStringValues .= ','.$country->id;
            $queryStringValues .= ','.$country->pop;
            $queryStringValues .= ','.$country->accounts;
            $queryStringValues .= ','.$country->towns;
            $queryStringValues .= ','.$country->science;
            $queryStringValues .= ','.$country->production;
            $queryStringValues .= ','.$country->attack;
            $queryStringValues .= ','.$country->defense;
            $queryStringValues .= ','.$country->getDeltaPop();
            $queryStringValues .= ','.$country->getDeltaAccounts();
            $queryStringValues .= ','.$country->getDeltaTowns();
            $queryStringValues .= ','.$country->getDeltaScience();
            $queryStringValues .= ','.$country->getDeltaProduction();
            $queryStringValues .= ','.$country->getDeltaAttack();
            $queryStringValues .= ','.$country->getDeltaDefence();
            $queryStringValues .= ')';

            if (++$counter >= StorageProcessor::CHUNK) {
                DB::insert($queryStringCommon.$queryStringValues);
                $first = true;
                $counter = 0;
                $queryStringValues = '';
            }
        }

        if ($queryStringValues) DB::insert($queryStringCommon.$queryStringValues);
    }
}
