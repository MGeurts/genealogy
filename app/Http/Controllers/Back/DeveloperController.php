<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DeveloperController extends Controller
{
    public function teams(): View
    {
        return view('back.developer.teams');
    }

    public function people(): View
    {
        return view('back.developer.people');
    }

    public function peoplelog(): View
    {
        return view('back.developer.peoplelog');
    }

    public function users(): View
    {
        return view('back.developer.users');
    }

    // --------------------------------------------------------------------------------
    public function dependencies(): View
    {
        return view('back.developer.dependencies');
    }

    public function session(): View
    {
        return view('back.developer.session');
    }
}
