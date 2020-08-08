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
            $country = $this->curr['countries'][$id];
            if (!$first) {
                $sql .= ',';
            } else {
                $first = false;
            }

            $json = "JSON_OBJECT(
              'flags', JSON_ARRAY(JSON_OBJECT('{$this->time->timestamp}', '{$country[DataStorage::COUNTRY_KEY_FLAG]}')),
              'titles', JSON_ARRAY(JSON_OBJECT('{$this->time->timestamp}', '{$country[DataStorage::COUNTRY_KEY_TITLE]}'))
            )";

            $sql .= "(";
            $sql .= (intval($id)).",";
            $sql .= ($pdo->quote($country[DataStorage::COUNTRY_KEY_TITLE])).",";
            $sql .= ($pdo->quote($country[DataStorage::COUNTRY_KEY_FLAG])).",";
            $sql .= (intval(1)).",";
            $sql .= $json;
            $sql .= ")";
        }

        $this->db->statement($sql);
    }


    private function updateCountriesDeleted()
    {
        if (empty($this->deleteCountryIds)) {
            return;
        }

        // UPDATE TABLE tbl_name SET `lost` = 1 WHERE `id` IN (a, b, c);
        $sql = "UPDATE `z_{$this->world->sign}_countries`";
        $sql .= " SET `active` = 0";
        $sql .= " WHERE `countryId` IN (".join(',', $this->deleteCountryIds).")";

        $this->db->statement($sql);
    }


    private function updateCountriesData()
    {
        if (empty($this->updateCountryIds)) {
            return;
        }

        $pdo = $this->db->getPdo();

        foreach ($this->updateCountryIds as $id => $data) {
            $fields = [];
            $extra = [];
            if (!is_null($data['currTitle'])) {
                $fields[] = "`countryTitle` = ".$pdo->quote($data['currTitle']);
                $extra[] = ",'$.titles', JSON_OBJECT('{$this->time->timestamp}', '{$data['currTitle']}')";
            }
            if (!is_null($data['currFlag'])) {
                $fields[] = "`countryFlag` = ".$pdo->quote($data['currFlag']);
                $extra[] = ",'$.flags', JSON_OBJECT('{$this->time->timestamp}', '{$data['currFlag']}')";
            }
            if (count($extra)) {
                $fields[] = "`extra` = JSON_ARRAY_APPEND( `extra` ".join($extra)." )";
            }
            if (count($fields)) {
                $this->db->statement("
                    UPDATE `z_{$this->world->sign}_countries`
                    SET ".join(', ', $fields)."
                    WHERE countryId = {$id}
                ");
            }
        }
    }


    private function insertCountriesStatistic()
    {
        if (empty($this->curr['countries'])) {
            return;
        }

        $columns = [
            'stateAt',
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

        foreach ($this->curr['countries'] as $id => $country) {
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
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_DELTA_POP])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_DELTA_ACCOUNTS])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_DELTA_TOWNS])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_DELTA_SCIENCE])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_DELTA_PRODUCTION])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_DELTA_ATTACK])).",";
            $sql .= (intval($country[DataStorage::COUNTRY_KEY_DELTA_DEFENSE]))."";
            $sql .= ")";
        }

        $this->db->statement($sql);
    }


    private function updateCountries()
    {
        $this->insertCountries();
        $this->updateCountriesDeleted();
        $this->updateCountriesData();
        $this->insertCountriesStatistic();
    }
}
