<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


/**
 * Class StatLog
 *
 * @package App
 * @property \Carbon\Carbon $created_at
 */
class StatLog extends Model
{
    const STAT_LOAD   = 1;
    const STAT_UPDATE = 2;

    const STATUS_OK  = 0;
    const STATUS_ERR = 1;
    const STATUS_WARN = 2;
    const STATUS_INFO = 3;

    public $table = 'wt_stat_log';

    protected $fillable = [
        'operation',
        'status',
        'world_id',
        'message',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
