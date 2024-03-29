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
use App\Console\Statistic\Storage\Storage;
use App\Console\Statistic\EventProcessor\EventProcessor;
use App\Models\World;
use Carbon\Carbon;
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

    protected bool $zip;

    /** @var \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter */
    protected $fs;

    protected World $world;

    protected ?string $prevFile;

    protected ?Collection $files;

    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->zip = false;
        $this->fs = Storage::disk(config('app.stat_disk'));
    }

    /**
     * @throws \Exception|\Throwable
     */
    public function updateWorld(World $world, int $limit = 0)
    {
        $this->prevFile = null;
        $this->files = null;
        $this->world = $world;

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
            $this->printHeaderFile($filename, $limit, $counter);
            $this->processFile($filename);
            $this->prevFile = $filename;

            if ($limit && $counter >= $limit) {
                $this->console->line();
                $this->console->line('Limited count files: '.$limit.'. STOP.', [Console::BG_CYAN, Console::WHITE]);
                break;
            }
        }

        $this->printMemoryUsage();
    }

    protected function printHeaderFile(string $filename, $limit, $counter)
    {
        preg_match($this->filenamePattern(), $filename, $m);
        $this->console->section(basename($filename).' // '.Carbon::createFromTimestamp($m[1])->format('d.m.Y H:i:s e'));
        $none = $this->console->makeString('None', Console::YELLOW);
        $this->console->line('Previous file   '.(is_null($this->prevFile) ? $none : basename($this->prevFile)));
        if ($limit) $this->console->success('File: '.$counter.' of '.$limit);
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

    /** @throws \Throwable */
    protected function processFile(string $filename)
    {
        DB::transaction(function () use ($filename) {

            $this->world->beginUpdate();

            /** @var \App\Console\Statistic\Storage\Storage $data */
            $data = resolve(Storage::class);
            $data->setWorld($this->world);
            $data->loadFromFile($filename);
            $data->parse();

            /** @var \App\Console\Statistic\Storage\Storage $dataPrevious */
            $dataPrevious = resolve(Storage::class);
            $dataPrevious->setWorld($this->world);
            $dataPrevious->loadFromFile($this->prevFile);

            if ($dataPrevious->hasData()) {
                $dataPrevious->parse();
            } else {
                $this->console->warn('No previous data');
            }
            // $this->dump($data, $dataPrevious, $this->world->id);

            /** @var \App\Console\Statistic\EventProcessor\EventProcessor $events */
            $events = resolve(EventProcessor::class);
            $events->setData($data, $dataPrevious);
            $events->checkEvents();

            $data->save($events);

            $this->world->endUpdate($data->getTime());

        });
    }

    /** @throws \Exception */
    protected function scanFiles(string $dataPath)
    {
        $pattern = $this->filenamePattern();
        $files = collect($this->fs->files($dataPath))
            ->map(fn ($filename) => parseFilename($filename, $pattern))
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

    protected function filenamePattern(): string
    {
        // Файлы с именами типа ru44_1598666461.json
        // 10 цифр – метка времени
        return '~'.$this->world->sign.'_(\d{10})\.json$~';
    }
}
