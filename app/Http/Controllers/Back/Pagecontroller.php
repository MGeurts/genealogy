<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function teams()
    {
        return view('back.teams');
    }

    public function test()
    {
        return view('back.test');
    }
}
