<?php

namespace App\Http\Controllers;


class StatController extends Controller
{
    public function index()
    {
        $this->apiGet('stat.worlds', '/world?active=true');
        return $this->view();
    }
    public function world()
    {
        $this->apiGet('stat.worlds', '/world?active=true');
        return $this->view();
    }
    public function players()
    {
        $this->apiGet('stat.worlds', '/world?active=true');
        return $this->view();
    }
    public function player($sign, $id)
    {
        $this->apiGet('stat.worlds', '/world?active=true');
        return $this->view();
    }
}
