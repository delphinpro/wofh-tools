<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
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
                $prevAccount = $this->prev->getAccount($id);
                $currAccount = $this->curr->getAccount($id);
                $currAccount->setDeltaPop($currAccount->pop - $prevAccount->pop);
                $currAccount->setDeltaTowns($currAccount->towns - $prevAccount->towns);
                $currAccount->setDeltaAttack($currAccount->attack - $prevAccount->attack);
                $currAccount->setDeltaDefence($currAccount->defense - $prevAccount->defense);
                $currAccount->setDeltaScience($currAccount->science - $prevAccount->science);
                $currAccount->setDeltaProduction($currAccount->production - $prevAccount->production);

                $this->checkEventsAccountRename($prevAccount, $currAccount);
                $this->checkEventsAccountCountry($prevAccount, $currAccount);
                $this->checkEventsAccountRating($prevAccount, $currAccount);
            }
        }

        $this->console->line('Check events of accounts : '.t($time).'s');
    }

    private function checkEventsAccountCreate(int $accountId)
    {
        // СЕГОДНЯ аккаунт есть
        if ($this->curr->hasAccount($accountId)) {
            ($account = $this->curr->getAccount($accountId))
                ->setDeltaPop($account->pop)
                ->setDeltaTowns($account->towns)
                ->setDeltaScience($account->science)
                ->setDeltaProduction($account->production)
                ->setDeltaAttack($account->attack)
                ->setDeltaDefence($account->defense);

            if (!$this->prev->hasData()) { // Первый день. События не создаём.
                $this->insertAccountIds[] = $accountId;
                return;
            }

            // Вчера аккаунта не было, а сегодня есть
            if (!$this->prev->hasAccount($accountId)) {
                $this->insertAccountIds[] = $accountId;

                $this->push(Wofh::EVENT_ACCOUNT_CREATE, [
                    static::TABLE_ACCOUNT_ID => $accountId,
                    static::TABLE_COUNTRY_ID => $this->curr->getCountryIdForAccount($accountId),
                ]);
            }
        }
    }

    private function checkEventsAccountDelete(int $accountId)
    {
        if (!$this->prev->hasData()) return; // Первый день. События не создаём.

        // Вчера аккаунт был, а сегодня его нет
        if (
            $this->prev->hasAccount($accountId)
            && !$this->curr->hasAccount($accountId)
        ) {
            $this->curr->accounts->put($accountId,
                ($account = $this->prev->getAccount($accountId))
                    ->setDeltaPop(-$account->pop)
                    ->setDeltaTowns(-$account->towns)
                    ->setDeltaScience(-$account->science)
                    ->setDeltaProduction(-$account->production)
                    ->setDeltaAttack(-$account->attack)
                    ->setDeltaDefence(-$account->defense)
            );

            $this->deleteAccountIds[] = $accountId;
            $this->push(Wofh::EVENT_ACCOUNT_DELETE, [
                static::TABLE_ACCOUNT_ID => $accountId,
                static::TABLE_COUNTRY_ID => $this->prev->getCountryIdForAccount($accountId),
            ]);
        }
    }

    private function checkEventsAccountRename(Account $prevAccount, Account $account)
    {
        if ($prevAccount->name != $account->name) {
            $this->updateAccountIds[] = $account->id;
            $account->mergeJsonField('names', [$this->time->timestamp => $account->name]);
            $this->push(Wofh::EVENT_ACCOUNT_RENAME, [
                static::TABLE_ACCOUNT_ID => $account->id,
                static::TABLE_COUNTRY_ID => $account->country_id,
                static::TABLE_PROPS      => [
                    'prevName' => $prevAccount->name,
                    'currName' => $account->name,
                ],
            ]);
        }
    }

    private function checkEventsAccountCountry(Account $prevAccount, Account $account)
    {
        // Страна сменилась
        if ($prevAccount->country_id != $account->country_id) {
            $this->updateAccountIds[] = $account->id;
            // Вчера был вне страны
            if (!$prevAccount->inCountry()) {
                $account->mergeJsonField('countries', [$this->time->timestamp => $account->country_id]);
                $this->push(Wofh::EVENT_ACCOUNT_COUNTRY_IN, [
                    static::TABLE_ACCOUNT_ID => $account->id,
                    static::TABLE_COUNTRY_ID => $account->country_id,
                ]);
                return;
            }

            // Сегодня вне страны
            if (!$account->inCountry()) {
                $account->mergeJsonField('countries', [$this->time->timestamp => null]);
                $this->push(Wofh::EVENT_ACCOUNT_COUNTRY_OUT, [
                    static::TABLE_ACCOUNT_ID      => $account->id,
                    static::TABLE_COUNTRY_ID_FROM => $prevAccount->country_id,
                ]);
                return;
            }

            // И вчера и сегодня в стране, но в другой
            $account->mergeJsonField('countries', [$this->time->timestamp => $account->country_id]);
            $this->push(Wofh::EVENT_ACCOUNT_COUNTRY_CHANGE, [
                static::TABLE_ACCOUNT_ID      => $account->id,
                static::TABLE_COUNTRY_ID      => $account->country_id,
                static::TABLE_COUNTRY_ID_FROM => $prevAccount->country_id,
            ]);
        }
    }

    private function checkEventsAccountRating(Account $prevAccount, Account $account)
    {
        // Нулевая сумма рейтингов означает, что рейтинг скрыт

        // Вчера рейтинг нулевой, сегодня нет
        if ($prevAccount->isHiddenRating() && $account->isShownRating()) {
            $this->push(Wofh::EVENT_ACCOUNT_RATING_SHOW, [
                static::TABLE_ACCOUNT_ID => $account->id,
                static::TABLE_COUNTRY_ID => $account->country_id,
            ]);
        }

        // Вчера рейтинг  был не нулевой, сегодня по нулям
        if ($prevAccount->isShownRating() && $account->isHiddenRating()) {
            $this->push(Wofh::EVENT_ACCOUNT_RATING_HIDE, [
                static::TABLE_ACCOUNT_ID => $account->id,
                static::TABLE_COUNTRY_ID => $account->country_id,
            ]);
        }
    }
}
