<?php

namespace App\Http\Controllers;


use App\Services\State;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /** @var \App\Services\State */
    protected $state;

    public function __construct(State $state)
    {
        $this->state = $state;
        if (request()->isXmlHttpRequest()) {
            sleep(3);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function view()
    {
        return view('vue');
    }

    protected function apiGet(string $compositeKey, string $endpoint)
    {
        $url = config('app.url').'/api/'.trim($endpoint, '/');
        $worlds = Http::get($url)->json();
        $this->state->push($compositeKey, $worlds);
    }
}
