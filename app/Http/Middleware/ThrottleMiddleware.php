<?php

namespace App\Http\Middleware;


use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;


class ThrottleMiddleware extends ThrottleRequests
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return string|void
     */
    protected function resolveRequestSignature($request)
    {
        if ($request->ip() === '127.0.0.1') {
            return sha1(Str::random(64));
        }

        return parent::resolveRequestSignature($request);
    }
}
