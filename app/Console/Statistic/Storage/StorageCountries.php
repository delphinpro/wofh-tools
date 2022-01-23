<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

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
        $this->insertCountries();
        $this->updateCountriesDeleted();
        $this->updateCountriesData();
        $this->insertCountriesStatistic();
        $this->console->line('    countries: '.round(microtime(true) - $time, 3).'s');
    }

    private function insertCountries()
    {
        if (empty($this->eventProcessor->insertCountryIds)) return;

        $columns = [
            'id',
            'name',
            'flag',
            'active',
            'props',
        ];

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = 'INSERT';
        $sql .= ' INTO `z_'.$this->world->sign.'_countries`';
        $sql .= ' (`'.join('`,`', $columns).'`)';
        $sql .= ' VALUES ';

        $pdo = DB::getPdo();
        $first = true;

        foreach ($this->eventProcessor->insertCountryIds as $id) {
            $country = $this->getCountry($id);
            if (!$first) $sql .= ','; else $first = false;

            $json = "JSON_OBJECT(
              'flags', JSON_ARRAY(JSON_OBJECT('{$this->time->timestamp}', '$country->flag')),
              'names', JSON_ARRAY(JSON_OBJECT('{$this->time->timestamp}', '$country->name'))
            )";

            $sql .= '(';
            $sql .= (intval($id));
            $sql .= ','.($pdo->quote($country->name));
            $sql .= ','.($pdo->quote($country->flag));
            $sql .= ','.(intval(1));
            $sql .= ','.$json;
            $sql .= ')';
        }

        DB::insert($sql);
    }

    private function updateCountriesDeleted()
    {
        if (empty($this->eventProcessor->deleteCountryIds)) return;
        // UPDATE TABLE tbl_name SET `lost` = 1 WHERE `id` IN (a, b, c);
        // $sql = "UPDATE `z_{$this->world->sign}_countries`";
        // $sql .= " SET `active` = 0";
        // $sql .= " WHERE `countryId` IN (".join(',', $this->deleteCountryIds).")";
        //
        // $this->db->statement($sql);
    }

    private function updateCountriesData()
    {
        if (empty($this->eventProcessor->updateCountryIds)) return;

        $pdo = DB::getPdo();

        foreach ($this->eventProcessor->updateCountryIds as $id => $data) {
            $fields = [];
            $props = [];
            if (!is_null($data['currName'])) {
                $fields[] = "`name` = ".$pdo->quote($data['currName']);
                $props[] = ",'$.names', JSON_OBJECT('{$this->time->timestamp}', '{$data['currName']}')";
            }
            if (!is_null($data['currFlag'])) {
                $fields[] = "`flag` = ".$pdo->quote($data['currFlag']);
                $props[] = ",'$.flags', JSON_OBJECT('{$this->time->timestamp}', '{$data['currFlag']}')";
            }
            if (count($props)) {
                $fields[] = "`props` = JSON_ARRAY_APPEND( `props` ".join($props)." )";
            }
            if (count($fields)) {
                DB::update("
                    UPDATE `z_{$this->world->sign}_countries`
                    SET ".join(', ', $fields)."
                    WHERE id = $id
                ");
            }
        }
    }

    private function insertCountriesStatistic()
    {
        if (!$this->hasCountries()) return;

        $columns = [
            'state_at',
            'id',
            'pop',
            'accounts',
            'towns',
            'science',
            'production',
            'attack',
            'defense',
            // 'deltaPop',
            // 'deltaAccounts',
            // 'deltaTowns',
            // 'deltaScience',
            // 'deltaProduction',
            // 'deltaAttack',
            // 'deltaDefense',
        ];

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = 'INSERT';
        $sql .= ' INTO `z_'.$this->world->sign.'_countries_stat`';
        $sql .= ' (`'.join('`,`', $columns).'`)';
        $sql .= ' VALUES ';

        $pdo = DB::getPdo();
        $first = true;

        /** @var \App\Console\Statistic\Data\Country $country */
        foreach ($this->countries as $country) {
            if (!$first) $sql .= ','; else $first = false;

            $sql .= '(';
            $sql .= ($pdo->quote($this->time));
            $sql .= ','.(intval($country->id));
            $sql .= ','.(intval($country->pop));
            $sql .= ','.(intval($country->accounts));
            $sql .= ','.(intval($country->towns));
            $sql .= ','.(intval($country->ratingScience));
            $sql .= ','.(intval($country->ratingProduction));
            $sql .= ','.(intval($country->ratingAttack));
            $sql .= ','.(intval($country->ratingDefense));
            // $sql .= ','.(intval($country->[Storage::COUNTRY_KEY_DELTA_POP]));
            // $sql .= ','.(intval($country->[Storage::COUNTRY_KEY_DELTA_ACCOUNTS]));
            // $sql .= ','.(intval($country->[Storage::COUNTRY_KEY_DELTA_TOWNS]));
            // $sql .= ','.(intval($country->[Storage::COUNTRY_KEY_DELTA_SCIENCE]));
            // $sql .= ','.(intval($country->[Storage::COUNTRY_KEY_DELTA_PRODUCTION]));
            // $sql .= ','.(intval($country->[Storage::COUNTRY_KEY_DELTA_ATTACK]));
            // $sql .= ','.(intval($country->[Storage::COUNTRY_KEY_DELTA_DEFENSE]));
            $sql .= ')';
        }

        DB::insert($sql);
    }
}
