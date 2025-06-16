<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function dashboard(): string
    {
        return view('header').view('dashboard').view('footer');
    }
}
