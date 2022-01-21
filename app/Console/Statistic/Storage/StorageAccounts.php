<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

use Illuminate\Support\Facades\DB;

/**
 * Trait TableAccounts
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\EventProcessor\Events events
 * @property \App\Models\World world
 */
trait StorageAccounts
{
    public function updateTableAccounts()
    {
        $time = microtime(true);
        $this->insertAccounts();
        $this->updateAccountsDeleted();
        $this->insertAccountsStatistic();
        $this->console->line('    accounts: '.t($time).'s');
    }

    private function insertAccounts()
    {
        if (empty($this->events->insertAccountIds)) return;

        $columns = [
            'id',
            'name',
            'race',
            'sex',
            'country_id',
            'role',
            'active',
            'props',
        ];

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = 'INSERT';
        $sql .= ' INTO `z_'.$this->world->sign.'_accounts`';
        $sql .= ' (`'.join('`,`', $columns).'`)';
        $sql .= ' VALUES ';

        $pdo = DB::getPdo();
        $first = true;

        foreach ($this->events->insertAccountIds as $id) {
            $account = $this->getAccount($id);

            if (!$first) $sql .= ','; else $first = false;

            $sql .= '(';
            $sql .= (intval($id));
            $sql .= ','.($pdo->quote($account->name));
            $sql .= ','.(intval($account->race));
            $sql .= ','.(intval($account->sex));
            $sql .= ','.(intval($account->countryId));
            $sql .= ','.(intval($account->role));
            $sql .= ','.'0';
            $sql .= ','.'NULL';
            $sql .= ')';
        }

        DB::insert($sql);
    }

    private function updateAccountsDeleted()
    {
        // if (empty($this->deleteAccountIds)) return;
        //
        // // UPDATE TABLE tbl_name SET `lost` = 1 WHERE `id` IN (a, b, c);
        // $sql = "UPDATE `z_{$this->world->sign}_accounts`";
        // $sql .= " SET `active` = 0";
        // $sql .= " WHERE `accountId` IN (".join(',', $this->deleteAccountIds).")";

        // $this->db->statement($sql);
    }

    private function insertAccountsStatistic()
    {
        $columns = [
            'state_at',
            'id',
            'country_id',
            'role',
            'pop',
            'towns',
            'science',
            'production',
            'attack',
            'defense',
            // 'deltaPop',
            // 'deltaTowns',
            // 'deltaScience',
            // 'deltaProduction',
            // 'deltaAttack',
            // 'deltaDefense',
        ];

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = 'INSERT';
        $sql .= ' INTO `z_'.$this->world->sign.'_accounts_stat`';
        $sql .= ' (`'.join('`,`', $columns).'`)';
        $sql .= ' VALUES ';

        $pdo = DB::getPdo();
        $first = true;

        /** @var \App\Console\Statistic\Data\Account $account */
        foreach ($this->accounts as $account) {
            if (!$first) $sql .= ','; else $first = false;

            $sql .= '(';
            $sql .= ($pdo->quote($this->time));
            $sql .= ','.(intval($account->id));
            $sql .= ','.(intval($account->countryId));
            $sql .= ','.(intval($account->role));
            $sql .= ','.(intval($account->pop));
            $sql .= ','.(intval($account->towns));
            $sql .= ','.(intval($account->ratingScience));
            $sql .= ','.(intval($account->ratingProduction));
            $sql .= ','.(intval($account->ratingAttack));
            $sql .= ','.(intval($account->ratingDefense));
            // $sql .= ','.(intval($account[Storage::ACCOUNT_KEY_DELTA_POP]));
            // $sql .= ','.(intval($account[Storage::ACCOUNT_KEY_DELTA_TOWNS]));
            // $sql .= ','.(intval($account[Storage::ACCOUNT_KEY_DELTA_SCIENCE]));
            // $sql .= ','.(intval($account[Storage::ACCOUNT_KEY_DELTA_PRODUCTION]));
            // $sql .= ','.(intval($account[Storage::ACCOUNT_KEY_DELTA_ATTACK]));
            // $sql .= ','.(intval($account[Storage::ACCOUNT_KEY_DELTA_DEFENSE]));
            $sql .= ')';
        }

        DB::insert($sql);
    }
}
