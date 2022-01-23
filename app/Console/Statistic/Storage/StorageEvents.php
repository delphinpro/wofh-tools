<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

use Illuminate\Support\Facades\DB;

trait StorageEvents
{
    public function updateTableEvents()
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
                $sql .= ",".(intval($event[EventProcessor::TABLE_TOWN_ID]));
                $sql .= ",".(intval($event[EventProcessor::TABLE_ACCOUNT_ID]));
                $sql .= ",".(intval($event[EventProcessor::TABLE_COUNTRY_ID]));
                $sql .= ",".(intval($event[EventProcessor::TABLE_COUNTRY_ID_FROM]));
                $sql .= ",".(intval($event[EventProcessor::TABLE_ROLE]));
                $sql .= ",".$pdo->quote(json_encode($event[EventProcessor::TABLE_PROPS]));
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
