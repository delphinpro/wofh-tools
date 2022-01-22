<?php

use Illuminate\Support\Facades\DB;

function t($startTime): float
{
    return round(microtime(true) - $startTime, 3);
}

function setTablePrefix(string $prefix = ''): string
{
    $previousPrefix = DB::getTablePrefix();
    DB::setTablePrefix($prefix);
    return $previousPrefix;
}

function setStatisticTablePrefix(string $worldSign): string
{
    return setTablePrefix('z_'.$worldSign.'_');
}
