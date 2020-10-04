<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


/**
 * @mixin IdeHelperSettings
 */
class Settings extends Model
{
    protected $primaryKey = 'name';
    public $timestamps = false;
}
