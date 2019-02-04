<?php

namespace Dolphin\Commands;


use Dolphin\BaseCommand;
use Dolphin\Commands\Migration\Migrator;
use Dolphin\Help;


/**
 * Class Migration
 * Dolphin command line interface
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016—2019 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin\Commands
 */
class Migration extends BaseCommand
{
    public function __construct()
    {
        $this->aliases = [
            '-d' => '--desc',
        ];
    }


    public function getDescription()
    {
        return 'Migration manager';
    }


    protected function create()
    {
        try {

            $migrator = new Migrator(DIR_ROOT.DIRECTORY_SEPARATOR.'.migrations', $this->arguments);
            $migrator->create();

        } catch (\Exception $e) {

            exit(__FILE__.':'.__LINE__.' '.$e->getMessage());

        }
    }


    protected function migrate()
    {
        $migrator = new Migrator(DIR_ROOT.DIRECTORY_SEPARATOR.'.migrations', $this->arguments);
        $migrator->migrate();
    }


    protected function rollback()
    {
        $migrator = new Migrator(DIR_ROOT.DIRECTORY_SEPARATOR.'.migrations', $this->arguments);
        $migrator->rollback();
    }


    protected function status()
    {
        $migrator = new Migrator(DIR_ROOT.DIRECTORY_SEPARATOR.'.migrations', $this->arguments);
        $migrator->status();
    }


    protected function help()
    {
        $help = new Help();

        $help->addString('Commands:');
        $help->addCommand('migration:create -d="some description"', 'Create new migration');
        $help->addCommand('migration:migrate', 'Exec migrations');
        $help->addCommand('migration:rollback', 'Rollback last migration');
        $help->addCommand('migration:status', 'Show status migrations');

        $help->addString('Options:');
        $help->addOption('-d, --desc', 'Description for migration');

        $help->display();
    }
}
