<?php

namespace App\Http\Controllers;


use App\Repositories\WorldRepository;
use App\Services\State;
use App\Services\Wofh;
use Illuminate\Http\Request;


class IndexController extends Controller
{
    public function show(WorldRepository $worldRepository)
    {
        $worlds = $worldRepository->working();
        $this->state->push('updateWorlds', $worlds->toArray());

        return view('vue');
    }
}
