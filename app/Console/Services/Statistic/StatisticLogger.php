<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Services\Statistic;

use App\Console\Services\Console;
use App\Models\StatLog;

/**
 * Class StatisticLogger
 *
 * @package App\Services
 */
class StatisticLogger
{
    protected Console $console;

    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    public function log(array $log)
    {
        $savedPrefix = setTablePrefix();
        StatLog::create([
            'operation' => $log['operation'] ?: 0,
            'status'    => $log['status'] ?: StatLog::STATUS_OK,
            'world_id'  => $log['world_id'] ?: null,
            'message'   => $log['message'] ?: '',
        ]);
        setTablePrefix($savedPrefix);
    }

    public function ok(int $operation, string $message, $worldId = null)
    {
        $message = $this->truncateMessage($message);
        $this->console->line(''.$message);
        $this->log([
            'operation' => $operation,
            'status'    => StatLog::STATUS_OK,
            'world_id'  => $worldId,
            'message'   => $message,
        ]);
    }

    public function error(int $operation, string $message, $worldId = null)
    {
        $message = $this->truncateMessage($message);
        $this->console->error('[ERR] '.$message);
        $this->log([
            'operation' => $operation,
            'status'    => StatLog::STATUS_ERR,
            'world_id'  => $worldId,
            'message'   => $message,
        ]);
    }

    public function info(int $operation, string $message, $worldId = null)
    {
        $message = $this->truncateMessage($message);
        $this->console->info('[INFO] '.$message);
        $this->log([
            'operation' => $operation,
            'status'    => StatLog::STATUS_INFO,
            'world_id'  => $worldId,
            'message'   => $message,
        ]);
    }

    public function warn(int $operation, string $message, $worldId = null)
    {
        $message = $this->truncateMessage($message);
        $this->console->warn('[WARN] '.$message);
        $this->log([
            'operation' => $operation,
            'status'    => StatLog::STATUS_WARN,
            'world_id'  => $worldId,
            'message'   => $message,
        ]);
    }

    private function truncateMessage($message)
    {
        $length = 500;
        if (mb_strlen($message) > $length) $message = mb_substr($message, 0, $length);
        return $message;
    }
}
