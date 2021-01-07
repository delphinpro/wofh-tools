<?php
/**
 * WofhTools
 *
 * @author      delphinpro <delphinpro@yandex.ru>
 * @copyright   copyright © 2020 delphinpro
 * @license     licensed under the MIT license
 */

namespace App\Providers;


use App\Repositories\Interfaces\WorldRepositoryInterface;
use App\Repositories\WorldRepository;
use Illuminate\Support\ServiceProvider;


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
