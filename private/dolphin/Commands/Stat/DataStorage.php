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
use Dolphin\Commands\Stat\Traits\DataNormalizer;
use Dolphin\Commands\Stat\Traits\DataPreviousReader;
use Dolphin\Commands\Stat\Traits\DataUpdaterAccounts;
use Dolphin\Commands\Stat\Traits\DataUpdaterCountries;
use Dolphin\Commands\Stat\Traits\DataUpdaterTowns;
use Dolphin\Console;
use Dolphin\DolphinContainer;
use Psr\Container\ContainerInterface;
use WofhTools\Models\Worlds;


class DataStorage extends DolphinContainer
{
    use DataNormalizer;
    use DataPreviousReader;
    use DataUpdaterTowns;
    use DataUpdaterAccounts;
    use DataUpdaterCountries;


    const TOWN_KEY_TITLE      = 0;
    const TOWN_KEY_ACCOUNT_ID = 1;
    const TOWN_KEY_POP        = 2;
    const TOWN_KEY_WONDER     = 3;

    const TOWN_KEY_WONDER_ID    = 4;
    const TOWN_KEY_WONDER_LEVEL = 5;


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

    /** @var \Carbon\Carbon */
    private $time;

    /** @var \WofhTools\Models\Worlds */
    private $world;

    /** @var array */
    private $raw;

    /** @var array */
    private $towns;

    /** @var array */
    private $accounts;

    /** @var array */
    private $countries;

    /** @var array */
    private $prev;

    private $firstInsert;

    private $insertTownIds;

    private $updateTownIds;

    private $insertAccountIds;

    private $updateAccountIds;

    private $insertCountryIds;

    private $updateCountryIds;


    public function __construct(ContainerInterface $container, Worlds $world)
    {
        parent::__construct($container);

        $this->world = $world;
        $this->firstInsert = false;
        $this->insertTownIds = [];
        $this->updateTownIds = [];
        $this->insertAccountIds = [];
        $this->updateAccountIds = [];
        $this->insertCountryIds = [];
        $this->updateCountryIds = [];

        $this->towns = [];
        $this->accounts = [];
        $this->countries = [];

        $this->prev = [
            'towns'     => [],
            'accounts'  => [],
            'countries' => [],
        ];
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


    public function normalize()
    {
        $time = microtime(true);
        $this->console->writeFixedWidth('Normalization source data', Updater::PRINT_WIDTH);
        $this->normalizeTowns();
        $this->normalizeAccounts();
        $this->normalizeCountries();
        $this->console->write(round(microtime(true) - $time, 3).'s');
    }


    public function readPreviousIndex()
    {
        $this->console->writeFixedWidth('Reading previous data', Updater::PRINT_WIDTH);
        $lastDate = $this->getLastDate();

        if (is_null($lastDate)) {
            $this->firstInsert = true;
            $this->console->write('No previous data', Console::YELLOW);

            $this->insertTownIds = array_keys($this->towns);
            $this->insertAccountIds = array_keys($this->accounts);
            $this->insertCountryIds = array_keys($this->countries);

            return;
        }

        $this->console->write($lastDate->format('d-m-Y H:i:s [P]'), Console::YELLOW);

        $time = microtime(true);
        $this->readPreviousIndexOfTowns($lastDate);
        $this->readPreviousIndexOfAccounts($lastDate);
        $this->readPreviousIndexOfCountries($lastDate);

        $this->console->writeFixedWidth('       towns pre/cur/ins/upd', Updater::PRINT_WIDTH);
        $this->console->write(
            count($this->prev['towns'])
            .' / '.count($this->towns)
            .' / '.count($this->insertTownIds)
            .' / '.count($this->updateTownIds)
        );

        $this->console->writeFixedWidth('    accounts pre/cur/ins/upd', Updater::PRINT_WIDTH);
        $this->console->write(
            count($this->prev['accounts'])
            .' / '.count($this->accounts)
            .' / '.count($this->insertAccountIds)
            .' / '.count($this->updateAccountIds)
        );

        $this->console->writeFixedWidth('   countries pre/cur/ins/upd', Updater::PRINT_WIDTH);
        $this->console->write(
            count($this->prev['countries'])
            .' / '.count($this->countries)
            .' / '.count($this->insertCountryIds)
            .' / '.count($this->updateCountryIds)
        );


        $this->console->writeFixedWidth('    complete', Updater::PRINT_WIDTH);
        $this->console->write(round(microtime(true) - $time, 3).'s');
    }


    public function calculate()
    {
        $this->console->write('Calculation data');

        // Считаем население и города аккаунтов
        foreach ($this->towns as $townId => $town) {
            $accountId = $town[DataStorage::TOWN_KEY_ACCOUNT_ID];
            $pop = $town[DataStorage::TOWN_KEY_POP];
            $this->accounts[$accountId][DataStorage::ACCOUNT_KEY_POP] += $pop;
            $this->accounts[$accountId][DataStorage::ACCOUNT_KEY_TOWNS] += 1;
        }

        // Считаем население, аккаунты и города стран
        foreach ($this->accounts as $accountId => $account) {
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

            $this->countries[$countryId][DataStorage::COUNTRY_KEY_POP] += $pop;
            $this->countries[$countryId][DataStorage::COUNTRY_KEY_ACCOUNTS] += 1;
            $this->countries[$countryId][DataStorage::COUNTRY_KEY_TOWNS] += $towns;
            $this->countries[$countryId][DataStorage::COUNTRY_KEY_SCIENCE] += $science;
            $this->countries[$countryId][DataStorage::COUNTRY_KEY_PRODUCTION] += $production;
            $this->countries[$countryId][DataStorage::COUNTRY_KEY_ATTACK] += $attack;
            $this->countries[$countryId][DataStorage::COUNTRY_KEY_DEFENSE] += $defense;
        }

    }


    public function filter()
    {
        $this->accounts = array_filter($this->accounts, function ($account) {
            return $account[DataStorage::ACCOUNT_KEY_TOWNS] != 0
                && $account[DataStorage::ACCOUNT_KEY_POP] != 0;
        });

        $this->countries = array_filter($this->countries, function ($account) {
            return $account[DataStorage::COUNTRY_KEY_ACCOUNTS] != 0
                && $account[DataStorage::COUNTRY_KEY_TOWNS] != 0
                && $account[DataStorage::COUNTRY_KEY_POP] != 0;
        });
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
        $this->updateCommonData();
        $this->console->writeFixedWidth('    common:', Updater::PRINT_WIDTH, ' ');
        $this->console->write(round(microtime(true) - $time, 3).'s');

        $this->console->writeFixedWidth('    complete', Updater::PRINT_WIDTH);
        $this->console->write(round(microtime(true) - $allTime, 3).'s');
    }


    private function updateCommonData()
    {
        $table = $this->db->table("z_{$this->world->sign}_common");
        $table->insert([
            'stateDate'             => $this->time,
            'townsTotal'            => count($this->towns),
            'townsNew'              => count($this->insertTownIds),
            'townsRenamed'          => 0,
            'townsLost'             => 0,
            'townsDestroy'          => 0,
            'wondersNew'            => 0,
            'wondersDestroy'        => 0,
            'wondersActivate'       => 0,
            'accountsTotal'         => count($this->accounts),
            'accountsActive'        => 0,
            'accountsRace0'         => 0,
            'accountsRace1'         => 0,
            'accountsRace2'         => 0,
            'accountsRace3'         => 0,
            'accountsSex0'          => 0,
            'accountsSex1'          => 0,
            'accountsNew'           => count($this->insertAccountIds),
            'accountsCountryChange' => 0,
            'accountsCountryIn'     => 0,
            'accountsCountryOut'    => 0,
            'accountsDeleted'       => 0,
            'accountsRenamed'       => 0,
            'accountsRoleIn'        => 0,
            'accountsRoleOut'       => 0,
            'countriesTotal'        => count($this->countries),
            'countriesNew'          => count($this->insertCountryIds),
            'countriesRenamed'      => 0,
            'countriesFlag'         => 0,
            'countriesDeleted'      => 0,
        ]);
    }
}
