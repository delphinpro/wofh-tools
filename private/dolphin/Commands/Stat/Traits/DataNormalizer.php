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


trait DataNormalizer
{

    private function normalizeTowns()
    {
        foreach ($this->raw['towns'] as $id => $town) {

            // Убрать города с нулевым населением
            // и варварские (аккаунт = 0)
            if ($this->isTownNullPopulation($town) or $this->isTownBarbarian($town)) {
                continue;
            }

            $wonder = array_key_exists(DataStorage::TOWN_KEY_WONDER, $town)
                ? $town[DataStorage::TOWN_KEY_WONDER]
                : 0;

            $this->towns[$id] = [
                DataStorage::TOWN_KEY_TITLE        => $town[DataStorage::TOWN_KEY_TITLE],
                DataStorage::TOWN_KEY_ACCOUNT_ID   => $town[DataStorage::TOWN_KEY_ACCOUNT_ID],
                DataStorage::TOWN_KEY_POP          => $town[DataStorage::TOWN_KEY_POP],
                DataStorage::TOWN_KEY_WONDER       => $wonder,
                DataStorage::TOWN_KEY_WONDER_ID    => $wonder % 1000,
                DataStorage::TOWN_KEY_WONDER_LEVEL => (int)floor($wonder / 1000),
            ];
        }
    }


    private function normalizeAccounts()
    {
        foreach ($this->raw['accounts'] as $id => $account) {
            $this->accounts[$id] = [
                DataStorage::ACCOUNT_KEY_TITLE             => $account[DataStorage::ACCOUNT_KEY_TITLE],
                DataStorage::ACCOUNT_KEY_RACE              => $account[DataStorage::ACCOUNT_KEY_RACE],
                DataStorage::ACCOUNT_KEY_SEX               => $account[DataStorage::ACCOUNT_KEY_SEX],
                DataStorage::ACCOUNT_KEY_COUNTRY_ID        => $account[DataStorage::ACCOUNT_KEY_COUNTRY_ID],
                DataStorage::ACCOUNT_KEY_RATING_ATTACK     => $account[DataStorage::ACCOUNT_KEY_RATING_ATTACK],
                DataStorage::ACCOUNT_KEY_RATING_DEFENSE    => $account[DataStorage::ACCOUNT_KEY_RATING_DEFENSE],
                DataStorage::ACCOUNT_KEY_RATING_SCIENCE    => $account[DataStorage::ACCOUNT_KEY_RATING_SCIENCE],
                DataStorage::ACCOUNT_KEY_RATING_PRODUCTION => $account[DataStorage::ACCOUNT_KEY_RATING_PRODUCTION],
                DataStorage::ACCOUNT_KEY_ROLE              => $account[DataStorage::ACCOUNT_KEY_ROLE],
                DataStorage::ACCOUNT_KEY_POP               => 0,
                DataStorage::ACCOUNT_KEY_TOWNS             => 0,
            ];
        }
    }


    private function normalizeCountries()
    {
        if (empty($this->raw['countries'])) {
            $this->raw['countries'] = [];
        }

        foreach ($this->raw['countries'] as $id => $country) {
            $diplomacy = array_key_exists(DataStorage::COUNTRY_KEY_DIPLOMACY, $country)
                ? $country[DataStorage::COUNTRY_KEY_DIPLOMACY]
                : [];

            $this->countries[$id] = [
                DataStorage::COUNTRY_KEY_TITLE      => $country[DataStorage::COUNTRY_KEY_TITLE],
                DataStorage::COUNTRY_KEY_FLAG       => $country[DataStorage::COUNTRY_KEY_FLAG],
                DataStorage::COUNTRY_KEY_DIPLOMACY  => $diplomacy,
                DataStorage::COUNTRY_KEY_POP        => 0,
                DataStorage::COUNTRY_KEY_ACCOUNTS   => 0,
                DataStorage::COUNTRY_KEY_TOWNS      => 0,
                DataStorage::COUNTRY_KEY_SCIENCE    => 0,
                DataStorage::COUNTRY_KEY_PRODUCTION => 0,
                DataStorage::COUNTRY_KEY_ATTACK     => 0,
                DataStorage::COUNTRY_KEY_DEFENSE    => 0,
            ];
        }
    }


    /**
     * @param $town
     *
     * @return bool
     */
    private function isTownNullPopulation($town): bool
    {
        return $town[DataStorage::TOWN_KEY_POP] == 0;
    }


    /**
     * @param $town
     *
     * @return bool
     */
    private function isTownBarbarian($town): bool
    {
        return $town[DataStorage::TOWN_KEY_ACCOUNT_ID] == 0;
    }
}
