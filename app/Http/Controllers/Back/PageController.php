<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

final class PageController extends Controller
{
    public function test(): View
    {
        return view('back.test');
    }
}
