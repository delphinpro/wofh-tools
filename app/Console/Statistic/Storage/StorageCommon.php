<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

use App\Console\Statistic\Data\Account;
use App\Services\Wofh;
use Illuminate\Support\Facades\DB;

trait StorageCommon
{
    public function updateTableCommon()
    {
        DB::table('common')->insert([
            'state_at' => $this->getTime(),

            'towns_total'     => count($this->towns),
            'towns_created'   => $this->eventProcessor->count(Wofh::EVENT_TOWN_CREATE),
            'towns_renamed'   => $this->eventProcessor->count(Wofh::EVENT_TOWN_RENAME),
            'towns_lost'      => $this->eventProcessor->count(Wofh::EVENT_TOWN_LOST),
            'towns_destroyed' => 0,

            'wonders_started'   => $this->eventProcessor->count(Wofh::EVENT_WONDER_CREATE),
            'wonders_destroyed' => $this->eventProcessor->count(Wofh::EVENT_WONDER_DESTROY),
            'wonders_activated' => $this->eventProcessor->count(Wofh::EVENT_WONDER_ACTIVATE),

            'accounts_total'  => $this->totalAccounts,
            'accounts_active' => $this->accounts->filter(fn(Account $acc) => $acc->pop > 0)->count(),
            'accounts_race0'  => $this->accounts->filter(fn(Account $acc) => $acc->race == 0)->count(),
            'accounts_race1'  => $this->accounts->filter(fn(Account $acc) => $acc->race == 1)->count(),
            'accounts_race2'  => $this->accounts->filter(fn(Account $acc) => $acc->race == 2)->count(),
            'accounts_race3'  => $this->accounts->filter(fn(Account $acc) => $acc->race == 3)->count(),
            'accounts_sex0'   => $this->accounts->filter(fn(Account $acc) => $acc->sex == 0)->count(),
            'accounts_sex1'   => $this->accounts->filter(fn(Account $acc) => $acc->sex == 1)->count(),

            'accounts_new'            => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_CREATE),
            'accounts_country_change' => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_COUNTRY_CHANGE),
            'accounts_country_in'     => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_COUNTRY_IN),
            'accounts_country_out'    => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_COUNTRY_OUT),
            'accounts_deleted'        => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_DELETE),
            'accounts_renamed'        => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_RENAME),
            'accounts_role_in'        => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_ROLE_IN),
            'accounts_role_out'       => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_ROLE_OUT),
            'accounts_rating_hide'    => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_RATING_HIDE),
            'accounts_rating_show'    => $this->eventProcessor->count(Wofh::EVENT_ACCOUNT_RATING_SHOW),

            'countries_total'   => count($this->countries),
            'countries_created' => $this->eventProcessor->count(Wofh::EVENT_COUNTRY_CREATE),
            'countries_renamed' => $this->eventProcessor->count(Wofh::EVENT_COUNTRY_RENAME),
            'countries_flag'    => $this->eventProcessor->count(Wofh::EVENT_COUNTRY_FLAG),
            'countries_deleted' => $this->eventProcessor->count(Wofh::EVENT_COUNTRY_DESTROY),
        ]);
    }
}
