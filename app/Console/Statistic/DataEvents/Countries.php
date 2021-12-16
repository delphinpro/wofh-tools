<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\DataEvents;

use App\Console\Statistic\Data\Country;
use App\Services\Wofh;

/**
 * Trait Countries
 *
 * @package App\Console\Statistic\DataEvents
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\DataStorage curr
 * @property \App\Console\Statistic\DataStorage prev
 * @property array insertCountryIds
 * @property array updateCountryIds
 * @property array deleteCountryIds
 */
trait Countries
{
    public function checkEventsOfCountries()
    {
        $time = microtime(true);
        $ids = $this->prev->countries->keys()->merge($this->curr->countries->keys())->unique();

        foreach ($ids as $id) {
            $this->checkEventsCountryCreate($id);
            $this->checkEventsCountryDelete($id);
            if ($this->prev->hasCountry($id) && $this->curr->hasCountry($id)) {
                $countryPrev = $this->prev->getCountry($id);
                $countryCurr = $this->curr->getCountry($id);
                // $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_POP] = $countryCurr[static::COUNTRY_KEY_POP] - $countryPrev[static::COUNTRY_KEY_POP];
                // $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_ACCOUNTS] = $countryCurr[static::COUNTRY_KEY_ACCOUNTS] - $countryPrev[static::COUNTRY_KEY_ACCOUNTS];
                // $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_TOWNS] = $countryCurr[static::COUNTRY_KEY_TOWNS] - $countryPrev[static::COUNTRY_KEY_TOWNS];
                // $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_ATTACK] = $countryCurr[static::COUNTRY_KEY_ATTACK] - $countryPrev[static::COUNTRY_KEY_ATTACK];
                // $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_DEFENSE] = $countryCurr[static::COUNTRY_KEY_DEFENSE] - $countryPrev[static::COUNTRY_KEY_DEFENSE];
                // $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_SCIENCE] = $countryCurr[static::COUNTRY_KEY_SCIENCE] - $countryPrev[static::COUNTRY_KEY_SCIENCE];
                // $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_PRODUCTION] = $countryCurr[static::COUNTRY_KEY_PRODUCTION] - $countryPrev[static::COUNTRY_KEY_PRODUCTION];
                $this->checkEventsCountryRename($countryPrev, $countryCurr);
                $this->checkEventsCountryFlag($countryPrev, $countryCurr);
            }
        }

        $this->console->line('Check events of countries: '.t($time).'s');
        // $this->console->line('              created   : '.count($this->events[Wofh::EVENT_ACCOUNT_CREATE]));
        // $this->console->line('              deleted   : '.count($this->events[Wofh::EVENT_ACCOUNT_DELETE]));
        // $this->console->line('              renamed   : '.count($this->events[Wofh::EVENT_TOWN_RENAME]));
        // $this->console->line('              lost      : '.count($this->events[Wofh::EVENT_TOWN_LOST]));
    }

    private function checkEventsCountryCreate(int $countryId)
    {
        if (!$this->prev->hasData()) {
            $this->insertCountryIds[] = $countryId;
            return;
        }

        // Вчера страны не было, а сегодня есть
        if (
            !$this->prev->hasCountry($countryId)
            && $this->curr->hasCountry($countryId)
        ) {
            $this->insertCountryIds[] = $countryId;
            // $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_POP] = $this->curr['countries'][$countryId][static::COUNTRY_KEY_POP];
            // $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_ACCOUNTS] = $this->curr['countries'][$countryId][static::COUNTRY_KEY_ACCOUNTS];
            // $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_TOWNS] = $this->curr['countries'][$countryId][static::COUNTRY_KEY_TOWNS];
            // $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_ATTACK] = $this->curr['countries'][$countryId][static::COUNTRY_KEY_ATTACK];
            // $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_DEFENSE] = $this->curr['countries'][$countryId][static::COUNTRY_KEY_DEFENSE];
            // $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_SCIENCE] = $this->curr['countries'][$countryId][static::COUNTRY_KEY_SCIENCE];
            // $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_PRODUCTION] = $this->curr['countries'][$countryId][static::COUNTRY_KEY_PRODUCTION];
            $this->events[Wofh::EVENT_COUNTRY_CREATE][$countryId] = [
                static::TABLE_TOWN_ID         => 0,
                static::TABLE_ACCOUNT_ID      => 0,
                static::TABLE_COUNTRY_ID      => $countryId,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_EXTRA           => null,
            ];
        }
    }

    private function checkEventsCountryDelete(int $countryId)
    {
        if (!$this->prev->hasData()) return;

        // Вчера страна была, а сегодня ее нет
        if (
            $this->prev->hasCountry($countryId)
            && !$this->curr->hasCountry($countryId)
        ) {
            $this->deleteCountryIds[] = $countryId;
            $this->events[Wofh::EVENT_COUNTRY_DESTROY][$countryId] = [
                static::TABLE_TOWN_ID         => 0,
                static::TABLE_ACCOUNT_ID      => 0,
                static::TABLE_COUNTRY_ID      => $countryId,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_EXTRA           => null,
            ];
        }
    }

    private function checkEventsCountryRename(Country $countryPrev, Country $country)
    {
        if ($countryPrev->name != $country->name) {
            $this->updateCountryIds[] = $country->id;
            $this->events[Wofh::EVENT_COUNTRY_RENAME][$country->id] = [
                static::TABLE_TOWN_ID         => 0,
                static::TABLE_ACCOUNT_ID      => 0,
                static::TABLE_COUNTRY_ID      => $country->id,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_EXTRA           => [
                    'prevName' => $countryPrev->name,
                    'nextName' => $country->name,
                ],
            ];
        }
    }

    private function checkEventsCountryFlag(Country $countryPrev, Country $country)
    {
        if ($countryPrev->flag != $country->flag) {
            $extra = [
                'prevFlag' => $countryPrev->flag,
                'currFlag' => $country->flag,
            ];
            $this->addUpdatedCountry($country->id, $extra);
            $this->events[Wofh::EVENT_COUNTRY_FLAG][$country->id] = [
                static::TABLE_TOWN_ID         => 0,
                static::TABLE_ACCOUNT_ID      => 0,
                static::TABLE_COUNTRY_ID      => $country->id,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_EXTRA           => $extra,
            ];
        }
    }

    private function addUpdatedCountry($countryId, $data = [])
    {
        if (!array_key_exists($countryId, $this->updateCountryIds)) {
            $this->updateCountryIds[$countryId] = [
                'prevName' => null,
                'currName' => null,
                'prevFlag'  => null,
                'currFlag'  => null,
                'active'    => null,
            ];
        }
        $this->updateCountryIds[$countryId] = array_merge(
            $this->updateCountryIds[$countryId],
            $data
        );
    }
}
