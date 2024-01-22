<?php

namespace App\Http\Controllers\Jetstream;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;

class CurrentTeamController extends Controller
{
    /**
     * Update the authenticated user's current team.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $team = Jetstream::newTeamModel()->findOrFail($request->team_id);

        if (!$request->user()->switchTeam($team)) {
            abort(403);
        }

        // ----------------------------------------------------------------------------------------------------
        // always redirect to search page after switching team
        // ----------------------------------------------------------------------------------------------------
        return redirect('/search', 303);
        // ----------------------------------------------------------------------------------------------------
    }
}
