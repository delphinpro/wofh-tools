<?php

use App\Models\World;
use Illuminate\Support\Facades\DB;

function t($startTime): float
{
    return round(microtime(true) - $startTime, 3);
}

function withWorldPrefix(Closure $closure, ?World $world = null)
{
    $prefix = DB::getTablePrefix();
    DB::setTablePrefix($world ? 'z_'.$world->sign.'_' : '');
    $closure();
    DB::setTablePrefix($prefix);
}
