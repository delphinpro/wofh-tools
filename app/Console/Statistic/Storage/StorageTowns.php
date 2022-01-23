<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020–2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Storage;

use App\Console\Statistic\Data\Town;
use Illuminate\Support\Facades\DB;

/**
 * Trait StorageTowns
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\EventProcessor\EventProcessor $eventProcessor
 * @property \App\Models\World world
 */
trait StorageTowns
{
    public function updateTableTowns()
    {
        $time = microtime(true);
        $this->updateTownsCreated();
        $this->updateTownsDestroyed();
        $this->updateTowns();
        $this->insertTownsStatistic();
        $this->console->line('    towns: '.t($time).'s');
    }

    private function updateTownsCreated()
    {
        $towns = $this->eventProcessor->getTownsForInsert()->toArray();
        DB::table('towns')->insert($towns);
    }

    private function updateTownsDestroyed()
    {
        DB::table('towns')
            ->whereIn('id', $this->eventProcessor->getDestroyedTownIds())
            ->update([
                'pop'       => 0,
                'lost'      => 1,
                'destroyed' => 1,
            ]);
    }

    private function updateTowns()
    {
        $this->eventProcessor->getTownsForUpdate()->each(function(Town $town){
            DB::table('towns')
                ->where('id', $town->id)
                ->update($town->toArray());
        });
    }

    private function insertTownsStatistic()
    {
        $columns = [
            'state_at',
            'id',
            'pop',
            'wonder_id',
            'wonder_level',
            // 'deltaPop',
        ];

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = 'INSERT';
        $sql .= ' INTO `z_'.$this->world->sign.'_towns_stat`';
        $sql .= ' (`'.join('`,`', $columns).'`)';
        $sql .= ' VALUES ';

        $pdo = DB::getPdo();
        $first = true;

        /** @var \App\Console\Statistic\Data\Town $town */
        foreach ($this->towns as $town) {
            if (!$first) $sql .= ','; else $first = false;

            $sql .= '(';
            $sql .= ($pdo->quote($this->time));
            $sql .= ','.(intval($town->id));
            $sql .= ','.(intval($town->pop));
            $sql .= ','.(intval($town->wonderId));
            $sql .= ','.(intval($town->wonderLevel));
            // $sql .= ','.(intval($town[Storage::TOWN_KEY_DELTA_POP]));
            $sql .= ')';
        }

        DB::insert($sql);
    }
}
