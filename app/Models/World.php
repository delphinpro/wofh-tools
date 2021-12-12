<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2016-2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Models;


use App\Traits\ModelAttributesToCamelCaseArray;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;


/**
 * Class World
 *
 * @package App
 * @mixin IdeHelperWorld
 */
class World extends Model
{
    use ModelAttributesToCamelCaseArray;
    use AsSource;


    public $timestamps = false;

    public $table = 'wt_worlds';

    protected $casts = [
        'can_reg'   => 'boolean',
        'working'   => 'boolean',
        'statistic' => 'boolean',
        'hidden'    => 'boolean',
    ];

    protected $dates = [
        'started_at',
        'closed_at',
        'stat_loaded_at',
        'stat_updated_at',
        'const_updated_at',
        'update_started_at',
    ];

    public function beginUpdate()
    {
        $this->update_started_at = Carbon::now();
        $this->save();
    }

    public function endUpdate(Carbon $statUpdatedAt = null)
    {
        if ($statUpdatedAt instanceof Carbon) {
            $this->stat_updated_at = $statUpdatedAt;
        }

        $this->update_started_at = null;
        $this->save();
    }

    // public function toArray()
    // {
    //     $attributes = $this->attributesToCamelCaseArray();
    //     unset($attributes['desc']);
    //     return array_merge(
    //         $attributes,
    //         [
    //             'uSign'             => ucfirst($this->sign),
    //             'age'               => $this->getAgeAsNumber(),
    //             'localAge'          => $this->getAgeAsString(),
    //             'serverCountryFlag' => $this->getServerFlag(),
    //         ]
    //     );
    // }

    public function getServerFlag()
    {
        return preg_match('/ru/i', $this->sign) ? 'flag-ru' : 'flag-uk';
    }

    /**
     * @return \Carbon\CarbonInterval|null
     */
    public function getAge()
    {
        if (!$this->started_at) { // нет данных о старте
            return null;
        }

        if (!$this->closed_at && !$this->working) { // нет данных об окончании завершенного мира
            return null;
        }

        $closed = new Carbon();
        $closed->setTimestamp(time());

        if ($this->closed_at && $this->closed_at instanceof Carbon) {
            $closed->setTimestamp($this->closed_at->timestamp);
        }

        return $closed->diffAsCarbonInterval($this->started_at);
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

    public function getAgeAsNumber()
    {
        $ageInterval = $this->getAge();

        if (!$ageInterval) {
            return 0;
        }

        return (int)$ageInterval->totalDays;
    }
}
