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


class Statistic extends Command
{
    use ListCommands;


    protected $signature = 'stat';

    protected $description = 'List all stat commands';


    public function handle()
    {
        $this->comment('');
        $this->comment('Available commands:');

        $this->allCommands('stat');

        return 0;
    }
}
