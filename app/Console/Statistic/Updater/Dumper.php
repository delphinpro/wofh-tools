<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Updater;


use App\Console\Statistic\DataStorage;
use Illuminate\Support\Facades\Storage;


trait Dumper
{
    public function dump(DataStorage $curr, DataStorage $prev, int $worldId)
    {
        $dump = [
            'curr' => $curr->getData(),
            'prev' => $prev->getData(),
            // 'events'           => $this->events,
            // 'insertTownIds'    => $this->insertTownIds,
            // 'updateTownIds'    => $this->updateTownIds,
            // 'insertAccountIds' => $this->insertAccountIds,
            // 'updateAccountIds' => $this->updateAccountIds,
            // 'insertCountryIds' => $this->insertCountryIds,
            // 'updateCountryIds' => $this->updateCountryIds,
        ];

        $filename = 'dump-'.$worldId.'-'.$curr->getTime()->format('d-m-Y_H-i-s').'.json';
        $fs = Storage::disk('public');

        $fs->put($filename, json_encode($dump, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
