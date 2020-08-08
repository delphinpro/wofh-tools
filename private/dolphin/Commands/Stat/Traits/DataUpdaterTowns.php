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
 * Trait DataUpdaterTowns
 *
 * @package Dolphin\Commands\Stat\Traits
 *
 * @property \Illuminate\Database\Connection db
 * @property \WofhTools\Models\Worlds        world
 */
trait DataUpdaterTowns
{
    private function insertTowns()
    {
        if (empty($this->insertTownIds)) {
            return;
        }

        $columns = [
            'townId',
            'townTitle',
            'accountId',
            'lost',
            //            'destroyed',
            'extra',
        ];


        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = "INSERT";
        $sql .= " INTO `z_{$this->world->sign}_towns`";
        $sql .= " (`".join('`,`', $columns)."`)";
        $sql .= " VALUES ";


        $pdo = $this->db->getPdo();
        $first = true;

        foreach ($this->insertTownIds as $id) {
            $town = $this->curr['towns'][$id];
            if (!$first) {
                $sql .= ',';
            } else {
                $first = false;
            }

            $sql .= "(";
            $sql .= (intval($id)).",";
            $sql .= ($pdo->quote($town[DataStorage::TOWN_KEY_TITLE])).",";
            $sql .= (intval($town[DataStorage::TOWN_KEY_ACCOUNT_ID])).",";
            $sql .= "0,";
//            $sql .= "0,";
            $sql .= "NULL";
            $sql .= ")";
        }

        $this->db->statement($sql);
    }

    private function updateTownsLost()
    {
        if (empty($this->lostTownIds)) {
            return;
        }

        // UPDATE TABLE tbl_name SET `lost` = 1 WHERE `id` IN (a, b, c);
        $sql = "UPDATE `z_{$this->world->sign}_towns`";
        $sql .= " SET `lost` = 1";
        $sql .= " WHERE `townId` IN (".join(',', $this->lostTownIds).")";

        $this->db->statement($sql);
    }


    private function insertTownsStatistic()
    {
        $columns = [
            'stateAt',
            'townId',
            //            'accountId',// TODO Why that here?
            'pop',
            'wonderId',
            'wonderLevel',
            'deltaPop',
        ];


        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = "INSERT";
        $sql .= " INTO `z_{$this->world->sign}_towns_stat`";
        $sql .= " (`".join('`,`', $columns)."`)";
        $sql .= " VALUES ";


        $pdo = $this->db->getPdo();
        $first = true;

        foreach ($this->curr['towns'] as $id => $town) {
            if (!$first) {
                $sql .= ',';
            } else {
                $first = false;
            }

            $sql .= "(";
            $sql .= ($pdo->quote($this->time)).",";
            $sql .= (intval($id)).",";
//            $sql .= (intval($town[DataStorage::TOWN_KEY_ACCOUNT_ID])).",";
            $sql .= (intval($town[DataStorage::TOWN_KEY_POP])).",";
            $sql .= (intval($town[DataStorage::TOWN_KEY_WONDER_ID])).",";
            $sql .= (intval($town[DataStorage::TOWN_KEY_WONDER_LEVEL])).",";
            $sql .= (intval($town[DataStorage::TOWN_KEY_DELTA_POP]))."";
            $sql .= ")";
        }

        $this->db->statement($sql);
    }


    private function updateTowns()
    {
        $this->insertTowns();
        $this->updateTownsLost();
        $this->insertTownsStatistic();
    }
}
