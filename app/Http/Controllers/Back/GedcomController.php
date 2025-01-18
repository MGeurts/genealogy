<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class GedcomController extends Controller
{
    public function import(): View
    {
        abort_unless(auth()->user()->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.gedcom.importteam');
    }

    public function export(): View
    {
        return view('back.gedcom.exportteam');
    }
}
