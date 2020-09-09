<?php

namespace App\Console\Commands;


use App\Console\Services\Console;
use App\Console\Services\Statistic\StatisticLogger;
use App\Console\Statistic\Updater;
use App\Console\Traits\Helper;
use App\Repositories\WorldRepository;
use App\Services\Wofh;
use App\StatLog;
use Illuminate\Console\Command;


class StatisticUpdate extends Command
{
    use Helper;


    protected $signature = 'stat:update
                            {world? : Process only for one world (ex. ru23)}
                            {--l|limit= : Set limit processing json-files per call command}';

    protected $description = 'Update statistic in database from loaded json-files';

    /** @var \App\Repositories\WorldRepository */
    protected $worldRepository;
    /** @var \App\Services\Wofh */
    protected $wofh;
    /** @var \App\Console\Statistic\Updater */
    protected $updater;
    /** @var \App\Console\Services\Statistic\StatisticLogger */
    protected $logger;
    /** @var \App\Console\Services\Console */
    protected $console;

    /** @var string|null Сигнатура мира для обновления */
    protected $sign;
    /** @var int Ограничение количества файлов для обработки за один вызов команды */
    protected $limit;
    /** @var bool Флаг обработки одного мира или всех доступных */
    protected $single;
    /** @var bool Использовать zip-архив для хранения файлов данных */
    protected $zip;

    /**
     * Create a new command instance.
     *
     * @param \App\Repositories\WorldRepository               $worldRepository
     * @param \App\Services\Wofh                              $wofh
     * @param \App\Console\Statistic\Updater                  $updater
     * @param \App\Console\Services\Statistic\StatisticLogger $logger
     * @param \App\Console\Services\Console                   $console
     */
    public function __construct(
        WorldRepository $worldRepository,
        Wofh $wofh,
        Updater $updater,
        StatisticLogger $logger,
        Console $console
    ) {
        parent::__construct();
        $this->worldRepository = $worldRepository;
        $this->wofh = $wofh;
        $this->updater = $updater;
        $this->logger = $logger;
        $this->console = $console;
        app()->instance(Console::class, $console);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->console->alert('Update statistic');
        $this->getInput();

        try {
            $worlds = $this->getWorlds();

            $this->console->line('The number of updating worlds: '.$worlds->count());
            $this->console->line('Limit for update             : '.($this->limit ?: 'none'));

            foreach ($worlds as $world) {
                $this->console->title(sprintf('Update statistic for %s', $world->sign));
                if (!$world->statistic) {
                    $message = 'Statistic off for '.$world->sign.'. SKIP';
                    $this->logger->warn(StatLog::STATISTIC_UPDATE, $message, $world->id);
                    continue;
                }

                try {

                    $this->updater->updateWorld($world, $this->limit);

                } catch (\Throwable $e) {
                    $this->logger->error(StatLog::STATISTIC_UPDATE, $e->getMessage(), $world->id);
                    $this->console->stackTrace($e);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error(StatLog::STATISTIC_UPDATE, $e->getMessage());
            $this->console->stackTrace($e);
            return 1;
        }
        return 0;
    }

    /**
     * Получть входные параметры
     */
    protected function getInput()
    {
        $this->sign = $this->argument('world');
        $this->limit = (int)$this->option('limit');
        $this->zip = false;
        $this->single = !!$this->sign;
    }

    /**
     * Получить коллекцию миров для обновления
     *
     * @return \App\World[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    protected function getWorlds()
    {
        /** @var \App\World[]|\Illuminate\Database\Eloquent\Collection $worlds */
        $worlds = $this->worldRepository->all([
            'id',
            'sign',
            'statistic',
            'stat_loaded_at',
            'stat_updated_at',
        ]);

        if ($this->single) {
            $id = $this->wofh->signToId((string)$this->sign);
            $worlds = $worlds->filter(function ($world) use ($id) { return $world->id == $id; });
        }

        if ($this->single && !$worlds->count()) {
            throw new \Exception('Invalid argument {world} ['.$this->sign.']');
        }

        return $worlds;
    }
}
