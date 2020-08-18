<?php

namespace App\Http\Controllers;

use App\Repositories\WorldRepository;
use App\World;
use Illuminate\Http\Request;

class StatController extends Controller
{
    public function index(WorldRepository $worldRepository)
    {
        $worlds = World::where('statistic', 1)->get();

        $this->state->push('worlds', $worlds);

        return view('vue');
    }
}
