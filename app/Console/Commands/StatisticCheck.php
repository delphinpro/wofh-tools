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
use App\Services\Wofh;
use App\Traits\CliColors;
use Illuminate\Console\Command;

class StatisticCheck extends Command
{
    use CliColors;
    use Helper;

    protected $signature = 'stat:check';

    protected $description = 'Check working servers of game';

    protected Wofh $wofh;

    protected WorldRepository $worldRepository;

    public function __construct(Wofh $wofh, WorldRepository $worldRepository)
    {
        parent::__construct();
        $this->wofh = $wofh;
        $this->worldRepository = $worldRepository;
    }

    public function handle(): int
    {
        $this->alert('Updating the status of worlds');
        return $this->checkWorlds() === true ? 1 : 0;
    }
}
