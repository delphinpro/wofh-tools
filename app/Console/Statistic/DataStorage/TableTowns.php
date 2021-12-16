<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\DataStorage;

use Illuminate\Support\Facades\DB;

/**
 * Trait TableTowns
 *
 * @package App\Console\Services\Statistic
 * @property \App\Console\Services\Console console
 * @property \App\Console\Statistic\DataEvents events
 * @property \App\Models\World world
 */
trait TableTowns
{
    public function updateTableTowns()
    {
        $time = microtime(true);
        $this->insertTowns();
        $this->updateTownsLost();
        $this->insertTownsStatistic();
        $this->console->line('    towns: '.t($time).'s');
    }

    private function insertTowns()
    {
        if (empty($this->events->insertTownIds)) return;

        $columns = [
            'id',
            'title',
            'account_id',
            'lost',
            'destroy',
            'extra',
        ];

        // INSERT INTO tbl_name (a, b, c) VALUES (1,2,3), (4,5,6), (7,8,9);
        $sql = 'INSERT';
        $sql .= ' INTO `z_'.$this->world->sign.'_towns`';
        $sql .= ' (`'.join('`,`', $columns).'`)';
        $sql .= ' VALUES ';

        $pdo = DB::getPdo();
        $first = true;

        foreach ($this->events->insertTownIds as $id) {
            /** @var \App\Console\Statistic\Data\Town $town */
            $town = $this->getTown($id);
            if (!$first) {
                $sql .= ',';
            } else {
                $first = false;
            }

            $sql .= '(';
            $sql .= (intval($id));
            $sql .= ','.($pdo->quote($town->title));
            $sql .= ','.(intval($town->accountId));
            $sql .= ','.'0';
            $sql .= ','.'0';
            $sql .= ','.'NULL';
            $sql .= ')';
        }

        // $this->console->line($sql);

        DB::insert($sql);
    }

    private function updateTownsLost()
    {
        // if (empty($this->lostTownIds)) {
        //     return;
        // }

        // UPDATE TABLE tbl_name SET `lost` = 1 WHERE `id` IN (a, b, c);
        // $sql = "UPDATE `z_{$this->world->sign}_towns`";
        // $sql .= " SET `lost` = 1";
        // $sql .= " WHERE `townId` IN (".join(',', $this->lostTownIds).")";
        //
        // $this->db->statement($sql);
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
            // $sql .= ','.(intval($town[DataStorage::TOWN_KEY_DELTA_POP]));
            $sql .= ')';
        }

        DB::insert($sql);
    }
}
