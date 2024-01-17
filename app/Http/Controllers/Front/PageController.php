<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about(Request $request)
    {
        return view('front.about', []);
    }

    public function help(Request $request)
    {
        return view('front.help', []);
    }

    public function test(Request $request)
    {
        return view('front.test', []);
    }
}
