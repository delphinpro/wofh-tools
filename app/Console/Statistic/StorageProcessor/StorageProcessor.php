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
use App\Console\Statistic\Data\DataStorage;
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
    protected DataStorage $prev;
    protected DataStorage $curr;

    public function __construct(
        Console        $console,
        EventProcessor $eventProcessor,
        World          $world,
        DataStorage    $dataPrevious,
        DataStorage    $data
    ) {
        $this->console = $console;
        $this->eventProcessor = $eventProcessor;
        $this->world = $world;
        $this->prev = $dataPrevious;
        $this->curr = $data;
    }

    public function getTime(): ?CarbonInterface { return $this->curr->getTime(); }

    public function save()
    {
        $time = microtime(true);
        $this->console->line('Saving data');

        $savedPrefix = setStatisticTablePrefix($this->world->sign);

        $this->updateTableTowns();
        $this->updateTableAccounts();
        $this->updateTableCountries();
        $this->eventProcessor->updateTableEvents($this->world->sign, $this->getTime());
        $this->updateTableCommon();

        setTablePrefix($savedPrefix);

        $this->console->line('Total saving time: '.t($time));
    }
}
