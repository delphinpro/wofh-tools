<?php

namespace App\Http\Controllers;


class IndexController extends Controller
{
    public function show()
    {
        $this->apiGet('stat.worlds', '/world?active=true');
        return $this->view();
    }
}
