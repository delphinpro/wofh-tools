<?php

namespace WofhTools\Helpers;


/**
 * Class Password
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 * @package     WofhTools\Helpers
 */
class Password
{
    public static function hash($password, $algorithm, array $options = [])
    {
        return password_hash($password, $algorithm, $options);
    }


    public static function getInfo($hash)
    {
        return password_get_info($hash);
    }


    public static function heedRehash($hash, $algorithm, array $options = [])
    {
        return password_needs_rehash($hash, $algorithm, $options);
    }


    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

