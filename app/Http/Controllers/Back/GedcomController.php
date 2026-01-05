<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

final class GedcomController extends Controller
{
    public function importteam(): View
    {
        $user = auth()->user();

        abort_unless($user && $user->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.gedcom.importteam');
    }

    public function exportteam(): View
    {
        return view('back.gedcom.exportteam');
    }
}
