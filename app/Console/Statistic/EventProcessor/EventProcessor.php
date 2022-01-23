<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\EventProcessor;

use App\Console\Services\Console;
use App\Console\Statistic\Storage\Storage;
use App\Services\Wofh;
use Carbon\Carbon;
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

    private Console $console;
    private Storage $curr;
    private Storage $prev;

    public array $insertTownIds = [];
    public array $updateTownIds = [];
    public array $lostTownIds = [];
    public array $destroyedTownIds = [];

    public array $insertAccountIds = [];
    public array $updateAccountIds = [];
    public array $deleteAccountIds = [];

    public array $insertCountryIds = [];
    public array $updateCountryIds = [];
    public array $deleteCountryIds = [];

    /** @var array[] */
    private $events;

    public static function create(Storage $curr, Storage $prev)
    {
        $instance = resolve(EventProcessor::class);
        $instance->curr = $curr;
        $instance->prev = $prev;
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

    public function checkEvents()
    {
        $this->checkEventsOfTowns();
        $this->checkEventsOfAccounts();
        $this->checkEventsOfCountries();
    }

    public function updateTableEvents(string $sign, Carbon $timestamp)
    {
        $time = microtime(true);

        $columns = [
            'state_at',
            'id',
            'town_id',
            'account_id',
            'country_id',
            'country_id_from',
            'role',
            'props',
        ];

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = "INSERT";
        $sql .= " INTO `z_{$sign}_events`";
        $sql .= " (`".join('`,`', $columns)."`)";
        $sql .= " VALUES ";

        $pdo = DB::getPdo();
        $first = true;

        foreach ($this->events as $eventId => $events) {
            foreach ($events as $event) {
                if (!$first) $sql .= ','; else $first = false;

                $sql .= "(";
                $sql .= ($pdo->quote($timestamp));
                $sql .= ",".(intval($eventId));
                $sql .= ",".(intval($event[static::TABLE_TOWN_ID]));
                $sql .= ",".(intval($event[static::TABLE_ACCOUNT_ID]));
                $sql .= ",".(intval($event[static::TABLE_COUNTRY_ID]));
                $sql .= ",".(intval($event[static::TABLE_COUNTRY_ID_FROM]));
                $sql .= ",".(intval($event[static::TABLE_ROLE]));
                $sql .= ",".$pdo->quote(json_encode($event[static::TABLE_PROPS]));
                $sql .= ")";
            }
        }

        // Если есть хоть одно событие
        if (!$first) {
            DB::insert($sql);
        }

        $this->console->line('    events: '.t($time).'s');
    }
}
