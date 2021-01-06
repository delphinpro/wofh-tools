<?php

namespace App\Models;


use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\HasPermissions;
use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;


/**
 * @mixin IdeHelperUser
 */
class User extends Administrator implements \Illuminate\Contracts\Auth\Authenticatable
{
    use Notifiable;
    use Authenticatable;
    use HasPermissions;
    use DefaultDatetimeFormat;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
