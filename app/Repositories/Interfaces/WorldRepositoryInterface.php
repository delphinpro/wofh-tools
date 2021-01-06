<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Repositories\Interfaces;


/**
 * Interface WorldRepositoryInterface
 *
 * @package App\Repositories\Interfaces
 */
interface WorldRepositoryInterface
{
    public function create(array $attributes);

    public function all($columns = ['*']);

    public function find(int $id, $columns = ['*']);

    public function working(int $id = null);

    /**
     * @return \App\Models\World[]|\Illuminate\Support\Collection
     */
    public function active();

    public function bySign(string $sign);
}
