<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Orchid\Icons\IconFinder;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @param  \Orchid\Icons\IconFinder  $iconFinder
     * @return void
     */
    public function boot(IconFinder $iconFinder)
    {
        // $iconFinder->registerIconDirectory('far', base_path('node_modules/@fortawesome/fontawesome-free/svgs/regular'));
        // $iconFinder->registerIconDirectory('fas', base_path('node_modules/@fortawesome/fontawesome-free/svgs/solid'));
        // $iconFinder->registerIconDirectory('fab', base_path('node_modules/@fortawesome/fontawesome-free/svgs/brands'));
    }
}
