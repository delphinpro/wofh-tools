<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Repositories;


use App\Services\Wofh;
use App\World;
use Illuminate\Support\Collection;


/**
 * Class WorldRepository
 *
 * @package App\Repositories
 */
class WorldRepository implements Interfaces\WorldRepositoryInterface
{
    /** @var \App\Services\Wofh */
    protected $wofh;

    /**
     * WorldRepository constructor.
     *
     * @param \App\Services\Wofh $wofh
     */
    public function __construct(Wofh $wofh)
    {
        $this->wofh = $wofh;
    }

    public function create(array $attributes)
    {
        World::create($attributes);
    }

    /**
     * @param string[] $columns
     *
     * @return \App\World[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($columns = ['*'])
    {
        $builder = World::whereNotBetween('id', [20000, 40000]);
        $collection = $builder->get($columns);

        return $this->sort($collection);
    }

    /**
     * @param int      $id      Идентификатор мира
     * @param string[] $columns Столбики для выборки
     *
     * @return \App\World|\App\World[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function find(int $id, $columns = ['*'])
    {
        return World::findOrFail($id, $columns);
    }

    /**
     * @param int|null $id
     *
     * @return \App\World[]|\Illuminate\Support\Collection
     */
    public function working(int $id = null)
    {
        $builder = World::where('working', 1);

        if ($id) {
            $builder->where('id', $id);
        }

        $collection = $builder->get();

        return $this->sort($collection);
    }

    /**
     * @return \App\World[]|\Illuminate\Support\Collection
     */
    public function active()
    {
        $builder = World::where('working', 1)->orWhere('statistic', 1);
        $collection = $builder->get();
        return $this->sort($collection);
    }

    /**
     * @param string $sign
     *
     * @return \App\World|\App\World[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function bySign(string $sign)
    {
        $id = $this->wofh->signToId($sign);

        if ($id === false) {
            return null;
        }

        return World::find($id);
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @return \App\World[]|\Illuminate\Support\Collection
     */
    private function sort(Collection $collection)
    {
        $sorted = $collection->sort(function ($a, $b) {
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

        return $sorted->values();
    }
}
