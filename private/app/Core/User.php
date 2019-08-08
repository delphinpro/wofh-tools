<?php

namespace WofhTools\Core;


/**
 * Class User
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2015–2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Core
 *
 * @property int                        $id
 * @property string                     $username
 * @property string                     $email
 * @property string                     $password
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int                        $sex
 * @property int                        $status
 * @property int                        $lang
 * @property int                        $group
 * @property string                     $avatar
 * @property string                     $reset_hash
 * @property int                        $verified
 */
class User extends \Illuminate\Database\Eloquent\Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected $primaryKey = 'id';


    /**
     * @return bool
     */
    public function exists()
    {
        return (bool)static::where('email', $this->email)->first();
    }
}
