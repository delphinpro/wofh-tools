<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Console\Statistic\Data;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\DB;

class Entry implements Arrayable
{
    protected array $data = [];

    public function __get($key)
    {
        return $this->data[$key];
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /** @noinspection PhpMissingReturnTypeInspection */
    public function mergeJsonField(string $fieldName, array $appendData)
    {
        // JSON_MERGE_PATCH(`fieldName`, JSON_OBJECT('key', 'value'), JSON_OBJECT('key', 'value') ...)
        $param1 = '`'.$fieldName.'`';
        $param2 = collect($appendData)->reduceWithKeys(function ($acc, $value, $key) {
            $key = $this->cast($key);
            $value = $this->cast($value);
            return $acc.',JSON_OBJECT('.$key.','.$value.')';
        }, '');
        $this->data[$fieldName] = DB::raw("JSON_MERGE_PATCH($param1 $param2)");
        return $this;
    }

    private function cast($val)
    {
        switch (true) {
            case is_int($val):
            case is_numeric($val):
                return (int)$val;
            case is_null($val):
                return 0;
            default:
                return DB::getPdo()->quote($val);
        }
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
