<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function dependencies(Request $request)
    {
        return view('back.developer.dependencies');
    }

    public function session(Request $request)
    {
        return view('back.developer.session');
    }

    public function test(Request $request)
    {
        return view('back.developer.test');
    }

    // --------------------------------------------------------------------------------

    public function persons(Request $request)
    {
        return view('back.developer.persons');
    }

    public function teams(Request $request)
    {
        return view('back.developer.teams');
    }

    public function users(Request $request)
    {
        return view('back.developer.users');
    }
}
