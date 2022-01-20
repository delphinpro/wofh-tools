<?php
/**
 * WofhTools
 * Helper functions
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace Helpers\Statistic;

function parseFilename(string $filename, string $pattern): array
{
    preg_match($pattern, $filename, $m);
    return [
        'name' => $filename,
        'time' => count($m) > 1 ? (int)$m[1] : 0,
    ];
}
