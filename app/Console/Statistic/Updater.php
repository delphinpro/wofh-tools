<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic;

use App\Console\Services\Console;
use App\Console\Statistic\Data\DataStorage;
use App\Console\Statistic\EventProcessor\EventProcessor;
use App\Console\Statistic\StorageProcessor\StorageProcessor;
use App\Models\World;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function Helpers\Statistic\parseFilename;

function humanize($bytes, $decimals = 2): string
{
    if ($bytes < 1024) return $bytes.' B';
    $factor = floor(log($bytes, 1024));
    return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)).['B', 'KB', 'MB', 'GB', 'TB', 'PB'][$factor];
}

class Updater
{
    use Checker;
    use Dumper;

    protected Console $console;
    protected Filesystem $fs;

    protected World $world;

    protected bool $zip;
    protected int $limit;
    protected bool $silent;

    protected ?string $prevFile;
    protected ?Collection $files;

    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    /** @throws \Throwable */
    public function updateWorld(World $world, Filesystem $filesystem, array $params)
    {
        $this->prevFile = null;
        $this->files = null;
        $this->world = $world;
        $this->fs = $filesystem;

        $this->limit = $params['limit'] ?? 0;
        $this->zip = $params['zip'] ?? false;
        $this->silent = $params['silent'] ?? false;

        $time = microtime(true);
        $dataPath = 'statistic/'.$world->sign;

        if (!$this->zip && !$this->fs->exists($dataPath)) {
            throw new \Exception('Data directory not found: '.$dataPath);
        }

        $this->checkTables($world);
        $this->scanFiles($dataPath);
        $this->printHeaderWorld($dataPath);

        $counter = 0;
        foreach ($this->files as $filename) {
            $counter++;
            $this->printHeaderFile($filename, $this->limit, $counter);
            $this->processFile($filename);
            $this->prevFile = $filename;

            if ($this->limit && $counter >= $this->limit) {
                $this->console->line();
                $this->console->line('Limited count files: '.$this->limit.'. STOP.', [Console::BG_CYAN, Console::WHITE]);
                break;
            }
        }

        $this->console->line('Total time: '.t($time), [Console::CYAN]);
        $this->printMemoryUsage();
    }

    /** @throws \Exception */
    protected function scanFiles(string $dataPath)
    {
        $pattern = $this->filenamePattern();
        $files = collect($this->fs->files($dataPath))
            ->map(fn($filename) => parseFilename($filename, $pattern))
            ->filter(fn($file) => !!$file['time']);

        if (!$files->count()) throw new \Exception(sprintf('Data files not found: %s', $pattern));

        $timestamp = $this->world->stat_updated_at ? $this->world->stat_updated_at->timestamp : 0;

        // Берем только те, которые не внесены в базу
        $newFiles = $files->filter(fn($file) => $file['time'] > $timestamp)->sort();

        $previousFile = $files->diffUsing($newFiles, fn($file1, $file2) => $file1['time'] - $file2['time'])
            ->sort()
            ->pop();

        $this->prevFile = $previousFile['name'] ?? null;
        $this->files = $newFiles->map(fn($file) => $file['name']);
    }

    /** @throws \Throwable */
    protected function processFile(string $filename)
    {
        DB::transaction(function () use ($filename) {

            $this->world->beginUpdate();

            $data = DataStorage::create($this->world, $this->fs, $this->silent);
            $data->loadFromFile($filename);
            $data->parse('Current:');

            $dataPrevious = DataStorage::create($this->world, $this->fs, $this->silent);
            $dataPrevious->loadFromFile($this->prevFile);
            $dataPrevious->parse('Previous:');

            if (!$dataPrevious->hasData()) $this->console->warn('No previous data');

            $eventProcessor = EventProcessor::create($this->world, $data, $dataPrevious);
            $eventProcessor->checkEvents();

            (new StorageProcessor(
                $this->console,
                $eventProcessor,
                $this->world,
                $dataPrevious,
                $data
            ))->save();

            $this->world->endUpdate($data->getTime());

        });
    }

    protected function printHeaderWorld(string $dataPath)
    {
        // @formatter:off
        $this->console->line('SOURCE: '.$this->console->trimPath($this->fs->path($dataPath)));
        $never = $this->console->makeString('never', Console::YELLOW);
        $this->console->line('Statistic loaded at   '.($this->world->stat_loaded_at ? $this->console->makeString($this->world->stat_loaded_at->format('d.m.Y H:i:s e'), Console::GREEN) : $never));
        $this->console->line('Statistic updated at  '.($this->world->stat_updated_at ? $this->console->makeString($this->world->stat_updated_at->format('d.m.Y H:i:s e'), Console::GREEN) : $never));
        // @formatter:on
    }

    protected function printHeaderFile(string $filename, $limit, $counter)
    {
        preg_match($this->filenamePattern(), $filename, $m);
        $this->console->section(basename($filename).' // '.Carbon::createFromTimestamp($m[1])->format('d.m.Y H:i:s e'));
        $none = $this->console->makeString('None', Console::YELLOW);
        $this->console->line('Previous file   '.(is_null($this->prevFile) ? $none : basename($this->prevFile)));
        if ($limit) $this->console->success('File: '.$counter.' of '.$limit);
    }

    protected function printMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $forHuman = humanize($memoryUsage);
        $available = ini_get('memory_limit');
        $details = preg_replace('~(\d(?=(?:\d{3})+(?!\d)))~s', "\\1'", $memoryUsage);

        $this->console->line(
            $this->console->makeString("Memory usage : ", Console::BLUE)
            .$this->console->makeString($forHuman, Console::RED)
            .$this->console->makeString(" of ".$available, Console::BLUE)
            .' ['.$details.']');
    }

    protected function filenamePattern(): string
    {
        // Файлы с именами типа ru44_1598666461.json
        // 10 цифр – метка времени
        return '~'.$this->world->sign.'_(\d{10})\.json$~';
    }
}
