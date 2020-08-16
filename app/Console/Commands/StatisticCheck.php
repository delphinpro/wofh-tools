<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Commands;


use App\Console\Traits\CliHelper;
use App\Console\Traits\ConsoleColors;
use App\Repositories\WorldRepository;
use App\Services\Wofh;
use Illuminate\Console\Command;


class StatisticCheck extends Command
{
    use ConsoleColors;
    use CliHelper;


    /** @var string */
    protected $signature = 'stat:check';

    /** @var string */
    protected $description = 'Check working servers of game';

    /** @var \App\Services\Wofh */
    protected $wofh;

    /** @var \App\Repositories\WorldRepository */
    protected $worldRepository;


    /**
     * Create a new command instance.
     *
     * @param \App\Services\Wofh                $wofh
     * @param \App\Repositories\WorldRepository $worldRepository
     */
    public function __construct(Wofh $wofh, WorldRepository $worldRepository)
    {
        parent::__construct();
        $this->wofh = $wofh;
        $this->worldRepository = $worldRepository;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert('Updating the status of worlds');

        return $this->checkWorlds() ? 0 : 1;
    }
}
