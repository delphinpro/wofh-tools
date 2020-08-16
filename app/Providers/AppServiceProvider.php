<?php

namespace App\Providers;


use App\Services\State;
use Illuminate\Support\Facades\View;
use App\Http\Composers\StateComposer;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(State::class, function ($app) {
            return new State();
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', StateComposer::class);
    }
}
