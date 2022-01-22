<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Commands;

use App\Traits\CliListCommands;
use Illuminate\Console\Command;

class Statistic extends Command
{
    use CliListCommands;

    protected $signature = 'stat';

    protected $description = 'List all stat commands';

    public function handle(): int
    {
        $this->comment('');
        $this->comment('Available commands:');

        $this->allCommands('stat');

        return 0;
    }
}
