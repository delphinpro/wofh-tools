<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

use Illuminate\Support\Facades\DB;

trait StorageEvents
{
    public function updateTableEvents()
    {
        $time = microtime(true);

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $events = $this->eventProcessor->getEvents();
        DB::table('events')->insert($events);

        $this->console->line('    events: '.t($time).'s');
    }
}
