<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function team(): View
    {
        return view('back.team');
    }

    public function test(): View
    {
        return view('back.test');
    }
}
