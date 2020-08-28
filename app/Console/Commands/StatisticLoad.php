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
use App\Repositories\WorldRepository;
use App\Services\Json;
use App\Services\Wofh;
use App\StatLog;
use App\Traits\CliColors;
use App\Traits\CliHelper;
use App\Traits\StatLogger;
use App\World;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class StatisticLoad extends Command
{
    use CliColors;
    use CliHelper;
    use StatLogger;


    /** @var string */
    protected $signature = 'stat:load
                            {--w= : Process only for one world (--w=ru23)}';

    /** @var string */
    protected $description = 'Load statistic from server of game';

    /** @var \App\Services\Wofh */
    private $wofh;

    /** @var false */
    private $useZip;

    /** @var false */
    private $limit;
    /**
     * @var \App\Services\Json
     */
    private $json;

    /** @var \App\Repositories\WorldRepository */
    protected $worldRepository;


    /**
     * Create a new command instance.
     *
     * @param \App\Services\Wofh                $wofh
     * @param \App\Services\Json                $json
     * @param \App\Repositories\WorldRepository $worldRepository
     */
    public function __construct(Wofh $wofh, Json $json, WorldRepository $worldRepository)
    {
        parent::__construct();

        $this->wofh = $wofh;
        $this->json = $json;
        $this->worldRepository = $worldRepository;

        //todo: params
        $this->useZip = false;
        $this->limit = false;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $oneWorld = $this->option('w');

        $this->alert('Download statistic');
        $this->line('The download interval is set to '.config('app.stat_load_interval').' hours');
        $this->line('Updating the status of worlds...');


        $checkStatus = $this->checkWorlds();
        if ($checkStatus !== true) {
            $this->line('Exit');
            $this->log([
                'operation' => StatLog::STAT_LOAD,
                'status'    => StatLog::STATUS_ERR,
                'world_id'  => null,
                'message'   => $checkStatus,
            ]);

            return 1;
        }

        $worlds = $this->worldRepository->working();
        $startTotal = microtime(true);

        $fs = Storage::disk('stat');

        /** @var \App\World $world */
        foreach ($worlds as $world) {

            if ($oneWorld && $oneWorld !== $world->sign) {
                continue;
            }

            $title = 'Load statistic '.strtoupper($world->sign);
            $this->output->section($title);

            $statisticLink = $this->wofh->getStatisticLink($world->id);
            // todo: 'statistic' брать из конфига или рутовую папку сделать (??)
            $realDataPath = str_replace('/', DIRECTORY_SEPARATOR, 'statistic/'.$world->sign);
            $this->line('SOURCE: '.$statisticLink);
            $this->line('DEST  : '.$this->trimPath($fs->path($realDataPath)));

            if (!is_dir($realDataPath)) {
                if (!$fs->makeDirectory($realDataPath)) {
                    $message = sprintf('Error. Can not create dir: %s', $realDataPath);
                    $this->error($message);
                    $this->log([
                        'operation' => StatLog::STAT_LOAD,
                        'status'    => StatLog::STATUS_ERR,
                        'world_id'  => $world->id,
                        'message'   => $message,
                    ]);
                    continue;
                }
            }

            if ($this->noDownloadRequired($world)) {
                $this->coloredLine('No download required', Color::YELLOW);
                $this->log([
                    'operation' => StatLog::STAT_LOAD,
                    'status'    => StatLog::STATUS_INFO,
                    'world_id'  => $world->id,
                    'message'   => 'No download required',
                ]);
                continue;
            }

            $start = microtime(true);
            $this->line('Start downloading...');

            $now = Carbon::now(new \DateTimeZone(config('app.timezone')));

            try {
                $data = Http::get($statisticLink)->throw()->json();
                // $data = $this->json->decode($fs->get($realDataPath.'/'.'ru44_test.json'));

                if (!$data) {
                    throw new \Exception('The server returned an empty response');
                }

                $this->coloredLine(
                    sprintf(' SUCCESS (%ss)', round(microtime(true) - $start, 2)),
                    Color::GREEN
                );

                $timestampFromData = $data['time'];
                $time = Carbon::createFromTimestamp($timestampFromData, config('app.timezone'));
                if ($time->greaterThan($now)) {
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
                    $this->coloredLine($message, Color::YELLOW);
                    $this->log([
                        'operation' => StatLog::STAT_LOAD,
                        'status'    => StatLog::STATUS_WARN,
                        'world_id'  => $world->id,
                        'message'   => $message,
                    ]);
                    continue;
                }

                if (!$fs->put($realDataPath.'/'.$filename, $stat)) {
                    $message = 'Error saving file: '.$filename;
                    $this->error($message);
                    $this->log([
                        'operation' => StatLog::STAT_LOAD,
                        'status'    => StatLog::STATUS_ERR,
                        'world_id'  => $world->id,
                        'message'   => $message,
                    ]);
                    continue;
                }

                $world->stat_loaded_at = $time;
                $world->save();

                $this->line('FILE  : '.$filename);
                $this->coloredLine('New last time download '.
                    $time->format(Wofh::STD_DATETIME).
                    ' '.
                    $time->timezone->getName(), Color::GREEN);

                $this->log([
                    'operation' => StatLog::STAT_LOAD,
                    'status'    => StatLog::STATUS_OK,
                    'world_id'  => $world->id,
                    'message'   => 'SUCCESS. '.
                        'File ['.$filename.']. '.
                        'LoadedAt: '.$time->format(Wofh::STD_DATETIME).' '.$time->timezone->getName(),
                ]);

            } catch (\Exception $e) {
                $message = $e->getMessage();
                $this->error(' ERROR');
                $this->error($message);
                $this->log([
                    'operation' => StatLog::STAT_LOAD,
                    'status'    => StatLog::STATUS_ERR,
                    'world_id'  => $world->id,
                    'message'   => $message,
                ]);
                continue;
            }
        }

        $this->output->newLine();
        $this->coloredLine(
            sprintf('Complete. Total time: (%ss)', round(microtime(true) - $startTotal, 2)),
            Color::MAGENTA
        );

        return 0;
    }


    protected function noDownloadRequired(World $world)
    {
        $statLoadedAt = $world->stat_loaded_at;

        $this->output->write('Last time download: ');

        if (!$statLoadedAt) {

            $this->coloredLine('never', Color::GREEN);

            return false;

        } else {

            $this->line($statLoadedAt->format(Wofh::STD_DATETIME).' '.$statLoadedAt->timezone->getName());

            $now = Carbon::now(new \DateTimeZone(config('app.timezone')));

            $this->line('Current time:       '.$now->format(Wofh::STD_DATETIME).' '.$now->timezone->getName());

            $diff = $now->diffAsCarbonInterval($statLoadedAt);

            try {
                $this->line('Diff time:          '.$diff->locale('en')->forHumans());
            } catch (\Exception $e) {
                $message = $e->getMessage();
                $this->error('[ERR] '.$message);
                $this->log([
                    'operation' => StatLog::STAT_LOAD,
                    'status'    => StatLog::STATUS_ERR,
                    'world_id'  => $world->id,
                    'message'   => $message,
                ]);

                return false;
            }

            if ($diff->totalHours >= config('app.stat_load_interval')) {
                return false;
            }

        }

        return true;
    }
}
