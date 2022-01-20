<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\DataEvents;

use App\Console\Statistic\Data\Town;
use App\Services\Wofh;

/**
 * Trait Towns
 *
 * @package App\Console\Services\Statistic
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\DataStorage curr
 * @property \App\Console\Statistic\DataStorage prev
 * @property array insertTownIds
 * @property array updateTownIds
 * @property array lostTownIds
 */
trait Towns
{
    public function checkEventsOfTowns()
    {
        $time = microtime(true);
        $ids = $this->prev->towns->keys()->merge($this->curr->towns->keys())->unique();

        foreach ($ids as $id) {
            $this->checkEventTownCreate($id);
            $this->checkEventTownLost($id);

            if ($this->prev->hasTown($id) && $this->curr->hasTown($id)) {
                $townPrev = $this->prev->getTown($id);
                $townCurr = $this->curr->getTown($id);
                // $this->curr['towns'][$id][static::TOWN_KEY_DELTA_POP] = $townCurr[static::TOWN_KEY_POP] - $townPrev[static::TOWN_KEY_POP];
                if ($townPrev->accountId && $townCurr->accountId) {
                    $this->checkEventTownRename($townPrev, $townCurr);
                    $this->checkEventWonder($townPrev, $townCurr);
                }
            }
        }

        $this->console->line('Check events of towns    : '.t($time).'s');
        // $this->console->line('              created   : '.count($this->events[Wofh::EVENT_TOWN_CREATE]));
        // $this->console->line('              renamed   : '.count($this->events[Wofh::EVENT_TOWN_RENAME]));
        // $this->console->line('              lost      : '.count($this->events[Wofh::EVENT_TOWN_LOST]));
    }

    private function checkEventTownCreate(int $townId)
    {
        if (!$this->prev->hasData()) {
            $this->insertTownIds[] = $townId;
            return;
        }

        // Вчера этого города не было, а сегодня есть
        if (
            !$this->prev->hasTown($townId)
            && $this->curr->hasTown($townId)
        ) {
            $this->insertTownIds[] = $townId;
            // $this->curr['towns'][$townId][static::TOWN_KEY_DELTA_POP] = $this->curr['towns'][$townId][static::TOWN_KEY_POP];
            $accountId = $this->curr->getTown($townId)->accountId;

            // Если город не варварский (оварварился в промежутке между считыванием статистики)
            if ($accountId) {
                $countryId = $this->curr->getAccount($accountId)->countryId;

                $this->events[Wofh::EVENT_TOWN_CREATE][$townId] = [
                    static::TABLE_TOWN_ID         => $townId,
                    static::TABLE_ACCOUNT_ID      => $accountId,
                    static::TABLE_COUNTRY_ID      => $countryId,
                    static::TABLE_COUNTRY_ID_FROM => 0,
                    static::TABLE_ROLE            => 0,
                    static::TABLE_PROPS           => null,
                ];
            }
        }
    }

    private function checkEventTownLost(int $townId)
    {
        if (!$this->prev->hasData()) return;

        // Вчера этот город был, а сегодня его нет
        if (
            $this->prev->hasTown($townId)
            && !$this->curr->hasTown($townId)
        ) {
            $this->lostTownIds[] = $townId;
            // $this->curr['towns'][$townId][static::TOWN_KEY_DELTA_POP] = $this->curr['towns'][$townId][static::TOWN_KEY_POP];
            $accountId = $this->prev->getTown($townId)->accountId;
            if ($accountId) {
                $countryId = $this->prev->getAccount($accountId)->countryId;
                $this->events[Wofh::EVENT_TOWN_LOST][$townId] = [
                    static::TABLE_TOWN_ID         => $townId,
                    static::TABLE_ACCOUNT_ID      => $accountId,
                    static::TABLE_COUNTRY_ID      => $countryId,
                    static::TABLE_COUNTRY_ID_FROM => 0,
                    static::TABLE_ROLE            => 0,
                    static::TABLE_PROPS           => null,
                ];
            }
        }
    }

    private function checkEventTownRename(Town $townPrev, Town $town)
    {
        if ($townPrev->name != $town->name) {
            $this->updateTownIds[] = $town->id;
            $this->events[Wofh::EVENT_TOWN_RENAME][$town->id] = [
                static::TABLE_TOWN_ID         => $town->id,
                static::TABLE_ACCOUNT_ID      => $town->accountId,
                static::TABLE_COUNTRY_ID      => $this->curr->getAccount($town->accountId)->countryId,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_PROPS => [
                    'prevName' => $townPrev->name,
                    'currName' => $town->name,
                ],
            ];
        }
    }

    private function checkEventWonder(Town $townPrev, Town $town)
    {
        $eventData = [
            static::TABLE_TOWN_ID         => $town->id,
            static::TABLE_ACCOUNT_ID      => $town->accountId,
            static::TABLE_COUNTRY_ID      => $this->curr->getAccount($town->accountId)->countryId,
            static::TABLE_COUNTRY_ID_FROM => 0,
            static::TABLE_ROLE            => 0,
        ];

        if ($townPrev->wonderId != $town->wonderId) {
            if ($townPrev->wonderId == 0) {
                $eventData[static::TABLE_PROPS] = [
                    'wonderId'    => $town->wonderId,
                    'wonderLevel' => $town->wonderLevel,
                ];
                $this->events[Wofh::EVENT_WONDER_CREATE][] = $eventData;
            }
            if ($town->wonderId == 0) {
                $eventData[static::TABLE_PROPS] = [
                    'wonderId'    => $townPrev->wonderId,
                    'wonderLevel' => $townPrev->wonderLevel,
                ];
                $this->events[Wofh::EVENT_WONDER_DESTROY][] = $eventData;
            }
        }
        if ($town->wonderId > 0
            && $town->wonderLevel > 20
            && $townPrev->wonderLevel < 21
        ) {
            $eventData[static::TABLE_PROPS] = [
                'wonderId'    => $town->wonderId,
                'wonderLevel' => $town->wonderLevel,
            ];
            $this->events[Wofh::EVENT_WONDER_ACTIVATE][] = $eventData;
        }
    }
}
