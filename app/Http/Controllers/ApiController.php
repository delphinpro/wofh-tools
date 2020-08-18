<?php

namespace App\Http\Controllers;


use App\World;


class ApiController extends Controller
{
    public function worlds()
    {
        $worlds = World::where('statistic', 1)->get();

        $this->state->push('worlds', $worlds);

        $status = true;
        $message = '';

        return response()->json([
            'status'  => $status,
            'message' => $message,
            'payload' => $this->state->toArray(),
        ]);
    }
}
