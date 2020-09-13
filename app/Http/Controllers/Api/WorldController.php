<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\WorldRepositoryInterface;
use Illuminate\Http\Request;


class WorldController extends Controller
{
    public function index(Request $request, WorldRepositoryInterface $worldRepository)
    {
        $filter = $request->get('active');

        if ($filter === true) {
            return $worldRepository->active();
        }

        return $worldRepository->all();
    }
}
