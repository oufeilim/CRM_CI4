<?php

namespace App\Controllers;

use App\Models\Company_model;
use App\Models\User_model;
use App\Models\Company_user_model;
use Exception;

class Home extends BaseController
{
    private $data = [];

    public function dashboard()
    {
        return view('header').view('dashboard').view('footer');
    }
}
