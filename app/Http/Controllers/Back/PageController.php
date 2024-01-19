<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dependencies(Request $request)
    {
        return view('back.dependencies', []);
    }

    public function session(Request $request)
    {
        return view('back.session', []);
    }
}
