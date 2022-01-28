<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\EventProcessor;

use App\Console\Statistic\Data\Country;
use App\Services\Wofh;

/**
 * Trait EventsCountries
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\Data\DataStorage curr
 * @property \App\Console\Statistic\Data\DataStorage prev
 * @property array insertCountryIds
 * @property array updateCountryIds
 * @property array deleteCountryIds
 */
trait EventsCountries
{
    public function checkEventsOfCountries()
    {
        $time = microtime(true);
        $ids = $this->prev->countries->keys()->merge($this->curr->countries->keys())->unique();

        foreach ($ids as $id) {
            $this->checkEventsCountryCreate($id);
            $this->checkEventsCountryDelete($id);

            if ($this->prev->hasCountry($id) && $this->curr->hasCountry($id)) {
                $prevCountry = $this->prev->getCountry($id);
                ($currCountry = $this->curr->getCountry($id))
                    ->setDeltaPop($currCountry->pop - $prevCountry->pop)
                    ->setDeltaAccounts($currCountry->accounts - $prevCountry->accounts)
                    ->setDeltaTowns($currCountry->towns - $prevCountry->towns)
                    ->setDeltaAttack($currCountry->attack - $prevCountry->attack)
                    ->setDeltaDefence($currCountry->defense - $prevCountry->defense)
                    ->setDeltaScience($currCountry->science - $prevCountry->science)
                    ->setDeltaProduction($currCountry->production - $prevCountry->production);

                $this->checkEventsCountryRename($prevCountry, $currCountry);
                $this->checkEventsCountryFlag($prevCountry, $currCountry);
            }
        }

        $this->console->line('Check events of countries: '.t($time).'s');
    }

    private function checkEventsCountryCreate(int $countryId)
    {
        // СЕГОДНЯ страна есть
        if ($this->curr->hasCountry($countryId)) {
            ($country = $this->curr->getCountry($countryId))
                ->setDeltaPop($country->pop)
                ->setDeltaAccounts($country->accounts)
                ->setDeltaTowns($country->towns)
                ->setDeltaScience($country->science)
                ->setDeltaProduction($country->production)
                ->setDeltaAttack($country->attack)
                ->setDeltaDefence($country->defense);

            if (!$this->prev->hasData()) { // Первый день. События не создаём.
                $this->insertCountryIds[] = $countryId;
                return;
            }

            // Вчера страны не было, а сегодня есть
            if (!$this->prev->hasCountry($countryId)
                && !$this->countries->get($countryId) // В базе тоже проверим наличие
            ) {
                $this->insertCountryIds[] = $countryId;
                $this->push(Wofh::EVENT_COUNTRY_CREATE, [
                    static::TABLE_COUNTRY_ID => $countryId,
                ]);
            }
        }
    }

    private function checkEventsCountryDelete(int $countryId)
    {
        if (!$this->prev->hasData()) return; // Первый день. События не создаём.

        // Вчера страна была, а сегодня ее нет
        if (
            $this->prev->hasCountry($countryId)
            && !$this->curr->hasCountry($countryId)
        ) {
            $this->curr->countries->put($countryId,
                ($country = $this->prev->getCountry($countryId))
                    ->setDeltaPop(-$country->pop)
                    ->setDeltaAccounts(-$country->accounts)
                    ->setDeltaTowns(-$country->towns)
                    ->setDeltaScience(-$country->science)
                    ->setDeltaProduction(-$country->production)
                    ->setDeltaAttack(-$country->attack)
                    ->setDeltaDefence(-$country->defense)
            );

            $this->deleteCountryIds[] = $countryId;
            $this->push(Wofh::EVENT_COUNTRY_DESTROY, [
                static::TABLE_COUNTRY_ID => $countryId,
            ]);
        }
    }

    private function checkEventsCountryRename(Country $prevCountry, Country $country)
    {
        if ($prevCountry->name != $country->name) {
            $this->updateCountryIds[] = $country->id;
            $country->mergeJsonField('names', [$this->time->timestamp => $country->name]);
            $this->push(Wofh::EVENT_COUNTRY_RENAME, [
                static::TABLE_COUNTRY_ID => $country->id,
                static::TABLE_PROPS      => [
                    'prevName' => $prevCountry->name,
                    'nextName' => $country->name,
                ],
            ]);
        }
    }

    private function checkEventsCountryFlag(Country $prevCountry, Country $country)
    {
        if ($prevCountry->flag != $country->flag) {
            $this->updateCountryIds[] = $country->id;
            $country->mergeJsonField('flags', [$this->time->timestamp => $country->flag]);
            $this->push(Wofh::EVENT_COUNTRY_FLAG, [
                static::TABLE_COUNTRY_ID => $country->id,
                static::TABLE_PROPS      => [
                    'prevFlag' => $prevCountry->flag,
                    'nextFlag' => $country->flag,
                ],
            ]);
        }
    }
}
