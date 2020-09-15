<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\StatCommon;


class StatWorldController extends Controller
{

    public function index(string $sign, StatCommon $statCommon)
    {
        try {
            $statCommon->setTable('z_'.$sign.'_common');
            return $statCommon->orderByDesc('state_at')->get();
        } catch (\Exception $e) {
            return abort(404, 'World '.$sign.' not found');
        }
    }

    public function last(string $sign, StatCommon $statCommon)
    {
        try {
            $statCommon->setTable('z_'.$sign.'_common');
            $lastTime = $statCommon->max('state_at');
            return $statCommon->where('state_at', $lastTime)->firstOrFail();
        } catch (\Exception $e) {
            return abort(404, 'World '.$sign.' not found');
        }
    }
}
