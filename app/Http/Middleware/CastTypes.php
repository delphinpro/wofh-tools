<?php

namespace App\Http\Middleware;


use Illuminate\Foundation\Http\Middleware\TransformsRequest;


class CastTypes extends TransformsRequest
{
    protected function transform($key, $value)
    {
        switch (strtolower($value)) {

            case 'true':
            case 'on':
                return true;

            case'false':
            case'off':
                return false;

            default:
                return $value;
        }
    }
}
