<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Providers;


use App\Repositories\WorldRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\WorldRepositoryInterface;


/**
 * Class RepositoryServiceProvider
 *
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(WorldRepositoryInterface::class, WorldRepository::class);
    }
}
