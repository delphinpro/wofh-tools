<?php

namespace Dolphin\Commands\Stat;


use Carbon\Carbon;
use Dolphin\Commands\Stat\Traits\UpdaterChecker;
use Dolphin\Console;
use Dolphin\DolphinContainer;
use Psr\Container\ContainerInterface;
use WofhTools\Models\Worlds;


/**
 * Class Updater
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2013—2019 delphinpro
 * @license     licensed under the MIT license
 *
 * @package     Dolphin\Commands\Stat
 *
 * @property array  raw
 * @property Carbon time
 */
class Updater extends DolphinContainer
{
    use UpdaterChecker;

    const PRINT_WIDTH = 40;

    /** @var Worlds */
    private $world;


    public function __construct(ContainerInterface $container, Worlds $world)
    {
        parent::__construct($container);
        $this->world = $world;
    }


    /**
     * @param string $realDataPath
     * @param string $dataFile
     *
     * @throws \Exception
     */
    public function update(string $realDataPath, string $dataFile)
    {
        $dataStorage = new DataStorage($this->container, $this->world);
        $dataStorage->loadFromFile($realDataPath.DIRECTORY_SEPARATOR.$dataFile);
        $this->printHeader($dataStorage->getTime()->format("d-m-Y H:i:s [P]"));
        $this->checkStructure();

        try {

            $this->world->beginUpdate();

//            $tableName = 'towns';
//            $table = $this->db->table('z_'.$this->world->sign.'_'.$tableName.'_stat');
//            $t = $table->select('UNIX_TIMESTAMP(stateDate)')->max('stateDate');
//            $t = Carbon::createFromTimeString($t);
//            pre(var_dump($t));

            $this->db->beginTransaction();

            $dataStorage->normalize();
            $dataStorage->calculate();
            $dataStorage->filter();
            $dataStorage->readPreviousIndex();

            $dataStorage->update();

//            $events = new Events($this->world, $data);
//            $updaterTowns = new Towns($this->world, $data, $events, 'towns', $this->container);
//            $updaterAccounts = new Accounts($world, $data, $events, 'accounts');
//            $updaterCountries = new Countries($world, $data, $events, 'countries');

//            $updaterTowns->update();
//            $updaterAccounts->update();
//            $updaterCountries->update();
//            $events->update();

//            $this->updateCommonData($world, $data, $events);

            $this->db->commit();

            $this->world->endUpdate($dataStorage->getTime());

        } catch (\Exception $e) {

            if ($this->db->transactionLevel() > 0) {
                $this->db->rollBack();
            }

            $this->world->endUpdate();


            throw $e;

        } finally {

            unset($data);
            unset($deletedAccounts, $deletedCountries);
            unset($updaterCountries, $updaterAccounts, $updaterTowns, $events);
            unset($updaterEvents);

        }
    }


    public function printHeader(string $titleTime): void
    {
        $titleText = ' '.$this->world->sign.' '.$titleTime.' ';
        $title = str_pad($titleText, Console::LINE_WIDTH, '-', STR_PAD_BOTH);
        $this->console->write($title, Console::CYAN);
    }
}
