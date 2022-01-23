<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\EventProcessor;

use App\Console\Statistic\Data\Account;
use App\Services\Wofh;

/**
 * Trait EventsAccounts
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\Data\DataStorage curr
 * @property \App\Console\Statistic\Data\DataStorage prev
 * @property array insertAccountIds
 * @property array updateAccountIds
 * @property array deleteAccountIds
 */
trait EventsAccounts
{
    public function checkEventsOfAccounts()
    {
        $time = microtime(true);
        $ids = $this->prev->accounts->keys()->merge($this->curr->accounts->keys())->unique();

        foreach ($ids as $id) {
            $this->checkEventsAccountCreate($id);
            $this->checkEventsAccountDelete($id);
            if ($this->prev->hasAccount($id) && $this->curr->hasAccount($id)) {
                $accountPrev = $this->prev->getAccount($id);
                $accountCurr = $this->curr->getAccount($id);
                // $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_POP] = $accountCurr[static::ACCOUNT_KEY_POP] - $accountPrev[static::ACCOUNT_KEY_POP];
                // $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_TOWNS] = $accountCurr[static::ACCOUNT_KEY_TOWNS] - $accountPrev[static::ACCOUNT_KEY_TOWNS];
                // $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_ATTACK] = $accountCurr[static::ACCOUNT_KEY_RATING_ATTACK] - $accountPrev[static::ACCOUNT_KEY_RATING_ATTACK];
                // $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_DEFENSE] = $accountCurr[static::ACCOUNT_KEY_RATING_DEFENSE] - $accountPrev[static::ACCOUNT_KEY_RATING_DEFENSE];
                // $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_SCIENCE] = $accountCurr[static::ACCOUNT_KEY_RATING_SCIENCE] - $accountPrev[static::ACCOUNT_KEY_RATING_SCIENCE];
                // $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_PRODUCTION] = $accountCurr[static::ACCOUNT_KEY_RATING_PRODUCTION] - $accountPrev[static::ACCOUNT_KEY_RATING_PRODUCTION];
                $this->checkEventsAccountRename($accountPrev, $accountCurr);
                $this->checkEventsAccountCountry($accountPrev, $accountCurr);
                $this->checkEventsAccountRating($accountPrev, $accountCurr);
            }
        }

        $this->console->line('Check events of accounts : '.t($time).'s');
        // $this->console->line('              created   : '.count($this->events[Wofh::EVENT_ACCOUNT_CREATE]));
        // $this->console->line('              deleted   : '.count($this->events[Wofh::EVENT_ACCOUNT_DELETE]));
        // $this->console->line('              renamed   : '.count($this->events[Wofh::EVENT_TOWN_RENAME]));
        // $this->console->line('              lost      : '.count($this->events[Wofh::EVENT_TOWN_LOST]));
    }

    private function checkEventsAccountCreate(int $accountId)
    {
        if (!$this->prev->hasData()) {
            $this->insertAccountIds[] = $accountId;
            return;
        }

        // Вчера аккаунта не было, а сегодня есть
        if (
            !$this->prev->hasAccount($accountId)
            && $this->curr->hasAccount($accountId)
        ) {
            $this->insertAccountIds[] = $accountId;
            // $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_POP] = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_POP];
            // $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_TOWNS] = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_TOWNS];
            // $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_ATTACK] = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_RATING_ATTACK];
            // $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_DEFENSE] = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_RATING_DEFENSE];
            // $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_SCIENCE] = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_RATING_SCIENCE];
            // $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_PRODUCTION] = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_RATING_PRODUCTION];
            $this->events[Wofh::EVENT_ACCOUNT_CREATE][$accountId] = [
                static::TABLE_TOWN_ID         => 0,
                static::TABLE_ACCOUNT_ID      => $accountId,
                static::TABLE_COUNTRY_ID      => $this->curr->getAccount($accountId)->country_id,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_PROPS           => null,
            ];
        }
    }

    private function checkEventsAccountDelete(int $accountId)
    {
        if (!$this->prev->hasData()) return;

        // Вчера аккаунт был, а сегодня его нет
        if (
            $this->prev->hasAccount($accountId)
            && !$this->curr->hasAccount($accountId)
        ) {
            $this->deleteAccountIds[] = $accountId;
            $this->events[Wofh::EVENT_ACCOUNT_DELETE][$accountId] = [
                static::TABLE_TOWN_ID         => 0,
                static::TABLE_ACCOUNT_ID      => $accountId,
                static::TABLE_COUNTRY_ID      => $this->prev->getAccount($accountId)->country_id,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_PROPS           => null,
            ];
        }
    }

    private function checkEventsAccountRename(Account $accountPrev, Account $account)
    {
        if ($accountPrev->name != $account->name) {
            $this->updateAccountIds[] = $account->id;
            $this->events[Wofh::EVENT_ACCOUNT_RENAME][$account->id] = [
                static::TABLE_TOWN_ID         => 0,
                static::TABLE_ACCOUNT_ID      => $account->id,
                static::TABLE_COUNTRY_ID      => $account->country_id,
                static::TABLE_COUNTRY_ID_FROM => 0,
                static::TABLE_ROLE            => 0,
                static::TABLE_PROPS => [
                    'prevName' => $accountPrev->name,
                    'currName' => $account->name,
                ],
            ];
        }
    }

    private function checkEventsAccountCountry(Account $accountPrev, Account $account)
    {
        if ($accountPrev->country_id != $account->country_id) {
            $this->updateAccountIds[] = $account->id;
            if ($accountPrev->country_id == 0) {
                $this->events[Wofh::EVENT_ACCOUNT_COUNTRY_IN][$account->id] = [
                    static::TABLE_TOWN_ID         => 0,
                    static::TABLE_ACCOUNT_ID      => $account->id,
                    static::TABLE_COUNTRY_ID      => $account->country_id,
                    static::TABLE_COUNTRY_ID_FROM => 0,
                    static::TABLE_ROLE            => 0,
                    static::TABLE_PROPS           => null,
                ];

                return;
            }
            if ($account->country_id == 0) {
                $this->events[Wofh::EVENT_ACCOUNT_COUNTRY_OUT][$account->id] = [
                    static::TABLE_TOWN_ID         => 0,
                    static::TABLE_ACCOUNT_ID      => $account->id,
                    static::TABLE_COUNTRY_ID      => 0,
                    static::TABLE_COUNTRY_ID_FROM => $accountPrev->country_id,
                    static::TABLE_ROLE            => 0,
                    static::TABLE_PROPS           => null,
                ];

                return;
            }
            $this->events[Wofh::EVENT_ACCOUNT_COUNTRY_CHANGE][$account->id] = [
                static::TABLE_TOWN_ID         => 0,
                static::TABLE_ACCOUNT_ID      => $account->id,
                static::TABLE_COUNTRY_ID      => $account->country_id,
                static::TABLE_COUNTRY_ID_FROM => $accountPrev->country_id,
                static::TABLE_ROLE            => 0,
                static::TABLE_PROPS           => null,
            ];
        }
    }

    private function checkEventsAccountRating(Account $accountPrev, Account $account)
    {
        $prevRating = $accountPrev->rating();
        $currRating = $account->rating();

        if ($prevRating != $currRating) {
            if ($prevRating == 0) {
                $this->events[Wofh::EVENT_ACCOUNT_RATING_SHOW][$account->id] = [
                    static::TABLE_TOWN_ID         => 0,
                    static::TABLE_ACCOUNT_ID      => $account->id,
                    static::TABLE_COUNTRY_ID      => $account->country_id,
                    static::TABLE_COUNTRY_ID_FROM => 0,
                    static::TABLE_ROLE            => 0,
                    static::TABLE_PROPS           => null,
                ];

                return;
            }
            if ($currRating == 0) {
                $this->events[Wofh::EVENT_ACCOUNT_RATING_HIDE][$account->id] = [
                    static::TABLE_TOWN_ID         => 0,
                    static::TABLE_ACCOUNT_ID      => $account->id,
                    static::TABLE_COUNTRY_ID      => $account->country_id,
                    static::TABLE_COUNTRY_ID_FROM => 0,
                    static::TABLE_ROLE            => 0,
                    static::TABLE_PROPS           => null,
                ];
            }
        }
    }
}
