<?php

namespace WofhTools\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


/**
 * Class Worlds
 *
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   Copyright © 2016-2019 delphinpro
 * @license     Licensed under the MIT license
 * @package     WofhTools\Models
 *
 * @property int    $id
 * @property string $title
 * @property string $title_alt
 * @property string $sign
 * @property int    $can_reg
 * @property int    $working
 * @property int    $statistic
 * @property int    $hidden
 * @property Carbon $started
 * @property Carbon $closed
 * @property Carbon $time_of_loaded_stat
 * @property Carbon $time_of_updated_stat
 * @property Carbon $time_of_updated_const
 * @property Carbon $time_of_update_started
 * @property string $desc
 * @property string $metainfo
 * @property string $version
 */
class Worlds extends Model
{
    public $timestamps = false;

    protected $table = 'wt_worlds';

    protected $casts = [
        'can_reg'   => 'boolean',
        'working'   => 'boolean',
        'statistic' => 'boolean',
        'hidden'    => 'boolean',
    ];

    protected $dates = [
        'started',
        'closed',
        'time_of_loaded_stat',
        'time_of_updated_stat',
        'time_of_updated_const',
        'time_of_update_started',
    ];


    public static function getAll()
    {
        /** @var \Illuminate\Database\Query\Builder $builder */
        $builder = Worlds::whereNotBetween('id', [20000, 40000]);

        $collection = $builder->get();

        return Worlds::sort($collection, true);
    }

    /**
     * @param Collection $collection
     * @param bool       $asArray
     *
     * @return array|Collection
     */
    private static function sort(Collection $collection, bool $asArray = false)
    {
        $collection = $collection->sort(function ($a, $b) {
            $countryId1 = floor($a['id'] / 10000);
            $countryId2 = floor($b['id'] / 10000);

            if ($countryId1 == $countryId2) {
                if ($a['id'] == $b['id']) {
                    return 0;
                }

                return $a['id'] < $b['id'] ? 1 : -1;
            }

            return $countryId1 > $countryId2 ? 1 : -1;
        });

        if ($asArray) {
            $arr = [];
            foreach ($collection as $item) {
                $arr[] = $item;
            }

            return $arr;
        }

        return $collection;
    }
}