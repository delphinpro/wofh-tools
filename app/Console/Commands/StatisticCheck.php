<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Commands;


use App\Repositories\WorldRepository;
use App\Services\Wofh;
use App\Traits\CliColors;
use App\Traits\CliHelper;
use Illuminate\Console\Command;


class StatisticCheck extends Command
{
    use CliColors;
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

        return $this->checkWorlds() === true ? 1 : 0;
    }
}
