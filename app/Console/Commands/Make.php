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


class Make extends Command
{
    use CliListCommands;


    protected $signature = 'make';

    protected $description = 'List all make commands';


    public function handle()
    {
        $this->comment('');
        $this->comment('Available commands:');

        $this->allCommands('make');
    }
}
