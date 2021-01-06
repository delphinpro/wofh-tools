<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Traits;


use App\Console\Color;
use Carbon\Carbon;
use Illuminate\Support\Collection;


trait Helper
{
    /**
     * @param \App\Models\World[]|\Illuminate\Support\Collection $worlds
     */
    protected function printStatusOfWorlds(Collection $worlds)
    {
        $header = ['  world', 'statistic', 'loaded at', 'updated at'];
        $rows = [];

        foreach ($worlds as $world) {
            if (!$world->working &&
                !$world->stat_loaded_at &&
                !$world->stat_updated_at &&
                !$world->statistic
            ) {
                continue;
            }

            $color = $world->working ? Color::BLUE : null;
            $rows[] = [
                $this->makeString(($world->working ? '* ' : '  ').$world->sign, $color),
                $this->makeString($world->statistic ? 'On' : 'Off', $color),
                $this->makeString($world->stat_loaded_at, $color),
                $this->makeString($world->stat_updated_at, $color),
            ];
        }

        $now = Carbon::now();
        $this->line('Now: '.$now.' '.$now->tzName);
        $this->table($header, $rows);
    }


    /**
     * @param bool $printTable Печатать таблицу статуса миров
     *
     * @return bool|string
     */
    protected function checkWorlds(bool $printTable = true): bool
    {
        try {

            $this->wofh->check();
            $this->info('[OK] The status of worlds has been updated successfully');

            if ($printTable) {
                $this->printStatusOfWorlds($this->worldRepository->all());
            }

            return true;

        } catch (\Exception $e) {

            $this->error('[FAIL] '.$e->getMessage());

            return $e->getMessage();
        }
    }
}
