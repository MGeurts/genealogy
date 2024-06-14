<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GedcomController extends Controller
{
    public function import(): View
    {
        return view('back.gedcom.import');
    }

    public function export(): View
    {
        return view('back.gedcom.export');
    }
}
