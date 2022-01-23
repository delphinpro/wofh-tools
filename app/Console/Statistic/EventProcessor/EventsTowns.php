<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\EventProcessor;

use App\Console\Statistic\Data\Town;
use App\Services\Wofh;

/**
 * Trait EventsTowns
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\Data\DataStorage curr
 * @property \App\Console\Statistic\Data\DataStorage prev
 * @property array insertTownIds
 * @property array updateTownIds
 * @property array lostTownIds
 */
trait EventsTowns
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
                if ($townPrev->account_id && $townCurr->account_id) {
                    $this->checkEventTownRename($townPrev, $townCurr);
                    $this->checkEventWonderCreate($townPrev, $townCurr);
                    $this->checkEventWonderDestroy($townPrev, $townCurr);
                    $this->checkEventWonderActivate($townPrev, $townCurr);
                }
            }
        }

        $this->console->line('Check events of towns    : '.t($time).'s');
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
            $accountId = $this->curr->getTown($townId)->account_id;

            // Если город не варварский (оварварился в промежутке между считыванием статистики)
            if ($accountId) {
                $countryId = $this->curr->getAccount($accountId)->country_id;

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
            $accountId = $this->prev->getTown($townId)->account_id;
            if ($accountId) {
                $countryId = $this->prev->getAccount($accountId)->country_id;
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
                static::TABLE_ACCOUNT_ID      => $town->account_id,
                static::TABLE_COUNTRY_ID      => $this->curr->getAccount($town->account_id)->country_id,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_PROPS           => [
                    'prev_name' => $townPrev->name,
                    'curr_name' => $town->name,
                ],
            ];
        }
    }

    private function checkEventWonderCreate(Town $townPrev, Town $town)
    {
        /** @noinspection DuplicatedCode */
        if ($townPrev->wonderId() != $town->wonderId() && $townPrev->wonderId() == 0) {
            $this->events[Wofh::EVENT_WONDER_CREATE][] = [
                static::TABLE_TOWN_ID         => $town->id,
                static::TABLE_ACCOUNT_ID      => $town->account_id,
                static::TABLE_COUNTRY_ID      => $this->curr->getAccount($town->account_id)->country_id,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_PROPS           => [
                    'wonder_id'    => $town->wonderId(),
                    'wonder_level' => $town->wonderLevel(),
                ],
            ];
        }
    }

    private function checkEventWonderDestroy(Town $townPrev, Town $town)
    {
        /** @noinspection DuplicatedCode */
        if ($townPrev->wonderId() != $town->wonderId() && $town->wonderId() == 0) {
            $this->events[Wofh::EVENT_WONDER_DESTROY][] = [
                static::TABLE_TOWN_ID         => $town->id,
                static::TABLE_ACCOUNT_ID      => $town->account_id,
                static::TABLE_COUNTRY_ID      => $this->curr->getAccount($town->account_id)->country_id,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_PROPS           => [
                    'wonder_id'    => $townPrev->wonderId(),
                    'wonder_level' => $townPrev->wonderLevel(),
                ],
            ];
        }
    }

    private function checkEventWonderActivate(Town $townPrev, Town $town)
    {
        if ($town->wonderId() > 0
            && $town->wonderLevel() > 20
            && $townPrev->wonderLevel() < 21
        ) {
            $this->events[Wofh::EVENT_WONDER_ACTIVATE][] = [
                static::TABLE_TOWN_ID    => $town->id,
                static::TABLE_ACCOUNT_ID => $town->account_id,
                static::TABLE_COUNTRY_ID => $this->curr->getAccount($town->account_id)->country_id,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_PROPS           => [
                    'wonder_id'    => $town->wonderId(),
                    'wonder_level' => $town->wonderLevel(),
                ],
            ];
        }
    }
}
