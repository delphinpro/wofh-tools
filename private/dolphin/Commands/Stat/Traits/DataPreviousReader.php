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
        $table = $this->db->table('z_'.$this->world->sign.'_towns_stat');
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

        foreach ($data as $datum) {
            $this->prev['towns'][$datum->townId] = (array)$datum;
            if ($this->isTownLost($datum->townId)) {
                $this->console->write('town lost: ('.$datum->townId.') '.$datum->townTitle.'');
            }
        }
    }


    /**
     * @param int $townId
     *
     * @return bool
     */
    private function isTownLost(int $townId)
    {
        return !array_key_exists($townId, $this->towns);
    }


    private function readPreviousIndexOfAccounts(Carbon $lastDate)
    {
        $table = $this->db->table('z_'.$this->world->sign.'_accounts AS t')
            ->select()
            ->leftJoin('z_'.$this->world->sign.'_accounts_stat AS s', 't.accountId', '=',
                's.accountId')
            ->where('s.stateDate', '=', $lastDate);
        $data = $table->get();

        foreach ($data as $datum) {
            $this->prev['accounts'][$datum->accountId] = (array)$datum;
            if ($this->isAccountDeleted($datum->accountId)) {
                $this->console->write('account deleted: ('.$datum->accountId.') '
                    .$datum->accountName.'');
            }
        }

        foreach ($this->accounts as $accountId => $account) {
            if ($this->isAccountNew($accountId)) {
                $this->insertAccountIds[] = $accountId;
                $this->console->write('account created: ('.$accountId.') '
                    .$this->accounts[DataStorage::ACCOUNT_KEY_TITLE].'');
            }
        }
    }


    private function isAccountDeleted($accountId)
    {
        return !array_key_exists($accountId, $this->accounts);
    }


    private function isAccountNew($accountId)
    {
        return !array_key_exists($accountId, $this->prev['accounts']);
    }


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
