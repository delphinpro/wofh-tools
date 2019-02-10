<?php

namespace Dolphin\Commands;


use Dolphin\Command;
use Dolphin\CommandInterface;
use Dolphin\Commands\Migration\Migrator;
use Dolphin\Help;
use Psr\Container\ContainerInterface;


/**
 * Class Migration
 * Dolphin command line interface
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016—2019 delphinpro
 * @license     Licensed under the MIT license
 * @package     Dolphin\Commands
 */
class Migration extends Command implements CommandInterface
{
    protected $aliases = [
        '-d' => '--desc',
    ];

    /** @var Migrator */
    private $migrator;


    public static function getDescription()
    {
        return 'Migration manager';
    }


    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $migrationsPath = DIR_ROOT.DIRECTORY_SEPARATOR.'.migrations';

        $this->migrator = new Migrator(
            $migrationsPath,
            $this->params,
            $this->console,
            $this->config,
            $this->db
        );
    }


    public function create()
    {
        $this->migrator->create();
    }


    public function migrate()
    {
        $this->migrator->migrate();
    }


    public function rollback()
    {
        $this->migrator->rollback();
    }


    public function status()
    {
        $this->migrator->status();
    }


    public function help()
    {
        $help = new Help($this->container);

        $help->addCommand('migration:create', 'Create new migration', [
            '--desc="some description"' => [],
        ]);
        $help->addCommand('migration:migrate', 'Exec migrations');
        $help->addCommand('migration:rollback', 'Rollback last migration');
        $help->addCommand('migration:status', 'Show status migrations');

        $help->addOption('-d, --desc', 'Description for migration');

        $help->display($this->getDescription().PHP_EOL);
    }
}
