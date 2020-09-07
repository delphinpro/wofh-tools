<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Commands;


use App\Console\Color;
use App\Console\Services\Statistic\StatisticLogger;
use App\Console\Traits\Helper;
use App\Repositories\WorldRepository;
use App\Services\Json;
use App\Services\Wofh;
use App\StatLog;
use App\Traits\CliColors;
use App\World;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class StatisticLoad extends Command
{
    use CliColors;
    use Helper;


    const FILENAME_PATTERN = '~_(\d+)\.json$~';


    /** @var string */
    protected $signature = 'stat:load
                            {world? : Process only for one world (ex. ru23)}';

    /** @var string */
    protected $description = 'Load statistic from server of game';

    /** @var \App\Services\Wofh */
    private $wofh;

    /**
     * @var \App\Services\Json
     */
    private $json;

    /** @var \App\Repositories\WorldRepository */
    protected $worldRepository;

    /** @var \App\Console\Services\Statistic\StatisticLogger */
    protected $logger;


    /**
     * Create a new command instance.
     *
     * @param \App\Services\Wofh                              $wofh
     * @param \App\Services\Json                              $json
     * @param \App\Repositories\WorldRepository               $worldRepository
     * @param \App\Console\Services\Statistic\StatisticLogger $logger
     */
    public function __construct(Wofh $wofh, Json $json, WorldRepository $worldRepository, StatisticLogger $logger)
    {
        parent::__construct();

        $this->wofh = $wofh;
        $this->json = $json;
        $this->worldRepository = $worldRepository;
        $this->logger = $logger;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sign = $this->argument('world');

        $this->alert('Download statistic');
        $this->line('The download interval is set to '.config('app.stat_load_interval').' hours');
        $this->line('Updating the status of worlds...');


        $checkStatus = $this->checkWorlds($printTable = false);
        if ($checkStatus !== true) {
            $this->logger->error(StatLog::STATISTIC_LOAD, $checkStatus);
            return 1;
        }

        $worlds = $this->worldRepository->working();
        $startTotal = microtime(true);

        $fs = Storage::disk(config('app.stat_disk'));

        /** @var \App\World $world */
        foreach ($worlds as $world) {

            if ($sign && $sign !== $world->sign) {
                continue;
            }

            $this->output->section('Load statistic '.strtoupper($world->sign));

            $statisticLink = $this->wofh->getStatisticLink($world->id);
            $realDataPath = 'statistic/'.$world->sign;
            $this->line('SOURCE: '.$statisticLink);
            $this->line('DEST  : '.$this->trimPath($fs->path($realDataPath)));

            if (!$fs->exists($realDataPath)) {
                if (!$fs->makeDirectory($realDataPath)) {
                    $message = sprintf('Error. Can not create dir: %s', $realDataPath);
                    $this->logger->error(StatLog::STATISTIC_LOAD, $message, $world->id);
                    continue;
                }
            }

            $this->line('Destination directory is ready');

            if ($this->noDownloadRequired($world, $realDataPath)) {
                $this->logger->info(StatLog::STATISTIC_LOAD, 'No download required', $world->id);
                continue;
            }

            $start = microtime(true);
            $this->line('Start downloading...');

            $now = Carbon::now(new CarbonTimeZone(config('app.timezone')));

            try {
                $data = Http::get($statisticLink)->throw()->json();
                // $data = $this->json->decode($fs->get($realDataPath.'/'.config('app.stat_test_filename')));

                if (!$data) {
                    throw new \Exception('The server returned an empty response');
                }

                $this->colorLine(
                    sprintf('Download SUCCESS (%ss)', round(microtime(true) - $start, 2)),
                    Color::GREEN
                );

                $timestampFromData = $data['time'];
                $time = Carbon::createFromTimestamp($timestampFromData, config('app.timezone'));
                if ($time->greaterThan($now)) {
                    $this->warn('Timestamp from server: '.$time->format(Wofh::STD_DATETIME).' '.$time->timezone->getName());
                    // С сервера приходит какой-то странный таймстамп.
                    // Временная метка из будущего.
                    // Или я туплю. В общем пока этот хак здесь побудет
                    $time->addHours(-1 * ceil($time->diffAsCarbonInterval($now)->totalHours));
                    $data['time'] = $time->timestamp;
                }

                $stat = $this->json->encode($data);

                $filename = sprintf('%s_%s.json',
                    $world->sign,
                    $time->timestamp
                );

                if ($fs->exists($realDataPath.'/'.$filename)) {
                    $world->stat_loaded_at = $time;
                    $world->save();
                    $message = 'File exists: '.$filename;
                    $this->logger->warn(StatLog::STATISTIC_LOAD, $message, $world->id);
                    continue;
                }

                if (!$fs->put($realDataPath.'/'.$filename, $stat)) {
                    $message = 'Error saving file: '.$filename;
                    $this->logger->error(StatLog::STATISTIC_LOAD, $message, $world->id);
                    continue;
                }

                $world->stat_loaded_at = $time;
                $world->save();

                $this->line('FILE  : '.$filename);
                $this->colorLine('New last time download '.
                    $time->format(Wofh::STD_DATETIME).
                    ' '.
                    $time->timezone->getName(), Color::GREEN);

                $this->logger->ok(
                    StatLog::STATISTIC_LOAD,
                    'SUCCESS. '.
                    'File ['.$filename.']. '.
                    'LoadedAt: '.$time->format(Wofh::STD_DATETIME).' '.$time->timezone->getName(),
                    $world->id
                );

            } catch (\Exception $e) {
                $message = get_class($e).': '.$e->getMessage();
                $this->logger->error(StatLog::STATISTIC_LOAD, $message, $world->id);
                continue;
            }
        }

        $this->output->newLine();
        $this->colorLine(
            sprintf('Complete. Total time: (%ss)', round(microtime(true) - $startTotal, 2)),
            Color::MAGENTA
        );

        return 0;
    }


    /**
     * @param \App\World $world
     * @param string     $dataPath
     *
     * @return \Carbon\Carbon|null
     */
    protected function getStatLoadedAt(World $world, string $dataPath)
    {
        $statLoadedAt = $world->stat_loaded_at;

        if (!$statLoadedAt) {
            $fs = Storage::disk(config('app.stat_disk'));
            $allFiles = collect($fs->allFiles($dataPath))
                ->filter(function ($item) { return preg_match(self::FILENAME_PATTERN, $item); })
                ->sort();

            if (!$allFiles->count()) {
                return null;
            }

            $lastFile = $allFiles->pop();

            if (!preg_match(self::FILENAME_PATTERN, $lastFile, $m)) {
                return null;
            }

            $statLoadedAt = Carbon::createFromTimestamp($m[1], config('app.timezone'));
            $world->stat_loaded_at = $statLoadedAt;
            $world->save();
        }

        return $statLoadedAt;
    }


    protected function noDownloadRequired(World $world, string $dataPath)
    {
        $statLoadedAt = $this->getStatLoadedAt($world, $dataPath);

        if (!$statLoadedAt) {

            $this->line('Last time download: '.$this->makeString('never', Color::GREEN));
            return false;

        } else {

            try {

                $now = Carbon::now(new CarbonTimeZone(config('app.timezone')));
                $diff = $now->diffAsCarbonInterval($statLoadedAt);

                $this->line('Last time download: '.$statLoadedAt->format(Wofh::STD_DATETIME).' '.$statLoadedAt->timezone->getName());
                $this->line('Current time:       '.$now->format(Wofh::STD_DATETIME).' '.$now->timezone->getName());
                $this->line('Diff time:          '.$diff->locale('en')->forHumans($short = true).' [ '.$diff->totalHours.'h ]');

            } catch (\Exception $e) {

                $this->logger->error(StatLog::STATISTIC_LOAD, $e->getMessage(), $world->id);
                return false;

            }

            if ($diff->totalHours >= config('app.stat_load_interval')) {
                return false;
            }

        }

        return true;
    }
}
