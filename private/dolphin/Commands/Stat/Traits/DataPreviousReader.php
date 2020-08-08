<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace Dolphin\Commands\Stat\Traits;


use Carbon\Carbon;
use Dolphin\Commands\Stat\DataStorage;


/**
 * Trait DataPreviousReader
 *
 * @package Dolphin\Commands\Stat\Traits
 *
 * @property \Dolphin\Console                console
 * @property \Illuminate\Database\Connection db
 * @property \WofhTools\Models\Worlds        world
 */
trait DataPreviousReader
{
    /**
     * @return \Carbon\Carbon|null
     */
    private function getLastDate()
    {
        $table = $this->db->table('z_'.$this->world->sign.'_common');
        $datetime = $table->select('stateDate')->max('stateDate');

        if (is_null($datetime)) {
            return null;
        }

        return new Carbon($datetime);
    }


    private function readPreviousIndexOfTowns(Carbon $lastDate)
    {
        $table = $this->db->table('z_'.$this->world->sign.'_towns AS t')
            ->select()
            ->leftJoin('z_'.$this->world->sign.'_towns_stat AS s', 't.townId', '=', 's.townId')
            ->where('s.stateDate', '=', $lastDate);
        $data = $table->get();

//        print_r('$data[1000]');
//        print_r($data[1000]);

        foreach ($data as $prevTown) {
//            $this->prev['towns'][$prevTown->townId] = (array)$prevTown;
            $this->prev['towns'][$prevTown->townId] = [
                DataStorage::TOWN_KEY_TITLE        => $prevTown->townTitle,
                DataStorage::TOWN_KEY_ACCOUNT_ID   => $prevTown->accountId,
                DataStorage::TOWN_KEY_POP          => $prevTown->pop,
                DataStorage::TOWN_KEY_WONDER       => $prevTown->wonderId * 1000 + $prevTown->wonderLevel,
                DataStorage::TOWN_KEY_WONDER_ID    => $prevTown->wonderId,
                DataStorage::TOWN_KEY_WONDER_LEVEL => $prevTown->wonderLevel,
                DataStorage::TOWN_KEY_COUNTRY_ID   => 0,
            ];
//            $this->checkEventsOfTowns();
//            if ($this->isTownLost($prevTown->townId)) {
//                $this->console->write('town lost: ('.$prevTown->townId.') '.$prevTown->townTitle.'');
//            }
        }
    }


    private function readPreviousIndexOfAccounts(Carbon $lastDate)
    {
        $table = $this->db->table('z_'.$this->world->sign.'_accounts AS t')
            ->select()
            ->leftJoin('z_'.$this->world->sign.'_accounts_stat AS s', 't.accountId', '=',
                's.accountId')
            ->where('s.stateDate', '=', $lastDate);
        $data = $table->get();

        foreach ($data as $account) {
//            $this->prev['accounts'][$account->accountId] = (array)$account;
            $this->prev['accounts'][$account->accountId] = [
                static::ACCOUNT_KEY_TITLE             => $account->accountName,
                static::ACCOUNT_KEY_RACE              => $account->accountRace,
                static::ACCOUNT_KEY_SEX               => $account->accountSex,
                static::ACCOUNT_KEY_COUNTRY_ID        => $account->countryId,
                static::ACCOUNT_KEY_RATING_ATTACK     => $account->attack,
                static::ACCOUNT_KEY_RATING_DEFENSE    => $account->defense,
                static::ACCOUNT_KEY_RATING_SCIENCE    => $account->science,
                static::ACCOUNT_KEY_RATING_PRODUCTION => $account->production,
                static::ACCOUNT_KEY_ROLE              => $account->role,
                static::ACCOUNT_KEY_POP               => $account->pop,
                static::ACCOUNT_KEY_TOWNS             => $account->towns,
            ];
//            if ($this->isAccountDeleted($account->accountId)) {
//                $this->console->write('account deleted: ('.$account->accountId.') '
//                    .$account->accountName.'');
//            }
        }

//        foreach ($this->accounts as $accountId => $account) {
//            if ($this->isAccountNew($accountId)) {
//                $this->insertAccountIds[] = $accountId;
//                $this->console->write('account created: ('.$accountId.') '
//                    .$this->accounts[DataStorage::ACCOUNT_KEY_TITLE].'');
//            }
//        }

//        print_r($this->prev['accounts'][994]);
//        print_r($this->accounts[994]);
    }


//    private function isAccountDeleted($accountId)
//    {
//        return !array_key_exists($accountId, $this->accounts);
//    }
//
//
//    private function isAccountNew($accountId)
//    {
//        return !array_key_exists($accountId, $this->prev['accounts']);
//    }


    private function readPreviousIndexOfCountries(Carbon $lastDate)
    {
        $table = $this->db->table('z_'.$this->world->sign.'_countries AS t')
            ->select()
            ->leftJoin('z_'.$this->world->sign.'_countries_stat AS s', 't.countryId', '=',
                's.countryId')
            ->where('s.stateDate', '=', $lastDate);
        $data = $table->get();

        foreach ($data as $datum) {
            $this->prev['countries'][$datum->countryId] = (array)$datum;
        }
    }

}
