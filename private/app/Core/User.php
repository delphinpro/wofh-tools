<?php

namespace WofhTools\Core;


//use Illuminate\Database\Capsule;
use Illuminate\Database\Eloquent\Model as EloquentModel;


/**
 * Class User
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2015–2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Core
 *
 * @property $id
 * @property $username
 * @property $email
 * @property $password
 * @property $created_at
 * @property $updated_at
 * @property $sex
 * @property $status
 * @property $group
 * @property $avatar
 * @property $reset_hash
 * @property $verified
 */
class User extends EloquentModel
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
