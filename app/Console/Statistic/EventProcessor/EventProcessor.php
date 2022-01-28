<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\EventProcessor;

use App\Console\Services\Console;
use App\Console\Statistic\Data\Account;
use App\Console\Statistic\Data\Country;
use App\Console\Statistic\Data\DataStorage;
use App\Console\Statistic\Data\Event;
use App\Console\Statistic\Data\Town;
use App\Models\World;
use App\Services\Wofh;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EventProcessor
{
    use EventsTowns;
    use EventsAccounts;
    use EventsCountries;

    const TABLE_TOWN_ID         = 'town_id';
    const TABLE_ACCOUNT_ID      = 'account_id';
    const TABLE_COUNTRY_ID      = 'country_id';
    const TABLE_COUNTRY_ID_FROM = 'country_id_from';
    const TABLE_ROLE            = 'role';
    const TABLE_PROPS           = 'props';

    protected Console $console;
    protected World $world;
    protected DataStorage $curr;
    protected DataStorage $prev;
    protected CarbonInterface $time;

    protected array $insertTownIds = [];
    protected array $updateTownIds = [];
    protected array $destroyedTownIds = [];

    public array $insertAccountIds = [];
    public array $updateAccountIds = [];
    public array $deleteAccountIds = [];

    public array $insertCountryIds = [];
    public array $updateCountryIds = [];
    public array $deleteCountryIds = [];

    private array $events;

    /** @var \App\Console\Statistic\Data\Town[]|\Illuminate\Support\Collection */
    protected Collection $towns;
    /** @var \App\Console\Statistic\Data\Account[]|\Illuminate\Support\Collection */
    protected Collection $accounts;
    /** @var \App\Console\Statistic\Data\Country[]|\Illuminate\Support\Collection */
    protected Collection $countries;

    public static function create(World $world, DataStorage $curr, DataStorage $prev)
    {
        $instance = resolve(EventProcessor::class);
        $instance->world = $world;
        $instance->curr = $curr;
        $instance->prev = $prev;
        $instance->time = $curr->getTime();
        return $instance;
    }

    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->events = [
            Wofh::EVENT_TOWN_CREATE  => [],
            Wofh::EVENT_TOWN_RENAME  => [],
            Wofh::EVENT_TOWN_LOST    => [],
            Wofh::EVENT_TOWN_DESTROY => [],

            Wofh::EVENT_ACCOUNT_CREATE         => [],
            Wofh::EVENT_ACCOUNT_COUNTRY_IN     => [],
            Wofh::EVENT_ACCOUNT_COUNTRY_OUT    => [],
            Wofh::EVENT_ACCOUNT_COUNTRY_CHANGE => [],
            Wofh::EVENT_ACCOUNT_DELETE         => [],
            Wofh::EVENT_ACCOUNT_RENAME         => [],
            Wofh::EVENT_ACCOUNT_ROLE_IN        => [],
            Wofh::EVENT_ACCOUNT_ROLE_OUT       => [],
            Wofh::EVENT_ACCOUNT_RATING_HIDE    => [],
            Wofh::EVENT_ACCOUNT_RATING_SHOW    => [],

            Wofh::EVENT_COUNTRY_CREATE  => [],
            Wofh::EVENT_COUNTRY_FLAG    => [],
            Wofh::EVENT_COUNTRY_RENAME  => [],
            Wofh::EVENT_COUNTRY_DESTROY => [],
            Wofh::EVENT_COUNTRY_PEACE   => [],
            Wofh::EVENT_COUNTRY_WAR     => [],

            Wofh::EVENT_WONDER_DESTROY  => [],
            Wofh::EVENT_WONDER_CREATE   => [],
            Wofh::EVENT_WONDER_ACTIVATE => [],
        ];
    }

    /*==
     *== Getters
     *== ======================================= ==*/

    public function getCountEvents($eventId): int { return count($this->events[$eventId]); }

    public function getEvents(): array
    {
        $result = [];
        foreach ($this->events as $events) {
            foreach ($events as $event) {
                $result[] = $event->toArray();
            }
        }
        return $result;
    }

    public function getTime(): CarbonInterface { return $this->time; }

    /*==
     *== Town getters
     *== ======================================= ==*/

    /** @return \App\Console\Statistic\Data\Town[]|\Illuminate\Support\Collection */
    public function getTowns(): Collection { return $this->curr->towns; }

    public function getCountTowns(): int { return $this->curr->towns->count(); }

    /** @return \App\Console\Statistic\Data\Town[]|\Illuminate\Support\Collection */
    public function getTownsForInsert(): Collection
    {
        return $this->curr->towns
            ->filter(fn(Town $town) => in_array($town->id, $this->insertTownIds))
            ->map(fn(Town $town) => $town->setNames(DB::raw("JSON_OBJECT('{$this->time->timestamp}', '$town->name')")));
    }

    /** @return \App\Console\Statistic\Data\Town[]|\Illuminate\Support\Collection */
    public function getTownsForUpdate(): Collection
    {
        return $this->curr->towns->filter(fn(Town $town) => in_array($town->id, $this->updateTownIds));
    }

    public function getDestroyedTownIds(): array { return $this->destroyedTownIds; }

    /*==
     *== Account getters
     *== ======================================= ==*/

    /** @return \App\Console\Statistic\Data\Account[]|\Illuminate\Support\Collection */
    public function getAccounts(): Collection { return $this->curr->accounts; }

    // @formatter:off
    public function getCountAccountsTotal(): int  { return $this->curr->getTotalAccounts(); }
    public function getCountAccountsActive(): int { return $this->curr->accounts->filter(fn(Account $acc) => $acc->pop  >  0)->count(); }
    public function getCountAccountsRace0(): int  { return $this->curr->accounts->filter(fn(Account $acc) => $acc->race == 0)->count(); }
    public function getCountAccountsRace1(): int  { return $this->curr->accounts->filter(fn(Account $acc) => $acc->race == 1)->count(); }
    public function getCountAccountsRace2(): int  { return $this->curr->accounts->filter(fn(Account $acc) => $acc->race == 2)->count(); }
    public function getCountAccountsRace3(): int  { return $this->curr->accounts->filter(fn(Account $acc) => $acc->race == 3)->count(); }
    public function getCountAccountsSex0(): int   { return $this->curr->accounts->filter(fn(Account $acc) => $acc->sex  == 0)->count(); }
    public function getCountAccountsSex1(): int   { return $this->curr->accounts->filter(fn(Account $acc) => $acc->sex  == 1)->count(); }
    // @formatter:on

    /** @return \App\Console\Statistic\Data\Account[]|\Illuminate\Support\Collection */
    public function getAccountsForInsert(): Collection
    {
        return $this->curr->accounts
            ->filter(fn(Account $account) => in_array($account->id, $this->insertAccountIds))
            ->map(fn(Account $account) => $account
                ->setNames(DB::raw("JSON_OBJECT('{$this->time->timestamp}', ".DB::getPdo()->quote($account->name).")"))
                ->setCountries(DB::raw("JSON_OBJECT()"))
            );
    }

    /** @return \App\Console\Statistic\Data\Account[]|\Illuminate\Support\Collection */
    public function getAccountsForUpdate(): Collection
    {
        return $this->curr->accounts->filter(fn(Account $account) => in_array($account->id, $this->updateAccountIds));
    }

    public function getDeletedAccountIds(): array { return $this->deleteAccountIds; }

    /*==
     *== Country getters
     *== ======================================= ==*/

    /** @return \App\Console\Statistic\Data\Country[]|\Illuminate\Support\Collection */
    public function getCountries() { return $this->curr->countries; }

    public function getCountCountries(): int { return $this->curr->countries->count(); }

    /** @return \App\Console\Statistic\Data\Country[]|\Illuminate\Support\Collection */
    public function getCountriesForInsert(): Collection
    {
        return $this->curr->countries
            ->filter(fn(Country $country) => in_array($country->id, $this->insertCountryIds))
            ->map(fn(Country $country) => $country
                ->setNames(DB::raw("JSON_OBJECT('{$this->time->timestamp}', ".DB::getPdo()->quote($country->name).")"))
                ->setFlags(DB::raw("JSON_OBJECT('{$this->time->timestamp}', ".DB::getPdo()->quote($country->flag).")"))
            );
    }

    /** @return \App\Console\Statistic\Data\Country[]|\Illuminate\Support\Collection */
    public function getCountriesForUpdate(): Collection
    {
        return $this->curr->countries->filter(fn(Country $country) => in_array($country->id, $this->updateCountryIds));
    }

    public function getDeletedCountiesIds(): array { return $this->deleteCountryIds; }

    /*==
     *== Methods
     *== ======================================= ==*/

    public function checkEvents()
    {
        withWorldPrefix(function () {

            $this->towns = DB::table('towns')
                ->select()->get()->keyBy('id')
                ->map(fn($obj) => Town::createFromDb($obj));

            $this->accounts = DB::table('accounts')
                ->select()->get()->keyBy('id')
                ->map(fn($obj) => Account::createFromDb($obj));

            $this->countries = DB::table('countries')
                ->select()->get()->keyBy('id')
                ->map(fn($obj) => Country::createFromDb($obj));

        }, $this->world);

        $this->checkEventsOfTowns();
        $this->checkEventsOfAccounts();
        $this->checkEventsOfCountries();
    }

    public function push(int $eventId, array $body)
    {
        $this->events[$eventId][] = new Event($eventId, $this->curr->getTime(), $body);
    }
}
