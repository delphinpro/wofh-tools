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
 * Trait DataUpdaterAccounts
 *
 * @package Dolphin\Commands\Stat\Traits
 *
 * @property \Illuminate\Database\Connection db
 * @property \WofhTools\Models\Worlds        world
 */
trait DataUpdaterAccounts
{
    private function insertAccounts()
    {
        if (empty($this->insertAccountIds)) {
            return;
        }

        $columns = [
            'accountId',
            'accountName',
            'accountRace',
            'accountSex',
            'countryId',
            'role',
            'active',
            'extra',
        ];


        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = "INSERT";
        $sql .= " INTO `z_{$this->world->sign}_accounts`";
        $sql .= " (`".join('`,`', $columns)."`)";
        $sql .= " VALUES ";


        $pdo = $this->db->getPdo();
        $first = true;

        foreach ($this->insertAccountIds as $id) {
            $account = $this->accounts[$id];
            if (!$first) {
                $sql .= ',';
            } else {
                $first = false;
            }

            $sql .= "(";
            $sql .= (intval($id)).",";
            $sql .= ($pdo->quote($account[DataStorage::ACCOUNT_KEY_TITLE])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_RACE])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_SEX])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_COUNTRY_ID])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_ROLE])).",";
            $sql .= "0,";
            $sql .= "NULL";
            $sql .= ")";
        }

        $this->db->statement($sql);
    }


    private function insertAccountsStatistic()
    {
        $columns = [
            'stateDate',
            'accountId',
            'countryId',
            'role',
            'pop',
            'towns',
            'science',
            'production',
            'attack',
            'defense',
            'deltaPop',
            'deltaTowns',
            'deltaScience',
            'deltaProduction',
            'deltaAttack',
            'deltaDefense',
        ];


        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = "INSERT";
        $sql .= " INTO `z_{$this->world->sign}_accounts_stat`";
        $sql .= " (`".join('`,`', $columns)."`)";
        $sql .= " VALUES ";


        $pdo = $this->db->getPdo();
        $first = true;

        foreach ($this->accounts as $id => $account) {
            if (!$first) {
                $sql .= ',';
            } else {
                $first = false;
            }

            $sql .= "(";
            $sql .= ($pdo->quote($this->time)).",";
            $sql .= (intval($id)).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_COUNTRY_ID])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_ROLE])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_POP])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_TOWNS])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_RATING_SCIENCE])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_RATING_PRODUCTION])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_RATING_ATTACK])).",";
            $sql .= (intval($account[DataStorage::ACCOUNT_KEY_RATING_DEFENSE])).",";
            $sql .= "0,";
            $sql .= "0,";
            $sql .= "0,";
            $sql .= "0,";
            $sql .= "0,";
            $sql .= "0";
            $sql .= ")";
        }

        $this->db->statement($sql);
    }


    private function updateAccounts()
    {
        $this->insertAccounts();
        $this->insertAccountsStatistic();
    }
}
