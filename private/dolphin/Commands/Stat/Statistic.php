<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2014—2019 delphinpro
 * @license     licensed under the MIT license
 */


namespace Dolphin\Commands\Stat;


use Carbon\Carbon;
use Dolphin\Console;
use Dolphin\DolphinContainer;
use Psr\Container\ContainerInterface;
use WofhTools\Helpers\FileSystemException;
use WofhTools\Helpers\HttpCustomException;
use WofhTools\Helpers\JsonCustomException;
use WofhTools\Models\Worlds;


/**
 * Class Statistic
 *
 * @package Dolphin\Commands\Stat
 */
class Statistic extends DolphinContainer
{
    /** @var bool Get data from zip archive */
    private $useZip;

    /** @var bool|string */
    private $oneWorld;

    /** @var bool|int */
    private $limit;

    private $printLength = 26;


    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->useZip = false;

        $this->oneWorld = array_key_exists('world', $this->params)
            ? strtolower($this->params['world'])
            : false;

        $this->limit = array_key_exists('limit', $this->params)
            ? (int)$this->params['limit']
            : false;
    }


    public function load()
    {
        $this->console->write('Start download process');
        $this->console->write('The download interval is set to '.$this->config->statLoadInterval.' hours');
        $worlds = Worlds::getWorking();
        $startTotal = microtime(true);

        /** @var Worlds $world */
        foreach ($worlds as $world) {

            if ($this->oneWorld && $this->oneWorld !== $world->sign) {
                continue;
            }

            $linkStatistic = $this->wofh->getStatisticLink($world->id);
            $realDataPath = $this->fs->path('/data/statistic/'.$world->sign.'/'); // todo: брать из конфига
            $title = 'Load statistic '.strtoupper($world->sign);

            $this->console->lineSingle(Console::LINE_WIDTH, Console::CYAN);
            $this->console->write($title, Console::CYAN, false);
            $this->console->write(' '.str_replace('https://', '', $linkStatistic));

            if (!is_dir($realDataPath)) {
                try {
                    $this->fs->mkdir($realDataPath, 0777);
                } catch (FileSystemException $e) {
                    $this->console->error(sprintf('Error. Can not create dir: %s', $realDataPath));
                    continue;
                }
            }

            if (!$this->requiredDownload($world)) {
                $this->console->write('No download required', Console::YELLOW);
                continue;
            }

            $start = microtime(true);
            $this->console->writeFixedWidth('Start downloading', $this->printLength);

            try {

                $stat = $this->http->readUrl($linkStatistic);
//                $stat = file_get_contents(DIR_ROOT.'/data/statistic/ru37/ru37_1549709993_2019-02-09_10-59-53.json');
                $data = $this->json->decode($stat, true);
                $timestampFromFile = $data['time'];
                $stat = $this->json->encode($data);

            } catch (HttpCustomException $e) {

                $this->console->error(' FAIL');
                $this->console->error($e->getMessage());
                continue;

            } catch (JsonCustomException $e) {

                $this->console->error(' FAIL');
                $this->console->error($e->getMessage());
                continue;

            }

            $this->console->write(
                sprintf('success (%ss)', round(microtime(true) - $start, 2)),
                Console::GREEN
            );

            /** @var Carbon $tObj */
            $tObj = Carbon::createFromTimestamp($timestampFromFile);
            $tObj->setTimezone('UTC');

            $filename = sprintf('%s_%s_%s.json',
                $world->sign,
                $tObj->timestamp,
                $tObj->format('Y-m-d_H-i-s')
            );

            if (file_exists($realDataPath.DIRECTORY_SEPARATOR.$filename)) {
                $world->time_of_loaded_stat = Carbon::createFromTimestamp($timestampFromFile);
                $world->save();
                $this->console->write('File exists: '.$filename, Console::YELLOW);
                continue;
            }

            try {
                $this->fs->saveFile($realDataPath.DIRECTORY_SEPARATOR.$filename, $stat, 0777);
            } catch (FileSystemException $e) {
                $this->console->error('Error saving file');
                $this->console->error('   '.$filename);
                continue;
            }

            $world->time_of_loaded_stat = Carbon::createFromTimestamp($timestampFromFile);
            $world->save();

            $this->console->writeFixedWidth('Filename', $this->printLength);
            $this->console->write($filename);
            $this->console->writeFixedWidth('New last time download', $this->printLength, '.',
                Console::GREEN);
            $this->console->write($tObj->format(STD_DATETIME).' '.$tObj->timezone->getName(),
                Console::GREEN);
        }

        $this->console->lineDouble(Console::LINE_WIDTH, Console::MAGENTA);
        $this->console->write(
            sprintf('Complete. Total time: (%ss)', round(microtime(true) - $startTotal, 2)),
            Console::MAGENTA
        );
    }


    private function requiredDownload(Worlds $world)
    {
        /** @var Carbon $lastLoadStat */
        $lastLoadStat = $world->time_of_loaded_stat;

        $this->console->writeFixedWidth('Last time download', $this->printLength);

        if (!$lastLoadStat) {

            $this->console->write('never', Console::GREEN);

            return true;

        } else {

            $this->console->write($lastLoadStat->format(STD_DATETIME).' '.$lastLoadStat->timezone->getName());

            $now = new Carbon();

            $this->console->writeFixedWidth('Current time', $this->printLength);
            $this->console->write($now->format(STD_DATETIME).' '.$now->timezone->getName());

            $diff = $now->diffAsCarbonInterval($lastLoadStat);

            $this->console->writeFixedWidth('Diff time', $this->printLength);
            $this->console->write($diff->forHumans());

            if ($diff->totalHours >= $this->config->statLoadInterval) {
                return true;
            }

        }

        return false;
    }


    /**
     * @throws \Exception
     */
    public function update()
    {
        $id = $this->wofh->signToId((string)$this->oneWorld);
        $worlds = Worlds::getWorking($id);

        if ($this->oneWorld && !count($worlds)) {
            throw new \Exception('Invalid parameter --world');
        }

        $this->console->writeFixedWidth('The number of updated worlds:', 33);
        $this->console->write(count($worlds), Console::CYAN);
        $this->console->writeFixedWidth('Limit for update:', 33);
        $this->console->write($this->limit ?: 'none', Console::CYAN);

        /** @var Worlds $world */
        foreach ($worlds as $world) {
            if (!$world->statistic) {
                $this->console->write($world->sign.' skip', Console::YELLOW);
                continue;
            }

            $this->updateWorld($world);
        }
    }


    /**
     * @throws \Exception
     */
    public function clear()
    {
        if (!$this->oneWorld) {
            throw new \Exception('Required parameter --world');
        }

        $world = Worlds::getBySign($this->oneWorld);

        if (!$world) {
            throw new \Exception('Invalid parameter --world');
        }

        try {

            $this->db->beginTransaction();

            $sqls = [
                "TRUNCATE `z_{$world->sign}_towns`;",
                "TRUNCATE `z_{$world->sign}_towns_stat`;",
                "TRUNCATE `z_{$world->sign}_accounts`;",
                "TRUNCATE `z_{$world->sign}_accounts_stat`;",
                "TRUNCATE `z_{$world->sign}_countries`;",
                "TRUNCATE `z_{$world->sign}_countries_stat`;",
                "TRUNCATE `z_{$world->sign}_countries_diplomacy`;",
                "TRUNCATE `z_{$world->sign}_events`;",
                "TRUNCATE `z_{$world->sign}_common`;",
                "UPDATE wt_worlds SET time_of_loaded_stat = null, time_of_updated_stat = null WHERE id = {$world->id};",
            ];

            foreach ($sqls as $sql) {
                $this->db->statement($sql);
            }

//            $world->time_of_loaded_stat = null;
//            $world->save();

            $this->db->commit();

            $this->console->write('Statistic cleared for '.$world->sign, Console::GREEN);

        } catch (\Exception $e) {

            if ($this->db->transactionLevel() > 0) {
                $this->db->rollBack();
            }

            throw $e;

        }
    }


    public function listActiveWorlds()
    {
        $worlds = Worlds::getWorking();
        /** @var Worlds $world */
        $this->console->write('Active worlds:', Console::GREEN);
        foreach ($worlds as $world) {
            $this->console->write($world->sign);
        }
    }

    //== ====================================================================================== ==//
    //== Private methods
    //== ====================================================================================== ==//

    /**
     * @param Worlds $world
     *
     * @throws \Exception
     */
    private function updateWorld(Worlds $world)
    {
        $this->console->lineSingle(Console::LINE_WIDTH, Console::CYAN);
        $this->console->write(sprintf('Update statistic for %s ', $world->sign), Console::CYAN);

//        $statPath = '/tmp/stat/';
//        $realStatPath = Helpers\FileSystem::resolve($statPath).DIRECTORY_SEPARATOR;
//        $realDataPath = Helpers\FileSystem::resolve($statPath.$world->lowerSign()).DIRECTORY_SEPARATOR;
//        $realDataZip = Helpers\FileSystem::resolve($statPath.$world->lowerSign().'.zip');

        $statDirectory = $this->fs->join($this->config->statisticPath, $world->sign);
        $realDataPath = $this->fs->path($statDirectory);
        $this->console->write($realDataPath);

//        Console::writeLn('DataPath: ' . $realDataPath, Console::C_RED);
//        Console::writeLn('DataZip : ' . $realDataZip, Console::C_RED);

//        if (is_file($realDataZip) && is_readable($realDataZip)) {
        $this->useZip = false;
//        }

        if (!$this->useZip && !is_dir($realDataPath)) {
            throw new \Exception('Error. Data directory not found: '.$realDataPath);
        }

//        $realLastUpdateTime = $world->getRealLastUpdateTime($this->useZip);
//        $realLastUpdateText = ($realLastUpdateTime ? $realLastUpdateTime->format(STD_DATETIME) : 'never');
//
//        $realLastDownloadTime = $this->_getRealLastDownloadTime($realStatPath, $world->lowerSign());
//        $realLastDownloadText = ($realLastDownloadTime ? $realLastDownloadTime->format(STD_DATETIME) : 'never');
        $textLastTimeDownload = $world->time_of_loaded_stat
            ? $world->time_of_loaded_stat->format('Y-m-d H:i:s [P]') : 'never';
        $textLastTimeUpdate = $world->time_of_updated_stat
            ? $world->time_of_updated_stat->format('Y-m-d H:i:s [P]') : 'never';

        $fixedWidth = 30;
        $this->console->writeFixedWidth('Last time download ', $fixedWidth);
        $this->console->write($textLastTimeDownload);
        $this->console->writeFixedWidth('Last time update ', $fixedWidth);
        $this->console->write($textLastTimeUpdate);

        $dataFiles = $this->getFileNames($realDataPath, $world);
        // pre($dataFiles);

        $counter = 0;

        foreach ($dataFiles as $dataFile) {
//            $this->console->write($dataFile);

            $startTime = microtime(true);

            $filename = $realDataPath.DIRECTORY_SEPARATOR.$dataFile;
            if (!is_file($filename)) {
                if (!is_dir($filename)) {
                    $this->console->error('Error. Data file not readable: '.$filename.$dataFile);
                }
                continue;
            }

            $counter++;

            $updater = new Updater($this->container, $world);

            try {
                $this->console->write('Check tables... ', false);
                $updater->checkTables($world);
                $this->console->write('OK', Console::GREEN);

            } catch (\PDOException $e) {

                $this->console->error('FAIL');
                $this->console->error($e->getMessage());
                $this->console->write('SKIP', Console::YELLOW);
                continue;

            }

            $updater->update($realDataPath, $dataFile);
            unset($updater);

            $fixedWidth = 30;
            $endTime = round(microtime(true) - $startTime, 1);
            $memory = preg_replace('~(\d(?=(?:\d{3})+(?!\d)))~s', '\\1.',
                memory_get_usage(true));
            $this->console->writeFixedWidth('  total time:', $fixedWidth, ' ');
            $this->console->write($endTime.' s', Console::RED);
            $this->console->writeFixedWidth('  memory usage:', $fixedWidth, ' ');
            $this->console->write($memory.' of '.ini_get('memory_limit'), Console::MAGENTA);

            if ($this->limit) {
                $this->console->writeFixedWidth('  limit:', $fixedWidth, ' ');
                $this->console->write($counter.' of '.$this->limit);
            }

            if ($this->limit && $counter >= $this->limit) {
                $this->console->lineSingle(Console::LINE_WIDTH, Console::YELLOW);
                $this->console->write('LIMIT: '.$this->limit.'. STOP.', Console::YELLOW);
                break;
            }
        }
    }


    /**
     * @param string $statisticPath
     * @param Worlds $world
     *
     * @return array
     * @throws \Exception
     */
    private function getFileNames(string $statisticPath, Worlds $world): array
    {

//        if ($this->useZip) {
//        $fileNames = [];
//            $zipFile = Helpers\FileSystem::join($statisticPath, $world->lowerSign().'.zip');
////            Console::writeLn($zipFile);
//            $zip = new \ZipArchive();
//            $zip->open($zipFile);
//
//            for ($i = 0; $i < $zip->numFiles; $i++) {
//                $fileNames[] = $zip->getNameIndex($i);
//            }
//
//            $zip->close();
//        } else {
        $fileNames = $this->fs->scanDir($statisticPath);

        if (!is_array($fileNames)) {
            throw new \Exception("Error. Can't read data files");
        }
//        }

        if (empty($fileNames)) {
            throw new \Exception(sprintf('Error. Data files not found: %s*.json', $world->sign));
        }

        $ts = $world->time_of_updated_stat->timestamp;

        // Берем только те, которые не внесены в базу
        $fileNames = array_filter($fileNames, function ($item) use ($ts) {
            if (pathinfo($item, PATHINFO_EXTENSION) != 'json') {
                return false;
            }

            $chunks = explode('_', $item);
            $time = (int)$chunks[1];

            if ($time <= $ts) {
                return false;
            }

            return true;
        });

        sort($fileNames);

        return $fileNames;
    }
}
