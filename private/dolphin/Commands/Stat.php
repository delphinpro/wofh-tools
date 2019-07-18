<?php

namespace dolphin\Commands;


use Dolphin\Command;
use Dolphin\CommandInterface;
use Dolphin\Commands\Stat\Statistic;
use Dolphin\Console;
use Dolphin\Help;
use Psr\Container\ContainerInterface;
use WofhTools\Tools\Wofh;


require_once DIR_ROOT.'/private/bootstrap/global_functions.php';


/**
 * Class Stat
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016—2019 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin\Commands
 *
 * @property Wofh wofh
 */
class Stat extends Command implements CommandInterface
{
    protected $aliases = [
        '-w' => '--world',
        '-l' => '--limit',
        '-e' => '--email',
        '-o' => '--output',
    ];

    /** @var Statistic */
    private $statistic;


    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->statistic = new Statistic($this->container);
    }


    public function load()
    {
        $this->check();
        $this->statistic->load();
    }


    public function check()
    {
        $message = 'The status of worlds has been updated successfully';
        $this->console->write('The status of worlds updating... ', null, false);

        try {

            $this->bootEloquent();
            $links = $this->wofh->getAllStatusLinks();
            $this->wofh->check($links);
            $this->console->write('SUCCESS !', Console::GREEN);
            $this->console->write($message, Console::GREEN);

        } catch (\Exception $e) {

            $this->console->write('FAIL !', Console::RED);
            $this->console->write($e->getMessage(), Console::RED);

        }
    }


    /**
     * @throws \Exception
     */
    public function update()
    {
        $this->console->write(__METHOD__, Console::RED);
        $this->bootEloquent();
        $this->statistic->update();
    }


    /**
     * @throws \Exception
     */
    public function clear()
    {
        $this->bootEloquent();
        $this->statistic->clear();
    }


    /**
     * @throws \Exception
     */
    public function list()
    {
        $this->bootEloquent();
        $this->statistic->listActiveWorlds();
    }


    public function help()
    {
        $help = new Help($this->container);

        $help->addCommand('stat:check', 'Check working servers of game');
        $help->addCommand('stat:load', 'Load statistic from server of game', [
            '--world' => [],
            '--email' => [],
        ]);
        $help->addCommand('stat:update', 'Update statistic in database from loaded json-files', [
            '--world' => [],
            '--email' => [],
            '--limit' => [],
        ]);
        $help->addCommand('stat:clear', 'Clear statistic for one world', [
            '--world' => ['required' => true],
        ]);
        $help->addCommand('stat:list', 'Display active worlds', []);

        $help->addOption('--world, -w', 'process only for one world');
        $help->addOption('--email, -e', 'level for send report to email [error, all]');
        $help->addOption('--limit, -l', 'set limit processing json-files per call command');

        $help->display($this->getDescription().PHP_EOL);
    }


    public static function getDescription()
    {
        return 'Statistic module';
    }
}
