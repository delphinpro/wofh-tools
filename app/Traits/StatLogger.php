<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Traits;


use App\StatLog;


trait StatLogger
{
    protected function log(array $log)
    {
        StatLog::create($log);
    }
}
