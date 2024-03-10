<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GedcomController extends Controller
{
    public function import(Request $request): View
    {
        return view('back.gedcom.import');
    }

    public function export(Request $request): View
    {
        return view('back.gedcom.export');
    }
}
