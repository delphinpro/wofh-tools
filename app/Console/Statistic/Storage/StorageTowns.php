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
use App\Console\Statistic\StorageProcessor\StorageProcessor;
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
        $this->eventProcessor->getTownsForUpdate()->each(function (Town $town) {
            DB::table('towns')
                ->where('id', $town->id)
                ->update($town->toArray());
        });
    }

    private function insertTownsStatistic()
    {
        $tableName = 'z_'.$this->world->sign.'_towns_data';
        $columns = collect([
            'state_at',
            'id',
            'pop',
            'wonder_id',
            'wonder_level',
            'delta_pop',
        ])->map(fn($s) => "`$s`")->join(',');

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $queryStringCommon = "INSERT INTO `$tableName` ($columns) VALUES ";

        $towns = $this->eventProcessor->getTowns();
        $first = true;
        $counter = 0;
        $queryStringValues = '';

        foreach ($towns as $town) {
            if (!$first) $queryStringValues .= ',';
            $first = false;

            $queryStringValues .= '('."'{$this->getTime()}'";
            $queryStringValues .= ','.($town->id);
            $queryStringValues .= ','.($town->pop);
            $queryStringValues .= ','.($town->wonderId());
            $queryStringValues .= ','.($town->wonderLevel());
            $queryStringValues .= ','.$town->getDeltaPop();
            $queryStringValues .= ')';

            if (++$counter >= StorageProcessor::CHUNK) {
                DB::insert($queryStringCommon.$queryStringValues);
                $first = true;
                $counter = 0;
                $queryStringValues = '';
            }
        }

        if ($queryStringValues) DB::insert($queryStringCommon.$queryStringValues);
    }
}
