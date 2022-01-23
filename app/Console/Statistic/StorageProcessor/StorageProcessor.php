<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\StorageProcessor;

use App\Console\Services\Console;
use App\Console\Statistic\EventProcessor\EventProcessor;
use App\Console\Statistic\Storage\StorageAccounts;
use App\Console\Statistic\Storage\StorageCommon;
use App\Console\Statistic\Storage\StorageCountries;
use App\Console\Statistic\Storage\StorageEvents;
use App\Console\Statistic\Storage\StorageTowns;
use App\Models\World;
use Carbon\CarbonInterface;

class StorageProcessor
{
    use StorageTowns;
    use StorageAccounts;
    use StorageCountries;
    use StorageEvents;
    use StorageCommon;

    protected EventProcessor $eventProcessor;
    protected Console $console;
    protected World $world;

    public function __construct(
        Console        $console,
        World          $world,
        EventProcessor $eventProcessor
    ) {
        $this->console = $console;
        $this->eventProcessor = $eventProcessor;
        $this->world = $world;
    }

    public function getTime(): ?CarbonInterface { return $this->eventProcessor->getTime(); }

    public function save()
    {
        $time = microtime(true);
        $this->console->line('Saving data');

        withWorldPrefix(function () {
            $this->updateTableTowns();
            $this->updateTableAccounts();
            $this->updateTableCountries();
            $this->updateTableEvents();
            $this->updateTableCommon();
        }, $this->world);

        $this->console->line('Total saving time: '.t($time));
    }
}
