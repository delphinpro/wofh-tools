<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

use App\Console\Services\Console;
use App\Console\Statistic\Data\Account;
use App\Console\Statistic\Data\Country;
use App\Console\Statistic\Data\Town;
use App\Console\Statistic\EventProcessor\EventProcessor;
use App\Models\World;
use App\Services\Json;
use App\Services\Wofh;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Storage
{
    use Assertion;
    use Normalizer;
    use StorageTowns;
    use StorageAccounts;
    use StorageCountries;

    private FilesystemManager $fsManager;

    private Json $json;

    private Console $console;

    private World $world;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter */
    private $fs;

    private ?array $raw;

    private ?Carbon $time;

    private ?int $totalAccounts;

    /** @var \Illuminate\Support\Collection|\App\Console\Statistic\Data\Town[] */
    public Collection $towns;

    /** @var \Illuminate\Support\Collection|\App\Console\Statistic\Data\Account[] */
    public Collection $accounts;

    /** @var \Illuminate\Support\Collection|\App\Console\Statistic\Data\Country[] */
    public Collection $countries;

    private EventProcessor $events;

    public function __construct(FilesystemManager $fsManager, Json $json, Console $console)
    {
        $this->fsManager = $fsManager;
        $this->console = $console;
        $this->json = $json;

        $this->raw = null;
        $this->time = null;
        $this->totalAccounts = null;
        $this->towns = collect([]);
        $this->accounts = collect([]);
        $this->countries = collect([]);
    }

    public function setWorld(World $world)
    {
        $this->world = $world;
        $this->fs = $this->fsManager->disk(config('app.stat_disk'));
    }

    /**
     * @throws \App\Exceptions\JsonServiceException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function loadFromFile($filename)
    {
        if (is_null($filename)) return;

        $jsonString = $this->fs->get($filename);
        $this->raw = $this->json->decode($jsonString);

        if (empty($this->raw['countries'])) {
            $this->raw['countries'] = [];
        }

        $this->time = Carbon::createFromTimestamp($this->raw['time']);
        $this->totalAccounts = count($this->raw['accounts']);
    }

    public function getTime(): ?Carbon { return $this->time; }

    public function getTown(int $id): Town { return $this->towns->get($id); }

    public function getAccount(int $id): Account { return $this->accounts->get($id); }

    public function getCountry(int $id): Country { return $this->countries->get($id); }

    public function unsetRaw() { $this->raw = null; }

    public function collectData()
    {
        $this->collectTowns();
        $this->collectAccounts();
        $this->collectCountries();
    }

    public function calculate()
    {
        // Считаем население и города аккаунтов
        foreach ($this->towns as $town) {
            $accountId = $town->account_id;

            if ($accountId == 0) continue;

            $this->accounts[$accountId]->pop += $town->pop;
            $this->accounts[$accountId]->towns += 1;
        }

        // Считаем население, аккаунты и города стран
        foreach ($this->accounts as $account) {
            $countryId = $account->country_id;

            if (!$countryId) continue; // Аккаунт вне страны, пропуск.

            $this->countries[$countryId]->pop += $account->pop;
            $this->countries[$countryId]->accounts += 1;
            $this->countries[$countryId]->towns += $account->towns;
            $this->countries[$countryId]->rating_science += $account->rating_science;
            $this->countries[$countryId]->rating_production += $account->rating_production;
            $this->countries[$countryId]->rating_attack += $account->rating_attack;
            $this->countries[$countryId]->rating_defense += $account->rating_defense;
        }
    }

    public function filter()
    {
        $this->accounts = $this->accounts->filter(function (Account $account) {
            return $account->towns != 0
                && $account->pop != 0;
        });

        $this->countries = $this->countries->filter(function (Country $country) {
            return $country->accounts != 0
                && $country->towns != 0
                && $country->pop != 0;
        });
    }

    public function parse()
    {
        $time = microtime(true);
        $this->collectData();
        $rows[] = [
            'Normalization',
            $this->towns->count(),
            $this->accounts->count(),
            $this->countries->count(),
            t($time),
        ];

        $time = microtime(true);
        $this->calculate();
        $rows[] = ['Calculate', '', '', '', t($time)];

        $time = microtime(true);
        $this->filter();
        $rows[] = [
            'Filter',
            $this->towns->count(),
            $this->accounts->count(),
            $this->countries->count(),
            t($time),
        ];

        $this->unsetRaw();
        $this->console->table(['Operation', 'Towns', 'Accounts', 'Countries', 'Time, s'], $rows);
    }

    public function save(EventProcessor $events)
    {
        $time = microtime(true);
        $this->events = $events;
        $this->console->line('Saving data');

        $this->updateTableTowns();
        $this->updateTableAccounts();
        $this->updateTableCountries();
        $this->events->updateTableEvents($this->world->sign, $this->time);
        $this->updateTableCommon();

        $this->console->line('Total saving time: '.t($time));
    }

    public function updateTableCommon()
    {
        DB::table("z_{$this->world->sign}_common")->insert([
            'state_at' => $this->time,

            'towns_total'   => count($this->towns),
            'towns_new'     => $this->events->count(Wofh::EVENT_TOWN_CREATE),
            'towns_renamed' => $this->events->count(Wofh::EVENT_TOWN_RENAME),
            'towns_lost'    => $this->events->count(Wofh::EVENT_TOWN_LOST),
            'towns_destroy' => 0,

            'wonders_new'      => $this->events->count(Wofh::EVENT_WONDER_CREATE),
            'wonders_destroy'  => $this->events->count(Wofh::EVENT_WONDER_DESTROY),
            'wonders_activate' => $this->events->count(Wofh::EVENT_WONDER_ACTIVATE),

            'accounts_total'  => $this->totalAccounts,
            'accounts_active' => $this->accounts->filter(fn(Account $acc) => $acc->pop > 0)->count(),
            'accounts_race0'  => $this->accounts->filter(fn(Account $acc) => $acc->race == 0)->count(),
            'accounts_race1'  => $this->accounts->filter(fn(Account $acc) => $acc->race == 1)->count(),
            'accounts_race2'  => $this->accounts->filter(fn(Account $acc) => $acc->race == 2)->count(),
            'accounts_race3'  => $this->accounts->filter(fn(Account $acc) => $acc->race == 3)->count(),
            'accounts_sex0'   => $this->accounts->filter(fn(Account $acc) => $acc->sex == 0)->count(),
            'accounts_sex1'   => $this->accounts->filter(fn(Account $acc) => $acc->sex == 1)->count(),

            'accounts_new'            => $this->events->count(Wofh::EVENT_ACCOUNT_CREATE),
            'accounts_country_change' => $this->events->count(Wofh::EVENT_ACCOUNT_COUNTRY_CHANGE),
            'accounts_country_in'     => $this->events->count(Wofh::EVENT_ACCOUNT_COUNTRY_IN),
            'accounts_country_out'    => $this->events->count(Wofh::EVENT_ACCOUNT_COUNTRY_OUT),
            'accounts_deleted'        => $this->events->count(Wofh::EVENT_ACCOUNT_DELETE),
            'accounts_renamed'        => $this->events->count(Wofh::EVENT_ACCOUNT_RENAME),
            'accounts_role_in'        => $this->events->count(Wofh::EVENT_ACCOUNT_ROLE_IN),
            'accounts_role_out'       => $this->events->count(Wofh::EVENT_ACCOUNT_ROLE_OUT),
            'accounts_rating_hide'    => $this->events->count(Wofh::EVENT_ACCOUNT_RATING_HIDE),
            'accounts_rating_show'    => $this->events->count(Wofh::EVENT_ACCOUNT_RATING_SHOW),

            'countries_total'   => count($this->countries),
            'countries_new'     => $this->events->count(Wofh::EVENT_COUNTRY_CREATE),
            'countries_renamed' => $this->events->count(Wofh::EVENT_COUNTRY_RENAME),
            'countries_flag'    => $this->events->count(Wofh::EVENT_COUNTRY_FLAG),
            'countries_deleted' => $this->events->count(Wofh::EVENT_COUNTRY_DESTROY),
        ]);
    }

    public function getData(): array
    {
        return [
            'countries' => $this->countries->map(fn(Country $item) => $item->toArray()),
            'accounts'  => $this->accounts->map(fn(Account $item) => $item->toArray()),
            'towns'     => $this->towns->map(fn(Town $item) => $item->toArray()),
        ];
    }
}
