<?php
/**
 * WofhTools
 * Helper functions
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2022 delphinpro
 * @license     licensed under the MIT license
 */

namespace Helpers\Wofh;

function wonderId(?int $wonder): int
{
    return $wonder % 1000;
}

function wonderLevel(?int $wonder): int
{
    return (int)floor($wonder / 1000);
}
