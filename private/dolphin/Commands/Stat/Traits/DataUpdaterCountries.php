<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace Dolphin\Commands\Stat\Traits;


use Dolphin\Commands\Stat\DataStorage;


/**
 * Trait DataUpdaterCountries
 *
 * @package Dolphin\Commands\Stat\Traits
 *
 * @property \Illuminate\Database\Connection db
 * @property \WofhTools\Models\Worlds        world
 */
trait DataUpdaterCountries
{
    private function insertCountries()
    {
        if (empty($this->insertCountryIds)) {
            return;
        }

        $columns = [
            'countryId',
            'countryTitle',
            'countryFlag',
            'active',
            'extra',
        ];


        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = "INSERT";
        $sql .= " INTO `z_{$this->world->sign}_countries`";
        $sql .= " (`".join('`,`', $columns)."`)";
        $sql .= " VALUES ";


        $pdo = $this->db->getPdo();
        $first = true;

        foreach ($this->insertCountryIds as $id) {
            $country = $this->countries[$id];
            if (!$first) {
                $sql .= ',';
            } else {
                $first = false;
            }

            $sql .= "(";
            $sql .= (intval($id)).",";
            $sql .= ($pdo->quote($country[DataStorage::COUNTRY_KEY_TITLE])).",";
            $sql .= ($pdo->quote($country[DataStorage::COUNTRY_KEY_FLAG])).",";
            $sql .= (intval(1)).",";
            $sql .= "NULL";
            $sql .= ")";
        }

        $this->db->statement($sql);
    }


    private function insertCountriesStatistic()
    {
        if (empty($this->countries)) {
            return;
        }

        $columns = [
            'stateDate',
            'countryId',
            'pop',
            'accounts',
            'towns',
            'science',
            'production',
            'attack',
            'defense',
            'deltaPop',
            'deltaAccounts',
            'deltaTowns',
            'deltaScience',
            'deltaProduction',
            'deltaAttack',
            'deltaDefense',
        ];


        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = "INSERT";
        $sql .= " INTO `z_{$this->world->sign}_countries_stat`";
        $sql .= " (`".join('`,`', $columns)."`)";
        $sql .= " VALUES ";


        $pdo = $this->db->getPdo();
        $first = true;

        foreach ($this->countries as $id => $country) {
            if (!$first) {
                $sql .= ',';
            } else {
                $first = false;
            }

            $sql .= "(";
            $sql .= ($pdo->quote($this->time)).",";
            $sql .= (intval($id)).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_POP])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_ACCOUNTS])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_TOWNS])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_SCIENCE])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_PRODUCTION])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_ATTACK])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_DEFENSE])).",";
            $sql .= (intval(0)).",";
            $sql .= (intval(0)).",";
            $sql .= (intval(0)).",";
            $sql .= (intval(0)).",";
            $sql .= (intval(0)).",";
            $sql .= (intval(0)).",";
            $sql .= (intval(0))."";
            $sql .= ")";
        }

        $this->db->statement($sql);
    }


    private function updateCountries()
    {
        $this->insertCountries();
        $this->insertCountriesStatistic();
    }
}
