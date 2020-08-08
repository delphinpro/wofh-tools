<?php

namespace Dolphin\Commands\Stat;


use Dolphin\Console;
use WofhTools\Models\Worlds;
use Dolphin\DolphinContainer;
use Psr\Container\ContainerInterface;
use Dolphin\Commands\Stat\Traits\UpdaterChecker;


/**
 * Class Updater
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2013—2019 delphinpro
 * @license     licensed under the MIT license
 *
 * @package     Dolphin\Commands\Stat
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
     * @param string      $realDataPath
     * @param string      $dataFile
     * @param string|null $previousDataFile
     *
     * @throws \WofhTools\Helpers\FileSystemException
     * @throws \WofhTools\Helpers\JsonCustomException
     * @throws \Exception
     */
    public function update(string $realDataPath, string $dataFile, $previousDataFile)
    {
        $dataStorage = new DataStorage($this->container, $this->world);
        $dataStorage->loadFromFile($realDataPath.DIRECTORY_SEPARATOR.$dataFile);
        $this->printHeader($dataStorage->getTime()->format("d-m-Y H:i:s [P]"));
        $this->checkStructure();

        try {

            $this->world->beginUpdate();

            $this->db->beginTransaction();

            $dataStorage->fixTotalAccountsValue();
            $dataStorage->normalize('curr');
            $dataStorage->calculate('curr');
            $dataStorage->filter('curr');

            $this->console->writeFixedWidth('Reading previous data', Updater::PRINT_WIDTH);
            if ($previousDataFile) {
                $dataStorage->readPreviousIndex($realDataPath.DIRECTORY_SEPARATOR.$previousDataFile);
                $dataStorage->normalize('prev');
                $dataStorage->calculate('prev');
                $dataStorage->filter('prev');
            } else {
//                $this->firstInsert = true;
                $this->console->write('No previous data', Console::YELLOW);

//                $this->insertTownIds = array_keys($this->curr['towns']);
//                $this->insertAccountIds = array_keys($this->curr['accounts']);
//                $this->insertCountryIds = array_keys($this->curr['countries']);
            }

            $dataStorage->unsetRaw();

            $dataStorage->checkEventsOfTowns();
            $dataStorage->checkEventsOfAccounts();
            $dataStorage->checkEventsOfCountries();

            $dataStorage->calculateDeltas();

            $dataStorage->dump();

            $dataStorage->update();

            $this->db->commit();

            $this->world->endUpdate($dataStorage->getTime());

        } catch (\Exception $e) {

            if ($this->db->transactionLevel() > 0) {
                $this->db->rollBack();
            }

            $this->world->endUpdate();


            throw $e;

        } finally {

            unset($dataStorage);

        }
    }


    public function printHeader(string $titleTime): void
    {
        $titleText = ' '.$this->world->sign.' '.$titleTime.' ';
        $title = str_pad($titleText, Console::LINE_WIDTH, '-', STR_PAD_BOTH);
        $this->console->write($title, Console::CYAN);
    }
}
