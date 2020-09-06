<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Commands;


use App\Console\Traits\Helper;
use App\Repositories\WorldRepository;
use App\Traits\CliColors;
use Illuminate\Console\Command;


class StatisticList extends Command
{
    use CliColors;
    use Helper;


    /** @var string */
    protected $signature = 'stat:list';

    /** @var string */
    protected $description = 'Display status of worlds';

    /** @var \App\Repositories\WorldRepository */
    protected $worldRepository;


    /**
     * Create a new command instance.
     *
     * @param \App\Repositories\WorldRepository $worldRepository
     */
    public function __construct(WorldRepository $worldRepository)
    {
        parent::__construct();
        $this->worldRepository = $worldRepository;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert('Status of worlds');
        $this->printStatusOfWorlds($this->worldRepository->all());

        return 0;
    }
}
