<?php

namespace WofhTools\Models;


use Carbon\Carbon;
use WofhTools\Tools\Wofh;
use WofhTools\Helpers\Json;
use WofhTools\Helpers\Http;
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
class Worlds extends \Illuminate\Database\Eloquent\Model
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

    protected $fullTitle = null;

    protected $realLastUpdate = null;


    /**
     * @return array[Worlds]|Collection
     */
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
     * @return array[Collection]|Collection
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


    /**
     * @return array[Worlds]|Collection
     */
    public static function getWorking($id = false)
    {
        /** @var \Illuminate\Database\Query\Builder $builder */
        $builder = Worlds::where('working', 1);

        if ($id) {
            $builder->where('id', $id);
        }

        $collection = $builder->get();

        return Worlds::sort($collection, true);
    }


    /**
     * @return \Carbon\CarbonInterval|null
     */
    public function getAge()
    {
        if (!$this->started) { // нет данных о старте
            return null;
        }

        if (!$this->closed && !$this->working) { // нет данных об окончании завершенного мира
            return null;
        }

        $closed = new Carbon();
        $closed->setTimestamp(time());

        if ($this->closed && $this->closed instanceof Carbon) {
            $closed->setTimestamp($this->closed->timestamp);
        }

        $interval = $closed->diffAsCarbonInterval($this->started);

        return $interval;
    }


    public function getAgeAsString()
    {
        $ageInterval = $this->getAge();

        if (!$ageInterval) {
            return 'Неизвестно';
        }

        $totalDays = (int)$ageInterval->totalDays;

        return $totalDays.' дн.';
    }


    /**
     * @param $sign
     *
     * @return bool|Collection|\Illuminate\Database\Eloquent\Model|Worlds
     */
    public static function getBySign($sign)
    {
        $wofh = new Wofh(new Http(), new Json());
        $id = $wofh->signToId($sign);

        if ($id === false) {
            return false;
        }

        $world = Worlds::find($id);

        return $world;
    }


    public function beginUpdate()
    {
        $this->time_of_update_started = Carbon::now();
        $this->save();
    }


    public function endUpdate(Carbon $timeOfUpdatedStat = null)
    {
        if ($timeOfUpdatedStat instanceof Carbon) {
            $this->time_of_updated_stat = $timeOfUpdatedStat;
        }

        $this->time_of_update_started = null;
        $this->save();
    }


    public function toArray()
    {
        return array_merge(
            parent::toArray(),
            $this->dateFieldsAsTimestamps(),
            [
                'fmtAge' => $this->getAgeAsString(),
            ]
        );
    }


    /**
     * @return array
     */
    private function dateFieldsAsTimestamps(): array
    {
        $dates = [];
        foreach ($this->dates as $dateField) {
            $dates[$dateField] = $this->$dateField->timestamp;
        }

        return $dates;
    }
}
