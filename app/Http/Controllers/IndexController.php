<?php

namespace App\Http\Controllers;


use App\Repositories\WorldRepository;


class IndexController extends Controller
{
    public function show(WorldRepository $worldRepository)
    {
        $worlds = $worldRepository->active();
        $this->state->push('updateWorlds', $worlds->toArray());

        return $this->view();
    }
}
