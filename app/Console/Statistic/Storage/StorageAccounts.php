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
use App\Console\Statistic\StorageProcessor\StorageProcessor;
use Illuminate\Support\Facades\DB;

/**
 * Trait TableAccounts
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\EventProcessor\EventProcessor $eventProcessor
 * @property \App\Models\World world
 */
trait StorageAccounts
{
    public function updateTableAccounts()
    {
        $time = microtime(true);
        $this->updateAccountsCreated();
        $this->updateAccountsDeleted();
        $this->updateAccounts();
        $this->insertAccountsStatistic();
        $this->console->line('    accounts: '.t($time).'s');
    }

    private function updateAccountsCreated()
    {
        $accounts = $this->eventProcessor->getAccountsForInsert()->toArray();
        DB::table('accounts')->insert($accounts);
    }

    private function updateAccountsDeleted()
    {
        DB::table('accounts')
            ->whereIn('id', $this->eventProcessor->getDeletedAccountIds())
            ->update([
                'role'       => 0,
                'pop'        => 0,
                'towns'      => 0,
                'science'    => 0,
                'production' => 0,
                'attack'     => 0,
                'defense'    => 0,
                'active'     => 0,
            ]);
    }

    private function updateAccounts()
    {
        $this->eventProcessor->getAccountsForUpdate()->each(function (Account $account) {
            DB::table('accounts')
                ->where('id', $account->id)
                ->update($account->toArray());
        });
    }

    /** @noinspection DuplicatedCode */
    private function insertAccountsStatistic()
    {
        $tableName = 'z_'.$this->world->sign.'_accounts_data';
        $columns = collect([
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
            'delta_pop',
            'delta_towns',
            'delta_science',
            'delta_production',
            'delta_attack',
            'delta_defense',
        ])->map(fn($s) => "`$s`")->join(',');

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $queryStringCommon = "INSERT INTO `$tableName` ($columns) VALUES ";

        $accounts = $this->eventProcessor->getAccounts();
        $first = true;
        $counter = 0;
        $queryStringValues = '';

        foreach ($accounts as $account) {
            if (!$first) $queryStringValues .= ',';
            $first = false;

            $queryStringValues .= '('."'{$this->getTime()}'";
            $queryStringValues .= ','.$account->id;
            $queryStringValues .= ','.($account->country_id ?? DB::raw('NULL'));
            $queryStringValues .= ','.$account->role;
            $queryStringValues .= ','.$account->pop;
            $queryStringValues .= ','.$account->towns;
            $queryStringValues .= ','.$account->science;
            $queryStringValues .= ','.$account->production;
            $queryStringValues .= ','.$account->attack;
            $queryStringValues .= ','.$account->defense;
            $queryStringValues .= ','.$account->getDeltaPop();
            $queryStringValues .= ','.$account->getDeltaTowns();
            $queryStringValues .= ','.$account->getDeltaScience();
            $queryStringValues .= ','.$account->getDeltaProduction();
            $queryStringValues .= ','.$account->getDeltaAttack();
            $queryStringValues .= ','.$account->getDeltaDefence();
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
