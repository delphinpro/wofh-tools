<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\EventProcessor;

use App\Console\Statistic\Data\Town;
use App\Services\Wofh;
use Illuminate\Support\Facades\DB;

trait EventsTowns
{
    // Данные загружены из файлов
    // Города с нулевым населением удалены из списка
    protected function checkEventsOfTowns()
    {
        $time = microtime(true);

        $filter = $this->prev->hasData()
            ? fn(Town $town) => $town
            : fn(Town $town) => !!$town->account_id; // Изначально варварские

        $currKeys = $this->curr->towns->filter($filter)->keys();
        $ids = $this->prev->towns->keys()->merge($currKeys)->unique();

        foreach ($ids as $id) {
            $this->checkEventTownCreate($id);
            $this->checkEventTownDestroy($id, $this->towns->get($id));

            // Город был вчера и есть сегодня
            if ($this->prev->hasTown($id) && $this->curr->hasTown($id)) {
                $townPrev = $this->prev->getTown($id);
                $townCurr = $this->curr->getTown($id);
                $townCurr->setDeltaPop($townCurr->pop - $townPrev->pop);

                $this->checkEventTownLost($this->towns->get($id), $townCurr);
                $this->checkEventTownRename($townPrev, $townCurr);
                $this->checkEventWonderCreate($townPrev, $townCurr);
                $this->checkEventWonderDestroy($townPrev, $townCurr);
                $this->checkEventWonderActivate($townPrev, $townCurr);
            }
        }

        $this->console->line('Check events of towns    : '.t($time).'s');
    }

    private function checkEventTownCreate($townId)
    {
        if ($this->curr->hasTown($townId)) {
            $town = $this->curr->getTown($townId);
            $town->setDeltaPop($town->pop);

            if (!$this->prev->hasData()) { // Первый день. События не создаём.
                $this->insertTownIds[] = $town->id;
                return;
            }

            // По внешнему условию, сюда попадаем только если СЕГОДНЯ город есть
            // ВЧЕРА этого города не было
            if (!$this->prev->hasTown($town->id)
                && !$this->towns->get($town->id) // В базе тоже проверим наличие
            ) {
                $this->insertTownIds[] = $town->id;
                // Если город не варварский, т.е.
                // оварварился в промежутке между считыванием статистики
                // или изначально появился варваром (для варваров не создаём события)
                if ($town->isNotBarbarian()) {
                    $this->push(Wofh::EVENT_TOWN_CREATE, [
                        EventProcessor::TABLE_TOWN_ID    => $town->id,
                        EventProcessor::TABLE_ACCOUNT_ID => $town->account_id,
                        EventProcessor::TABLE_COUNTRY_ID => $this->curr->getCountryIdForAccount($town->account_id),
                    ]);
                }
            }
        }
    }

    private function checkEventTownDestroy(int $townId, ?Town $town)
    {
        if (!$this->prev->hasData()) return; // Первый день. События не создаём.

        // Вчера этот город был, а сегодня его нет
        if (
            $this->prev->hasTown($townId)
            && !$this->curr->hasTown($townId)
        ) {
            $this->destroyedTownIds[] = $townId;

            if ($town) {

                $town->setDeltaPop($town->pop * -1);
                $this->curr->towns->put($townId, $town);

                $this->push(Wofh::EVENT_TOWN_DESTROY, [
                    EventProcessor::TABLE_TOWN_ID    => $townId,
                    EventProcessor::TABLE_ACCOUNT_ID => $town->account_id,
                    EventProcessor::TABLE_COUNTRY_ID => $this->prev->getCountryIdForAccount($town->account_id),
                ]);
            }
        }
    }

    private function checkEventTownLost(?Town $townPrev, Town $town)
    {
        if (!$this->prev->hasData()) return; // Первый день. События не создаём.

        if (!$townPrev) return; // В базе отсутствует

        // Вчера принадлежал игроку, а сегодня – варвар
        if (
            $townPrev->isNotBarbarian()
            && $town->isBarbarian()
        ) {
            $this->updateTownIds[] = $town->id;
            $town->account_id = $this->towns->get($town->id)->account_id;
            $this->push(Wofh::EVENT_TOWN_LOST, [
                EventProcessor::TABLE_TOWN_ID    => $town->id,
                EventProcessor::TABLE_ACCOUNT_ID => $town->account_id,
                EventProcessor::TABLE_COUNTRY_ID => $this->prev->getCountryIdForAccount($town->account_id),
            ]);
        }
    }

    private function checkEventTownRename(Town $townPrev, Town $town)
    {
        if ($townPrev->name != $town->name) {
            $this->updateTownIds[] = $town->id;
            $name = DB::getPdo()->quote($town->name);
            $town->setNames(DB::raw("JSON_MERGE_PATCH(`names`, JSON_OBJECT('{$this->time->timestamp}', $name))"));
            $this->push(Wofh::EVENT_TOWN_RENAME, [
                EventProcessor::TABLE_TOWN_ID    => $town->id,
                EventProcessor::TABLE_ACCOUNT_ID => $town->account_id,
                EventProcessor::TABLE_COUNTRY_ID => $this->curr->getCountryIdForAccount($town->account_id),
                EventProcessor::TABLE_PROPS      => [
                    'prev_name' => $townPrev->name,
                    'curr_name' => $town->name,
                ],
            ]);
        }
    }

    private function checkEventWonderCreate(Town $townPrev, Town $town)
    {
        // Сегодня чудо есть
        // И
        // Вчера не было ИЛИ было, но другое
        if ($town->wonderExists() && (
                $townPrev->wonderNotExists()
                || ($townPrev->wonderExists() && ($townPrev->wonderId() != $town->wonderId()))
            )
        ) {
            $this->updateTownIds[] = $town->id;
            $data = makeWonderEvent($town, $this->curr->getCountryIdForAccount($town->account_id));
            $this->push(Wofh::EVENT_WONDER_CREATE, $data);
        }
    }

    private function checkEventWonderDestroy(Town $townPrev, Town $town)
    {
        // Вчера чудо было
        // И
        // Сегодня его нет ИЛИ есть, но другое
        if ($townPrev->wonderExists() && (
                $town->wonderNotExists()
                || ($town->wonderExists() && ($townPrev->wonderId() != $town->wonderId()))
            )
        ) {
            $this->updateTownIds[] = $town->id;
            $data = makeWonderEvent($town, $this->curr->getCountryIdForAccount($town->account_id), $townPrev);
            $this->push(Wofh::EVENT_WONDER_DESTROY, $data);
        }
    }

    private function checkEventWonderActivate(Town $townPrev, Town $town)
    {
        if ($town->wonderExists()              // Чудо есть
            && $town->wonderActivated()        // Сегодня активировано (21)
            && $townPrev->wonderNotActivated() // Вчера не было активировано (<21)
        ) {
            $this->updateTownIds[] = $town->id;
            $data = makeWonderEvent($town, $this->curr->getCountryIdForAccount($town->account_id));
            $this->push(Wofh::EVENT_WONDER_ACTIVATE, $data);
        }
    }
}

function makeWonderEvent(Town $town, ?int $countryId, ?Town $townPrev = null): array
{
    $townPrev = $townPrev ?? $town;
    return [
        EventProcessor::TABLE_TOWN_ID    => $town->id,
        EventProcessor::TABLE_ACCOUNT_ID => $town->account_id,
        EventProcessor::TABLE_COUNTRY_ID => $countryId,
        EventProcessor::TABLE_PROPS      => [
            'wonder_id'    => $townPrev->wonderId(),
            'wonder_level' => $townPrev->wonderLevel(),
        ],
    ];
}
