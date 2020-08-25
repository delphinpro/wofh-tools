<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Commands;


use Illuminate\Console\Command;


class MakeCommand extends Command
{
    use ListCommands;


    protected $signature = 'make';

    protected $description = 'List all make commands';


    public function handle()
    {
        $this->comment('');
        $this->comment('Available commands:');

        $this->allCommands('make');
    }
}
