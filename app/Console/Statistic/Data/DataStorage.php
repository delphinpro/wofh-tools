<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Data;

use App\Console\Services\Console;
use App\Models\World;
use App\Services\Json;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class DataStorage
{
    private Filesystem $fs;
    private Json $json;
    private Console $console;
    private World $world;
    protected bool $silent;

    private ?array $raw;
    private ?Carbon $time;

    private ?int $totalAccounts;

    /** @var \Illuminate\Support\Collection|\App\Console\Statistic\Data\Town[] */
    public Collection $towns;

    /** @var \Illuminate\Support\Collection|\App\Console\Statistic\Data\Account[] */
    public Collection $accounts;

    /** @var \Illuminate\Support\Collection|\App\Console\Statistic\Data\Country[] */
    public Collection $countries;

    public static function create(World $world, Filesystem $fileSystem, bool $silent = false): DataStorage
    {
        $instance = resolve(DataStorage::class);
        $instance->world = $world;
        $instance->fs = $fileSystem;
        $instance->silent = $silent;
        return $instance;

    }

    public function __construct(Json $json, Console $console)
    {
        $this->console = $console;
        $this->json = $json;

        $this->raw = null;
        $this->time = null;
        $this->totalAccounts = null;
        $this->towns = collect([]);
        $this->accounts = collect([]);
        $this->countries = collect([]);
    }

    /*==
     *== Getters
     *== ======================================= ==*/

    public function getTime(): ?CarbonInterface { return $this->time; }

    public function getTown(int $id): Town { return $this->towns->get($id); }

    public function getAccount(int $id): Account { return $this->accounts->get($id); }

    public function getCountry(int $id): Country { return $this->countries->get($id); }

    public function getCountryIdForAccount(?int $accountId): ?int
    {
        if ($this->hasAccount($accountId)) return $this->getAccount($accountId)->country_id;
        return null;
    }

    public function getTotalAccounts(): int { return $this->totalAccounts; }

    public function getData(): array
    {
        return [
            'countries' => $this->countries->map(fn(Country $item) => $item->toArray()),
            'accounts'  => $this->accounts->map(fn(Account $item) => $item->toArray()),
            'towns'     => $this->towns->map(fn(Town $item) => $item->toArray()),
        ];
    }

    /*==
     *== Assertions
     *== ======================================= ==*/

    public function hasData(): bool { return !is_null($this->time); }

    public function hasTown(?int $id): bool { return $this->towns->has($id); }

    public function hasAccount(?int $id): bool { return $this->accounts->has($id); }

    public function hasCountry(?int $id): bool { return $this->countries->has($id); }

    public function hasCountries(): bool { return $this->countries->count() > 0; }

    /*==
     *== Prepare and Calculation process
     *== ======================================= ==*/

    protected function configure(World $world, Filesystem $fileSystem, bool $silent = false)
    {
        $this->world = $world;
        $this->fs = $fileSystem;
        $this->silent = $silent;
    }

    /**
     * @throws \App\Exceptions\JsonServiceException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function loadFromFile(?string $filename)
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

    public function parse(string $title = 'Operation')
    {
        if (!$this->hasData()) return;

        $time = microtime(true);
        $this->collectTowns();
        $this->collectAccounts();
        $this->collectCountries();
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
        if (!$this->silent) $this->console->table([$title, 'Towns', 'Accounts', 'Countries', 'Time, s'], $rows);
    }

    protected function collectTowns()
    {
        $this->towns = collect($this->raw['towns'])
            ->map(fn($town, $id) => Town::createFromFile($id, $town))
            ->filter(fn(Town $town) => $town->pop > 0); // Убрать города с нулевым населением
    }

    protected function collectAccounts()
    {
        $this->accounts = collect($this->raw['accounts'])->map(fn($account, $id) => new Account($id, $account));
    }

    protected function collectCountries()
    {
        $this->countries = collect($this->raw['countries'])->map(fn($country, $id) => new Country($id, $country));
    }

    protected function calculate()
    {
        // Считаем население и города аккаунтов
        foreach ($this->towns as $town) {
            if ($town->isBarbarian()) continue;

            $this->accounts[$town->account_id]->pop += $town->pop;
            $this->accounts[$town->account_id]->towns += 1;
        }

        // Считаем население, аккаунты и города стран
        foreach ($this->accounts as $account) {
            if (!$account->inCountry()) continue; // Аккаунт вне страны, пропуск.

            $this->countries[$account->country_id]->pop += $account->pop;
            $this->countries[$account->country_id]->accounts += 1;
            $this->countries[$account->country_id]->towns += $account->towns;
            $this->countries[$account->country_id]->rating_science += $account->rating_science;
            $this->countries[$account->country_id]->rating_production += $account->rating_production;
            $this->countries[$account->country_id]->rating_attack += $account->rating_attack;
            $this->countries[$account->country_id]->rating_defense += $account->rating_defense;
        }
    }

    protected function filter()
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

    protected function unsetRaw() { $this->raw = null; }
}
