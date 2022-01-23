<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

use App\Services\Wofh;
use Illuminate\Support\Facades\DB;

trait StorageCommon
{
    public function updateTableCommon()
    {
        DB::table('common')->insert([
            'state_at' => $this->getTime(),

            'towns_total'     => $this->eventProcessor->getCountTowns(),
            'towns_created'   => $this->eventProcessor->getCountEvents(Wofh::EVENT_TOWN_CREATE),
            'towns_renamed'   => $this->eventProcessor->getCountEvents(Wofh::EVENT_TOWN_RENAME),
            'towns_lost'      => $this->eventProcessor->getCountEvents(Wofh::EVENT_TOWN_LOST),
            'towns_destroyed' => $this->eventProcessor->getCountEvents(Wofh::EVENT_TOWN_DESTROY),

            'wonders_started'   => $this->eventProcessor->getCountEvents(Wofh::EVENT_WONDER_CREATE),
            'wonders_destroyed' => $this->eventProcessor->getCountEvents(Wofh::EVENT_WONDER_DESTROY),
            'wonders_activated' => $this->eventProcessor->getCountEvents(Wofh::EVENT_WONDER_ACTIVATE),

            'accounts_total'  => $this->eventProcessor->getCountAccountsTotal(),
            'accounts_active' => $this->eventProcessor->getCountAccountsActive(),
            'accounts_race0'  => $this->eventProcessor->getCountAccountsRace0(),
            'accounts_race1'  => $this->eventProcessor->getCountAccountsRace1(),
            'accounts_race2'  => $this->eventProcessor->getCountAccountsRace2(),
            'accounts_race3'  => $this->eventProcessor->getCountAccountsRace3(),
            'accounts_sex0'   => $this->eventProcessor->getCountAccountsSex0(),
            'accounts_sex1'   => $this->eventProcessor->getCountAccountsSex1(),

            'accounts_created'        => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_CREATE),
            'accounts_country_change' => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_COUNTRY_CHANGE),
            'accounts_country_in'     => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_COUNTRY_IN),
            'accounts_country_out'    => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_COUNTRY_OUT),
            'accounts_deleted'        => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_DELETE),
            'accounts_renamed'        => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_RENAME),
            'accounts_role_in'        => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_ROLE_IN),
            'accounts_role_out'       => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_ROLE_OUT),
            'accounts_rating_hide'    => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_RATING_HIDE),
            'accounts_rating_show'    => $this->eventProcessor->getCountEvents(Wofh::EVENT_ACCOUNT_RATING_SHOW),

            'countries_total'   => $this->eventProcessor->getCountCountries(),
            'countries_created' => $this->eventProcessor->getCountEvents(Wofh::EVENT_COUNTRY_CREATE),
            'countries_renamed' => $this->eventProcessor->getCountEvents(Wofh::EVENT_COUNTRY_RENAME),
            'countries_flag'    => $this->eventProcessor->getCountEvents(Wofh::EVENT_COUNTRY_FLAG),
            'countries_deleted' => $this->eventProcessor->getCountEvents(Wofh::EVENT_COUNTRY_DESTROY),
        ]);
    }
}
