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
use App\Console\Statistic\Data\DataStorage;
use App\Console\Statistic\Data\Event;
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
    protected array $lostTownIds = [];
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

    public function count($eventId): int { return count($this->events[$eventId]); }

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

    public function checkEvents()
    {
        withWorldPrefix(function () {
            $this->towns = DB::table('towns')->select()->get()->keyBy('id');
            $this->accounts = DB::table('accounts')->select()->get()->keyBy('id');
            $this->countries = DB::table('countries')->select()->get()->keyBy('id');
        }, $this->world);

        $this->checkEventsOfTowns();
        $this->checkEventsOfAccounts();
        $this->checkEventsOfCountries();
    }

    public function push(int $eventId, int $entityId, array $body)
    {
        $this->events[$eventId][$entityId] = new Event($eventId, $this->curr->getTime(), $body);
    }
}
