<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace Dolphin\Commands\Stat;


use Carbon\Carbon;
use Dolphin\Console;
use WofhTools\Models\Worlds;
use Dolphin\DolphinContainer;
use Psr\Container\ContainerInterface;
use Dolphin\Commands\Stat\Traits\DataEvents;
use Dolphin\Commands\Stat\Traits\DataNormalizer;
use Dolphin\Commands\Stat\Traits\DataUpdaterTowns;
use Dolphin\Commands\Stat\Traits\DataPreviousReader;
use Dolphin\Commands\Stat\Traits\DataUpdaterAccounts;
use Dolphin\Commands\Stat\Traits\DataUpdaterCountries;


class DataStorage extends DolphinContainer
{
    use DataNormalizer;
    use DataPreviousReader;
    use DataUpdaterTowns;
    use DataUpdaterAccounts;
    use DataUpdaterCountries;
    use DataEvents;

    const TOWN_KEY_TITLE      = 0;
    const TOWN_KEY_ACCOUNT_ID = 1;
    const TOWN_KEY_POP        = 2;
    const TOWN_KEY_WONDER     = 3;

    const TOWN_KEY_WONDER_ID    = 4;
    const TOWN_KEY_WONDER_LEVEL = 5;
    const TOWN_KEY_COUNTRY_ID   = 6;

    const TOWN_KEY_DELTA_POP = 7;

    const ACCOUNT_KEY_TITLE             = 0;
    const ACCOUNT_KEY_RACE              = 1;
    const ACCOUNT_KEY_SEX               = 2;
    const ACCOUNT_KEY_COUNTRY_ID        = 3;
    const ACCOUNT_KEY_RATING_ATTACK     = 4;
    const ACCOUNT_KEY_RATING_DEFENSE    = 5;
    const ACCOUNT_KEY_RATING_SCIENCE    = 6;
    const ACCOUNT_KEY_RATING_PRODUCTION = 7;
    const ACCOUNT_KEY_ROLE              = 8;

    const ACCOUNT_KEY_POP   = 9;
    const ACCOUNT_KEY_TOWNS = 10;

    const ACCOUNT_KEY_DELTA_POP        = 11;
    const ACCOUNT_KEY_DELTA_TOWNS      = 12;
    const ACCOUNT_KEY_DELTA_ATTACK     = 13;
    const ACCOUNT_KEY_DELTA_DEFENSE    = 14;
    const ACCOUNT_KEY_DELTA_SCIENCE    = 15;
    const ACCOUNT_KEY_DELTA_PRODUCTION = 16;

    const COUNTRY_KEY_TITLE     = 0;
    const COUNTRY_KEY_FLAG      = 1;
    const COUNTRY_KEY_DIPLOMACY = 2;

    const COUNTRY_KEY_POP        = 3;
    const COUNTRY_KEY_ACCOUNTS   = 4;
    const COUNTRY_KEY_TOWNS      = 5;
    const COUNTRY_KEY_SCIENCE    = 6;
    const COUNTRY_KEY_PRODUCTION = 7;
    const COUNTRY_KEY_ATTACK     = 8;
    const COUNTRY_KEY_DEFENSE    = 9;

    const COUNTRY_KEY_DELTA_POP        = 10;
    const COUNTRY_KEY_DELTA_ACCOUNTS   = 11;
    const COUNTRY_KEY_DELTA_TOWNS      = 12;
    const COUNTRY_KEY_DELTA_ATTACK     = 13;
    const COUNTRY_KEY_DELTA_DEFENSE    = 14;
    const COUNTRY_KEY_DELTA_SCIENCE    = 15;
    const COUNTRY_KEY_DELTA_PRODUCTION = 16;

    const TABLE_EVENTS_TOWN_ID         = 'townId';
    const TABLE_EVENTS_ACCOUNT_ID      = 'accountId';
    const TABLE_EVENTS_COUNTRY_ID      = 'countryId';
    const TABLE_EVENTS_COUNTRY_ID_FROM = 'countryIdFrom';
    const TABLE_EVENTS_ROLE            = 'role';
    const TABLE_EVENTS_EXTRA           = 'extra';

    /** @var \Carbon\Carbon */
    private $time;

    /** @var \WofhTools\Models\Worlds */
    private $world;

    /** @var array */
    private $raw;

//    /** @var array */
//    private $towns;
//
//    /** @var array */
//    private $accounts;
//
//    /** @var array */
//    private $countries;

    /** @var array */
    private $curr;
    /** @var array */
    private $prev;

    private $firstInsert;

    private $insertTownIds;
    private $updateTownIds;
    private $lostTownIds;

    private $insertAccountIds;
    private $updateAccountIds;
    private $deleteAccountIds;

    private $insertCountryIds;
    private $updateCountryIds;
    private $deleteCountryIds;

    private $events;

    private $totalAccounts;


    public function __construct(ContainerInterface $container, Worlds $world)
    {
        parent::__construct($container);

        $this->world = $world;
        $this->firstInsert = true;

        $this->insertTownIds = [];
        $this->updateTownIds = [];
        $this->lostTownIds = [];
        $this->insertAccountIds = [];
        $this->updateAccountIds = [];
        $this->deleteAccountIds = [];
        $this->insertCountryIds = [];
        $this->updateCountryIds = [];
        $this->deleteCountryIds = [];
        $this->totalAccounts = 0;
//
//        $this->towns = [];
//        $this->accounts = [];
//        $this->countries = [];

        $this->curr = [
            'towns'     => [],
            'accounts'  => [],
            'countries' => [],
        ];

        $this->prev = [
            'towns'     => [],
            'accounts'  => [],
            'countries' => [],
        ];

        $this->events = [
            EVENT_TOWN_CREATE => [],
            EVENT_TOWN_RENAME => [],
            EVENT_TOWN_LOST   => [],
            //            EVENT_TOWN_DESTROY => [],

            EVENT_ACCOUNT_CREATE         => [],
            EVENT_ACCOUNT_COUNTRY_IN     => [],
            EVENT_ACCOUNT_COUNTRY_OUT    => [],
            EVENT_ACCOUNT_COUNTRY_CHANGE => [],
            EVENT_ACCOUNT_DELETE         => [],
            EVENT_ACCOUNT_RENAME         => [],
            EVENT_ACCOUNT_ROLE_IN        => [],
            EVENT_ACCOUNT_ROLE_OUT       => [],

            EVENT_COUNTRY_CREATE  => [],
            EVENT_COUNTRY_FLAG    => [],
            EVENT_COUNTRY_RENAME  => [],
            EVENT_COUNTRY_DESTROY => [],
            EVENT_COUNTRY_PEACE   => [],
            EVENT_COUNTRY_WAR     => [],

            EVENT_WONDER_DESTROY  => [],
            EVENT_WONDER_CREATE   => [],
            EVENT_WONDER_ACTIVATE => [],
        ];
    }


    public function dump()
    {
        $dump = [
            'curr'             => $this->curr,
            'prev'             => $this->prev,
            'events'           => $this->events,
            'insertTownIds'    => $this->insertTownIds,
            'updateTownIds'    => $this->updateTownIds,
            'insertAccountIds' => $this->insertAccountIds,
            'updateAccountIds' => $this->updateAccountIds,
            'insertCountryIds' => $this->insertCountryIds,
            'updateCountryIds' => $this->updateCountryIds,
        ];

        foreach ($dump['curr']['towns'] as &$town) {
            $town['TITLE'] = $town[static::TOWN_KEY_TITLE];
            $town['ACCOUNT_ID'] = $town[static::TOWN_KEY_ACCOUNT_ID];
            $town['POP'] = $town[static::TOWN_KEY_POP];
            $town['WONDER'] = $town[static::TOWN_KEY_WONDER];
            $town['WONDER_ID'] = $town[static::TOWN_KEY_WONDER_ID];
            $town['WONDER_LEVEL'] = $town[static::TOWN_KEY_WONDER_LEVEL];
            $town['COUNTRY_ID'] = $town[static::TOWN_KEY_COUNTRY_ID];
            $town['DELTA_POP'] = $town[static::TOWN_KEY_DELTA_POP];
            unset(
                $town[static::TOWN_KEY_TITLE],
                $town[static::TOWN_KEY_ACCOUNT_ID],
                $town[static::TOWN_KEY_POP],
                $town[static::TOWN_KEY_WONDER],
                $town[static::TOWN_KEY_WONDER_ID],
                $town[static::TOWN_KEY_WONDER_LEVEL],
                $town[static::TOWN_KEY_COUNTRY_ID]
            );
        }

        $filename = DIR_ROOT.'/.tmp/dump-'.$this->world->id.'-'.$this->getTime()->format('d-m-Y_H-i-s').'.json';
        file_put_contents($filename, json_encode($dump, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }


    /**
     * @param string $filename
     *
     * @throws \WofhTools\Helpers\FileSystemException
     * @throws \WofhTools\Helpers\JsonCustomException
     */
    public function loadFromFile(string $filename): void
    {
//            if ($useZip) {
//                $zip = new \ZipArchive();
//                $zip->open(Helpers\FileSystem::join($realStatPath, $world->lowerSign().'.zip'));
//                $raw = $zip->getFromName($dataFile);
//                $zip->close();
//                $data = new Data($raw, $world);
//            } else {

//            }
        $jsonString = $this->fs->readFile($filename);
        $this->raw = $this->json->decode($jsonString);
        $this->time = Carbon::createFromTimestamp($this->raw['time']);
    }


    /**
     * @return \Carbon\Carbon
     */
    public function getTime()
    {
        return $this->time;
    }


    public function fixTotalAccountsValue()
    {
        $this->totalAccounts = count($this->raw['accounts']);
    }


    public function normalize(string $timePoint = 'curr')
    {
        $time = microtime(true);
        $this->console->writeFixedWidth('Normalization source data', Updater::PRINT_WIDTH);
        $this->normalizeTowns($timePoint);
        $this->normalizeAccounts($timePoint);
        $this->normalizeCountries($timePoint);
        $this->console->write(round(microtime(true) - $time, 3).'s');
    }


    public function calculate(string $timePoint = 'curr')
    {
        $this->console->write('Calculation data');

        // Считаем население и города аккаунтов
        foreach ($this->{$timePoint}['towns'] as $townId => $town) {
            $accountId = $town[DataStorage::TOWN_KEY_ACCOUNT_ID];
            $pop = $town[DataStorage::TOWN_KEY_POP];
            $this->{$timePoint}['accounts'][$accountId][DataStorage::ACCOUNT_KEY_POP] += $pop;
            $this->{$timePoint}['accounts'][$accountId][DataStorage::ACCOUNT_KEY_TOWNS] += 1;
        }

        // Считаем население, аккаунты и города стран
        foreach ($this->{$timePoint}['accounts'] as $accountId => $account) {
            $countryId = $account[DataStorage::ACCOUNT_KEY_COUNTRY_ID];

            if (!$countryId) {
                continue; // Аккаунт вне страны, пропуск.
            }

            $pop = $account[DataStorage::ACCOUNT_KEY_POP];
            $towns = $account[DataStorage::ACCOUNT_KEY_TOWNS];
            $science = $account[DataStorage::ACCOUNT_KEY_RATING_SCIENCE];
            $production = $account[DataStorage::ACCOUNT_KEY_RATING_PRODUCTION];
            $attack = $account[DataStorage::ACCOUNT_KEY_RATING_ATTACK];
            $defense = $account[DataStorage::ACCOUNT_KEY_RATING_DEFENSE];

            $this->{$timePoint}['countries'][$countryId][DataStorage::COUNTRY_KEY_POP] += $pop;
            $this->{$timePoint}['countries'][$countryId][DataStorage::COUNTRY_KEY_ACCOUNTS] += 1;
            $this->{$timePoint}['countries'][$countryId][DataStorage::COUNTRY_KEY_TOWNS] += $towns;
            $this->{$timePoint}['countries'][$countryId][DataStorage::COUNTRY_KEY_SCIENCE] += $science;
            $this->{$timePoint}['countries'][$countryId][DataStorage::COUNTRY_KEY_PRODUCTION] += $production;
            $this->{$timePoint}['countries'][$countryId][DataStorage::COUNTRY_KEY_ATTACK] += $attack;
            $this->{$timePoint}['countries'][$countryId][DataStorage::COUNTRY_KEY_DEFENSE] += $defense;
        }

    }


    public function filter(string $timePoint = 'curr')
    {
        $this->{$timePoint}['accounts'] = array_filter($this->{$timePoint}['accounts'], function ($account) {
            return $account[DataStorage::ACCOUNT_KEY_TOWNS] != 0
                && $account[DataStorage::ACCOUNT_KEY_POP] != 0;
        });

        $this->{$timePoint}['countries'] = array_filter($this->{$timePoint}['countries'], function ($country) {
            return $country[DataStorage::COUNTRY_KEY_ACCOUNTS] != 0
                && $country[DataStorage::COUNTRY_KEY_TOWNS] != 0
                && $country[DataStorage::COUNTRY_KEY_POP] != 0;
        });
    }


    public function readPreviousIndex($filename)
    {
//        $lastDate = $this->getLastDate();

        if (!$filename) {

            return;
        }

        $jsonString = $this->fs->readFile($filename);
        $this->raw = $this->json->decode($jsonString);
        $time = Carbon::createFromTimestamp($this->raw['time']);
        $this->firstInsert = false;

        $this->console->write($time->format('d-m-Y H:i:s [P]'), Console::YELLOW);

        $time = microtime(true);
//        $this->readPreviousIndexOfTowns($lastDate);
//        $this->readPreviousIndexOfAccounts($lastDate);
//        $this->readPreviousIndexOfCountries($lastDate);

//        $this->console->writeFixedWidth('       towns prev/cur/ins/upd', Updater::PRINT_WIDTH);
//        $this->console->write(
//            count($this->prev['towns'])
//            .' / '.count($this->towns)
//            .' / '.count($this->insertTownIds)
//            .' / '.count($this->updateTownIds)
//        );
//
//        $this->console->writeFixedWidth('    accounts prev/cur/ins/upd', Updater::PRINT_WIDTH);
//        $this->console->write(
//            count($this->prev['accounts'])
//            .' / '.count($this->accounts)
//            .' / '.count($this->insertAccountIds)
//            .' / '.count($this->updateAccountIds)
//        );
//
//        $this->console->writeFixedWidth('   countries prev/cur/ins/upd', Updater::PRINT_WIDTH);
//        $this->console->write(
//            count($this->prev['countries'])
//            .' / '.count($this->countries)
//            .' / '.count($this->insertCountryIds)
//            .' / '.count($this->updateCountryIds)
//        );


        $this->console->writeFixedWidth('    complete', Updater::PRINT_WIDTH);
        $this->console->write(round(microtime(true) - $time, 3).'s');
    }


    public function update()
    {
        $this->console->write('Update data');
        $allTime = microtime(true);

        $time = microtime(true);
        $this->updateTowns();
        $this->console->writeFixedWidth('    towns:', Updater::PRINT_WIDTH, ' ');
        $this->console->write(round(microtime(true) - $time, 3).'s');

        $time = microtime(true);
        $this->updateAccounts();
        $this->console->writeFixedWidth('    accounts:', Updater::PRINT_WIDTH, ' ');
        $this->console->write(round(microtime(true) - $time, 3).'s');

        $time = microtime(true);
        $this->updateCountries();
        $this->console->writeFixedWidth('    countries:', Updater::PRINT_WIDTH, ' ');
        $this->console->write(round(microtime(true) - $time, 3).'s');

        $time = microtime(true);
        $this->updateEvents();
        $this->console->writeFixedWidth('       events:', Updater::PRINT_WIDTH, ' ');
        $this->console->write(round(microtime(true) - $time, 3).'s');

        $time = microtime(true);
        $this->updateCommonData();
        $this->console->writeFixedWidth('    common:', Updater::PRINT_WIDTH, ' ');
        $this->console->write(round(microtime(true) - $time, 3).'s');

        $this->console->writeFixedWidth('    complete', Updater::PRINT_WIDTH);
        $this->console->write(round(microtime(true) - $allTime, 3).'s');
    }


    public function checkEventsOfTowns()
    {
        $this->console->write('Check events of towns', Updater::PRINT_WIDTH);
        $idsPrev = array_keys($this->prev['towns']);
        $idsCurr = array_keys($this->curr['towns']);
        $ids = array_unique(array_merge($idsPrev, $idsCurr));

        foreach ($ids as $id) {
            $this->checkEventTownCreate($id);
            $this->checkEventTownLost($id);
            if (array_key_exists($id, $this->prev['towns'])
                && array_key_exists($id, $this->curr['towns'])
            ) {
                $a1 = $this->prev['towns'][$id];
                $a2 = $this->curr['towns'][$id];
                $this->curr['towns'][$id][static::TOWN_KEY_DELTA_POP] =
                    $a2[static::TOWN_KEY_POP] - $a1[static::TOWN_KEY_POP];
                $this->checkEventTownRename($a1, $a2, $id);
                $this->checkEventWonder($a1, $a2, $id);
            }
        }

        $this->console->write('    created: '.count($this->events[EVENT_TOWN_CREATE]));
        $this->console->write('    renamed: '.count($this->events[EVENT_TOWN_RENAME]));
        $this->console->write('    lost   : '.count($this->events[EVENT_TOWN_LOST]));
    }


    public function checkEventsOfAccounts()
    {
        $this->console->write('Check events of accounts', Updater::PRINT_WIDTH);
        $idsPrev = array_keys($this->prev['accounts']);
        $idsCurr = array_keys($this->curr['accounts']);
        $ids = array_unique(array_merge($idsPrev, $idsCurr));

        foreach ($ids as $id) {
            $this->checkEventsAccountCreate($id);
            $this->checkEventsAccountDelete($id);
            if (array_key_exists($id, $this->prev['accounts'])
                && array_key_exists($id, $this->curr['accounts'])
            ) {
                $a1 = $this->prev['accounts'][$id];
                $a2 = $this->curr['accounts'][$id];
                $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_POP] =
                    $a2[static::ACCOUNT_KEY_POP] - $a1[static::ACCOUNT_KEY_POP];
                $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_TOWNS] =
                    $a2[static::ACCOUNT_KEY_TOWNS] - $a1[static::ACCOUNT_KEY_TOWNS];
                $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_ATTACK] =
                    $a2[static::ACCOUNT_KEY_RATING_ATTACK] - $a1[static::ACCOUNT_KEY_RATING_ATTACK];
                $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_DEFENSE] =
                    $a2[static::ACCOUNT_KEY_RATING_DEFENSE] - $a1[static::ACCOUNT_KEY_RATING_DEFENSE];
                $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_SCIENCE] =
                    $a2[static::ACCOUNT_KEY_RATING_SCIENCE] - $a1[static::ACCOUNT_KEY_RATING_SCIENCE];
                $this->curr['accounts'][$id][static::ACCOUNT_KEY_DELTA_PRODUCTION] =
                    $a2[static::ACCOUNT_KEY_RATING_PRODUCTION] - $a1[static::ACCOUNT_KEY_RATING_PRODUCTION];
                $this->checkEventsAccountRename($a1, $a2, $id);
                $this->checkEventsAccountCountry($a1, $a2, $id);
                $this->checkEventsAccountRating($a1, $a2, $id);
            }
        }

        $this->console->write('    created: '.count($this->events[EVENT_ACCOUNT_CREATE]));
        $this->console->write('    deleted: '.count($this->events[EVENT_ACCOUNT_DELETE]));
//        $this->console->write('    renamed: '.count($this->events[EVENT_TOWN_RENAME]));
//        $this->console->write('    lost   : '.count($this->events[EVENT_TOWN_LOST]));
    }


    public function checkEventsOfCountries()
    {
        $this->console->write('Check events of countries', Updater::PRINT_WIDTH);
        $idsPrev = array_keys($this->prev['countries']);
        $idsCurr = array_keys($this->curr['countries']);
        $ids = array_unique(array_merge($idsPrev, $idsCurr));
        foreach ($ids as $id) {
            $this->checkEventsCountryCreate($id);
            $this->checkEventsCountryDelete($id);
            if (array_key_exists($id, $this->prev['countries'])
                && array_key_exists($id, $this->curr['countries'])
            ) {
                $a1 = $this->prev['countries'][$id];
                $a2 = $this->curr['countries'][$id];
                $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_POP] =
                    $a2[static::COUNTRY_KEY_POP] - $a1[static::COUNTRY_KEY_POP];
                $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_ACCOUNTS] =
                    $a2[static::COUNTRY_KEY_ACCOUNTS] - $a1[static::COUNTRY_KEY_ACCOUNTS];
                $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_TOWNS] =
                    $a2[static::COUNTRY_KEY_TOWNS] - $a1[static::COUNTRY_KEY_TOWNS];
                $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_ATTACK] =
                    $a2[static::COUNTRY_KEY_ATTACK] - $a1[static::COUNTRY_KEY_ATTACK];
                $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_DEFENSE] =
                    $a2[static::COUNTRY_KEY_DEFENSE] - $a1[static::COUNTRY_KEY_DEFENSE];
                $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_SCIENCE] =
                    $a2[static::COUNTRY_KEY_SCIENCE] - $a1[static::COUNTRY_KEY_SCIENCE];
                $this->curr['countries'][$id][static::COUNTRY_KEY_DELTA_PRODUCTION] =
                    $a2[static::COUNTRY_KEY_PRODUCTION] - $a1[static::COUNTRY_KEY_PRODUCTION];
                $this->checkEventsCountryRename($a1, $a2, $id);
                $this->checkEventsCountryFlag($a1, $a2, $id);
            }
        }
    }


    public function unsetRaw()
    {
        $this->raw = [];
    }


    private function updateCommonData()
    {
        $table = $this->db->table("z_{$this->world->sign}_common");
        $table->insert([
            'stateAt'               => $this->time,
            'townsTotal'            => count($this->curr['towns']),
            'townsNew'              => count($this->events[EVENT_TOWN_CREATE]),
            'townsRenamed'          => count($this->events[EVENT_TOWN_RENAME]),
            'townsLost'             => count($this->events[EVENT_TOWN_LOST]),
            'wondersNew'            => count($this->events[EVENT_WONDER_CREATE]),
            'wondersDestroy'        => count($this->events[EVENT_WONDER_DESTROY]),
            'wondersActivate'       => count($this->events[EVENT_WONDER_ACTIVATE]),
            'accountsTotal'         => $this->totalAccounts,
            'accountsActive'        => 0,
            'accountsRace0'         => 0,
            'accountsRace1'         => 0,
            'accountsRace2'         => 0,
            'accountsRace3'         => 0,
            'accountsSex0'          => 0,
            'accountsSex1'          => 0,
            'accountsNew'           => count($this->events[EVENT_ACCOUNT_CREATE]),
            'accountsCountryChange' => count($this->events[EVENT_ACCOUNT_COUNTRY_CHANGE]),
            'accountsCountryIn'     => count($this->events[EVENT_ACCOUNT_COUNTRY_IN]),
            'accountsCountryOut'    => count($this->events[EVENT_ACCOUNT_COUNTRY_OUT]),
            'accountsDeleted'       => count($this->events[EVENT_ACCOUNT_DELETE]),
            'accountsRenamed'       => count($this->events[EVENT_ACCOUNT_RENAME]),
            'accountsRoleIn'        => count($this->events[EVENT_ACCOUNT_ROLE_IN]),
            'accountsRoleOut'       => count($this->events[EVENT_ACCOUNT_ROLE_OUT]),
            'countriesTotal'        => count($this->curr['countries']),
            'countriesNew'          => count($this->events[EVENT_COUNTRY_CREATE]),
            'countriesRenamed'      => count($this->events[EVENT_COUNTRY_RENAME]),
            'countriesFlag'         => count($this->events[EVENT_COUNTRY_FLAG]),
            'countriesDeleted'      => count($this->events[EVENT_COUNTRY_DESTROY]),
        ]);
    }


    public function calculateDeltas()
    {
    }
}
