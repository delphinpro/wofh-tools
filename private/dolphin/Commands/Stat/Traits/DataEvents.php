<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace Dolphin\Commands\Stat\Traits;


/**
 * Trait DataEvents
 *
 * @package Dolphin\Commands\Stat\Traits
 *
 * @property \Illuminate\Database\Connection db
 * @property \WofhTools\Models\Worlds        world
 */
trait DataEvents
{
    private function checkEventTownCreate(int $townId)
    {
        // Вчера этого города не было, а сегодня есть
        if (!array_key_exists($townId, $this->prev['towns'])
            && array_key_exists($townId, $this->curr['towns'])
        ) {
            $this->insertTownIds[] = $townId;
            $this->curr['towns'][$townId][static::TOWN_KEY_DELTA_POP] =
                $this->curr['towns'][$townId][static::TOWN_KEY_POP];
            $accountId = $this->curr['towns'][$townId][static::TOWN_KEY_ACCOUNT_ID];
            $countryId = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_COUNTRY_ID];

            $this->events[EVENT_TOWN_CREATE][$townId] = [
                static::TABLE_EVENTS_TOWN_ID         => $townId,
                static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => null,
            ];
        }
    }


    private function checkEventTownLost(int $townId)
    {
        // Сегодня этого города нет
        if (array_key_exists($townId, $this->prev['towns'])
            && !array_key_exists($townId, $this->curr['towns'])
        ) {
            $this->lostTownIds[] = $townId;
//            $this->curr['towns'][$townId][static::TOWN_KEY_DELTA_POP] = $this->curr['towns'][$townId][static::TOWN_KEY_POP];
            $accountId = $this->prev['towns'][$townId][static::TOWN_KEY_ACCOUNT_ID];
            $countryId = $this->prev['accounts'][$accountId][static::ACCOUNT_KEY_COUNTRY_ID];
            $this->events[EVENT_TOWN_LOST][$townId] = [
                static::TABLE_EVENTS_TOWN_ID         => $townId,
                static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => null,
            ];
        }
    }


    private function checkEventTownRename($town1, $town2, int $townId)
    {
        if ($town1[static::TOWN_KEY_TITLE] != $town2[static::TOWN_KEY_TITLE]) {
            $this->updateTownIds[] = $townId;
            $accountId = $this->curr['towns'][$townId][static::TOWN_KEY_ACCOUNT_ID];
            $countryId = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_COUNTRY_ID];
            $this->events[EVENT_TOWN_RENAME][$townId] = [
                static::TABLE_EVENTS_TOWN_ID         => $townId,
                static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => [
                    'prevTitle' => $town1[static::TOWN_KEY_TITLE],
                    'currTitle' => $town2[static::TOWN_KEY_TITLE],
                ],
            ];
        }
    }


    private function checkEventWonder($town1, $town2, int $id)
    {
        $accountId = $this->curr['towns'][$id][static::TOWN_KEY_ACCOUNT_ID];
        $countryId = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_COUNTRY_ID];
        $eventData = [
            static::TABLE_EVENTS_TOWN_ID         => $id,
            static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
            static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
            static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
            static::TABLE_EVENTS_ROLE            => 0,
        ];

        if ($town1[static::TOWN_KEY_WONDER_ID] != $town2[static::TOWN_KEY_WONDER_ID]) {
            if ($town1[static::TOWN_KEY_WONDER_ID] == 0) {
                $eventData[static::TABLE_EVENTS_EXTRA] = [
                    'wonderId'    => $town2[static::TOWN_KEY_WONDER_ID],
                    'wonderLevel' => $town2[static::TOWN_KEY_WONDER_LEVEL],
                ];
                $this->events[EVENT_WONDER_CREATE][] = $eventData;
            }
            if ($town2[static::TOWN_KEY_WONDER_ID] == 0) {
                $eventData[static::TABLE_EVENTS_EXTRA] = [
                    'wonderId'    => $town1[static::TOWN_KEY_WONDER_ID],
                    'wonderLevel' => $town1[static::TOWN_KEY_WONDER_LEVEL],
                ];
                $this->events[EVENT_WONDER_DESTROY][] = $eventData;
            }
        }
        if ($town2[static::TOWN_KEY_WONDER_ID] > 0
            && $town2[static::TOWN_KEY_WONDER_LEVEL] > 20
            && $town1[static::TOWN_KEY_WONDER_LEVEL] < 21
        ) {
            $eventData[static::TABLE_EVENTS_EXTRA] = [
                'wonderId'    => $town2[static::TOWN_KEY_WONDER_ID],
                'wonderLevel' => $town2[static::TOWN_KEY_WONDER_LEVEL],
            ];
            $this->events[EVENT_WONDER_ACTIVATE][] = $eventData;
        }
    }


    private function checkEventsAccountCreate(int $accountId)
    {
        if (!array_key_exists($accountId, $this->prev['accounts'])
            && array_key_exists($accountId, $this->curr['accounts'])
        ) {
            $this->insertAccountIds[] = $accountId;
            $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_POP] =
                $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_POP];
            $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_TOWNS] =
                $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_TOWNS];
            $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_ATTACK] =
                $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_RATING_ATTACK];
            $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_DEFENSE] =
                $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_RATING_DEFENSE];
            $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_SCIENCE] =
                $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_RATING_SCIENCE];
            $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_DELTA_PRODUCTION] =
                $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_RATING_PRODUCTION];
            $countryId = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_COUNTRY_ID];
            $this->events[EVENT_ACCOUNT_CREATE][$accountId] = [
                static::TABLE_EVENTS_TOWN_ID         => 0,
                static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => null,
            ];
        }
    }


    private function checkEventsAccountDelete(int $accountId)
    {
        if (array_key_exists($accountId, $this->prev['accounts'])
            && !array_key_exists($accountId, $this->curr['accounts'])
        ) {
            $this->deleteAccountIds[] = $accountId;
            $countryId = $this->prev['accounts'][$accountId][static::ACCOUNT_KEY_COUNTRY_ID];
            $this->events[EVENT_ACCOUNT_DELETE][$accountId] = [
                static::TABLE_EVENTS_TOWN_ID         => 0,
                static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => null,
            ];
        }
    }


    private function checkEventsAccountRename($acc1, $acc2, $accountId)
    {
        if ($acc1[static::ACCOUNT_KEY_TITLE] != $acc2[static::ACCOUNT_KEY_TITLE]) {
            $this->updateAccountIds[] = $accountId;
            $countryId = $this->curr['accounts'][$accountId][static::ACCOUNT_KEY_COUNTRY_ID];
            $this->events[EVENT_ACCOUNT_RENAME][$accountId] = [
                static::TABLE_EVENTS_TOWN_ID         => 0,
                static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => [
                    'prevTitle' => $acc1[static::ACCOUNT_KEY_TITLE],
                    'currTitle' => $acc2[static::ACCOUNT_KEY_TITLE],
                ],
            ];
        }
    }


    private function checkEventsAccountCountry($acc1, $acc2, $accountId)
    {
        $prevCountryId = $acc1[static::ACCOUNT_KEY_COUNTRY_ID];
        $currCountryId = $acc2[static::ACCOUNT_KEY_COUNTRY_ID];
        if ($prevCountryId != $currCountryId) {
            $this->updateAccountIds[] = $accountId;
            if ($prevCountryId == 0) {
                $this->events[EVENT_ACCOUNT_COUNTRY_IN][$accountId] = [
                    static::TABLE_EVENTS_TOWN_ID         => 0,
                    static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                    static::TABLE_EVENTS_COUNTRY_ID      => $currCountryId,
                    static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                    static::TABLE_EVENTS_ROLE            => 0,
                    static::TABLE_EVENTS_EXTRA           => null,
                ];

                return;
            }
            if ($currCountryId == 0) {
                $this->events[EVENT_ACCOUNT_COUNTRY_OUT][$accountId] = [
                    static::TABLE_EVENTS_TOWN_ID         => 0,
                    static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                    static::TABLE_EVENTS_COUNTRY_ID      => 0,
                    static::TABLE_EVENTS_COUNTRY_ID_FROM => $prevCountryId,
                    static::TABLE_EVENTS_ROLE            => 0,
                    static::TABLE_EVENTS_EXTRA           => null,
                ];

                return;
            }
            $this->events[EVENT_ACCOUNT_COUNTRY_CHANGE][$accountId] = [
                static::TABLE_EVENTS_TOWN_ID         => 0,
                static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                static::TABLE_EVENTS_COUNTRY_ID      => $currCountryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => $prevCountryId,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => null,
            ];
        }
    }


    private function checkEventsAccountRating($acc1, $acc2, $accountId)
    {
        $currCountryId = $acc2[static::ACCOUNT_KEY_COUNTRY_ID];
        $prevRating = $acc1[static::ACCOUNT_KEY_RATING_SCIENCE]
            + $acc1[static::ACCOUNT_KEY_RATING_PRODUCTION]
            + $acc1[static::ACCOUNT_KEY_RATING_ATTACK]
            + $acc1[static::ACCOUNT_KEY_RATING_DEFENSE];
        $currRating = $acc2[static::ACCOUNT_KEY_RATING_SCIENCE]
            + $acc2[static::ACCOUNT_KEY_RATING_PRODUCTION]
            + $acc2[static::ACCOUNT_KEY_RATING_ATTACK]
            + $acc2[static::ACCOUNT_KEY_RATING_DEFENSE];
        if ($prevRating != $currRating) {
            if ($prevRating == 0) {
                $this->events[EVENT_ACCOUNT_RATING_SHOW][$accountId] = [
                    static::TABLE_EVENTS_TOWN_ID         => 0,
                    static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                    static::TABLE_EVENTS_COUNTRY_ID      => $currCountryId,
                    static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                    static::TABLE_EVENTS_ROLE            => 0,
                    static::TABLE_EVENTS_EXTRA           => null,
                ];

                return;
            }
            if ($currRating == 0) {
                $this->events[EVENT_ACCOUNT_RATING_HIDE][$accountId] = [
                    static::TABLE_EVENTS_TOWN_ID         => 0,
                    static::TABLE_EVENTS_ACCOUNT_ID      => $accountId,
                    static::TABLE_EVENTS_COUNTRY_ID      => $currCountryId,
                    static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                    static::TABLE_EVENTS_ROLE            => 0,
                    static::TABLE_EVENTS_EXTRA           => null,
                ];

                return;
            }
        }
    }


    private function checkEventsCountryCreate(int $countryId)
    {
        if (!array_key_exists($countryId, $this->prev['countries'])
            && array_key_exists($countryId, $this->curr['countries'])
        ) {
            $this->insertCountryIds[] = $countryId;
            $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_POP] =
                $this->curr['countries'][$countryId][static::COUNTRY_KEY_POP];
            $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_ACCOUNTS] =
                $this->curr['countries'][$countryId][static::COUNTRY_KEY_ACCOUNTS];
            $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_TOWNS] =
                $this->curr['countries'][$countryId][static::COUNTRY_KEY_TOWNS];
            $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_ATTACK] =
                $this->curr['countries'][$countryId][static::COUNTRY_KEY_ATTACK];
            $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_DEFENSE] =
                $this->curr['countries'][$countryId][static::COUNTRY_KEY_DEFENSE];
            $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_SCIENCE] =
                $this->curr['countries'][$countryId][static::COUNTRY_KEY_SCIENCE];
            $this->curr['countries'][$countryId][static::COUNTRY_KEY_DELTA_PRODUCTION] =
                $this->curr['countries'][$countryId][static::COUNTRY_KEY_PRODUCTION];
            $this->events[EVENT_COUNTRY_CREATE][$countryId] = [
                static::TABLE_EVENTS_TOWN_ID         => 0,
                static::TABLE_EVENTS_ACCOUNT_ID      => 0,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => null,
            ];
        }
    }


    private function checkEventsCountryDelete(int $countryId)
    {
        if (array_key_exists($countryId, $this->prev['countries'])
            && !array_key_exists($countryId, $this->curr['countries'])
        ) {
            $this->deleteCountryIds[] = $countryId;
            $this->events[EVENT_COUNTRY_DESTROY][$countryId] = [
                static::TABLE_EVENTS_TOWN_ID         => 0,
                static::TABLE_EVENTS_ACCOUNT_ID      => 0,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => null,
            ];
        }
    }


    private function checkEventsCountryRename($country1, $country2, $countryId)
    {
        if ($country1[static::COUNTRY_KEY_TITLE] != $country2[static::COUNTRY_KEY_TITLE]) {
            $this->updateCountryIds[] = $countryId;
            $this->events[EVENT_COUNTRY_RENAME][$countryId] = [
                static::TABLE_EVENTS_TOWN_ID         => 0,
                static::TABLE_EVENTS_ACCOUNT_ID      => 0,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => [
                    'prevTitle' => $country1[static::COUNTRY_KEY_TITLE],
                    'nextTitle' => $country2[static::COUNTRY_KEY_TITLE],
                ],
            ];
        }
    }


    private function checkEventsCountryFlag($country1, $country2, $countryId)
    {
        if ($country1[static::COUNTRY_KEY_FLAG] != $country2[static::COUNTRY_KEY_FLAG]) {
            $extra = [
                'prevFlag' => $country1[static::COUNTRY_KEY_FLAG],
                'currFlag' => $country2[static::COUNTRY_KEY_FLAG],
            ];
            $this->addUpdatedCountry($countryId, $extra);
            $this->events[EVENT_COUNTRY_FLAG][$countryId] = [
                static::TABLE_EVENTS_TOWN_ID         => 0,
                static::TABLE_EVENTS_ACCOUNT_ID      => 0,
                static::TABLE_EVENTS_COUNTRY_ID      => $countryId,
                static::TABLE_EVENTS_COUNTRY_ID_FROM => 0,
                static::TABLE_EVENTS_ROLE            => 0,
                static::TABLE_EVENTS_EXTRA           => $extra,
            ];
        }
    }


    private function addUpdatedCountry($countryId, $data = [])
    {
        if (!array_key_exists($countryId, $this->updateCountryIds)) {
            $this->updateCountryIds[$countryId] = [
                'prevTitle' => null,
                'currTitle' => null,
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


    private function updateEvents()
    {
        $columns = [
            'stateAt',
            'eventId',
            'townId',
            'accountId',
            'countryId',
            'countryIdFrom',
            'role',
            'extra',
        ];

        $e = [
            'TOWN_CREATE' => 100,
            'TOWN_RENAME' => 101,
            'TOWN_LOST'   => 102,

            'ACCOUNT_CREATE'         => 200,
            'ACCOUNT_COUNTRY_IN'     => 201,
            'ACCOUNT_COUNTRY_OUT'    => 202,
            'ACCOUNT_COUNTRY_CHANGE' => 203,
            'ACCOUNT_DELETE'         => 204,
            'ACCOUNT_RENAME'         => 205,
            'ACCOUNT_ROLE_IN'        => 206,
            'ACCOUNT_ROLE_OUT'       => 207,
            'ACCOUNT_RATING_HIDE'    => 208,
            'ACCOUNT_RATING_SHOW'    => 209,

            'COUNTRY_CREATE'  => 300,
            'COUNTRY_FLAG'    => 301,
            'COUNTRY_RENAME'  => 302,
            'COUNTRY_DESTROY' => 303,
            'COUNTRY_PEACE'   => 304,
            'COUNTRY_WAR'     => 305,

            'WONDER_DESTROY'  => 400,
            'WONDER_CREATE'   => 402,
            'WONDER_ACTIVATE' => 403,
        ];

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = "INSERT";
        $sql .= " INTO `z_{$this->world->sign}_events`";
        $sql .= " (`".join('`,`', $columns)."`)";
        $sql .= " VALUES ";


        $pdo = $this->db->getPdo();
        $first = true;

        foreach ($this->events as $eventId => $events) {
            if (!array_key_exists($eventId, $e)) {
                throw new \Exception('Unknown event name: '.$eventId);
            }
            $ev = $e[$eventId];
            foreach ($events as $event) {
                if (!$first) {
                    $sql .= ',';
                } else {
                    $first = false;
                }

                $sql .= "(";
                $sql .= ($pdo->quote($this->time)).",";
                $sql .= (intval($ev)).",";
                $sql .= (intval($event['townId'])).",";
                $sql .= (intval($event['accountId'])).",";
                $sql .= (intval($event['countryId'])).",";
                $sql .= (intval($event['countryIdFrom'])).",";
                $sql .= (intval($event['role'])).",";
                $sql .= ('NULL');
                $sql .= ")";
            }
        }

        // Если есть хоть одно событие
        if (!$first) {
            $this->db->statement($sql);
        }
    }
}
