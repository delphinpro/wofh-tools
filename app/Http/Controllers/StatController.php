<?php

namespace App\Http\Controllers;


use App\Repositories\WorldRepository;


class StatController extends Controller
{
    public function index(WorldRepository $worldRepository)
    {
        $worlds = $worldRepository->active();
        $this->state->push('updateWorlds', $worlds->toArray());

        return $this->view();
    }
}
