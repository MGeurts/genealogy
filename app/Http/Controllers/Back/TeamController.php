<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use App\Notifications\OwnershipTransferred;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use TallStackUi\Traits\Interactions;

final class TeamController extends Controller
{
    use Interactions;

    public function team(): View
    {
        return view('back.team');
    }

    public function teamLog(): View
    {
        return view('back.teamlog');
    }

    public function peopleLog(): View
    {
        return view('back.peoplelog');
    }

    public function transferOwnership(Request $request, Team $team)
    {
        $validated = $request->validate([
            'new_owner_id' => ['required', 'exists:users,id'],
        ]);

        $currentOwner = $team->owner;
        $newOwner     = User::findOrFail($validated['new_owner_id']);

        try {
            DB::transaction(function () use ($team, $currentOwner, $newOwner): void {
                $currentOwner_as_teamUser = $team->users()->find($currentOwner->id);  // `find()` retrieves the user with membership

                if (! $currentOwner_as_teamUser) {
                    // If the current owner is not in the pivot table, attach them
                    $team->users()->attach($currentOwner->id, [
                        'role' => 'administrator',
                    ]);
                } elseif (empty($currentOwner_as_teamUser->membership->role)) {
                    // If the membership entry exists but no role is set, update it
                    $team->users()->updateExistingPivot($currentOwner->id, [
                        'role' => 'administrator',
                    ]);
                }

                // Remove the new owner's previous role
                $team->users()->detach($newOwner->id);

                // Transfer ownership to the new owner
                $team->user_id = $newOwner->id;
                $team->save();

                /* -------------------------------------------------------------------------------------------- */
                // Log activity: Transfer Team Membership
                /* -------------------------------------------------------------------------------------------- */
                defer(function () use ($team, $currentOwner, $newOwner): void {
                    activity()
                        ->useLog('user_team')
                        ->performedOn($team)
                        ->causedBy($currentOwner)
                        ->event(__('app.event_transferred'))
                        ->withProperties([
                            'email' => $newOwner->email,
                            'name'  => $newOwner->name,
                        ])
                        ->log(__('team.membership') . ' ' . __('app.event_transferred'));
                });
                /* -------------------------------------------------------------------------------------------- */

                // Notify the new owner synchronously
                $newOwner->notify(new OwnershipTransferred($team));

                $this->toast()->success(__('team.transfer'), __('team.transferred_to') . $newOwner->name . '.')->flash()->send();
            });
        } catch (Exception $e) {
            $this->toast()->error(__('team.transfer'), __('team.transfer_failed'))->flash()->send();
        }

        return back();
    }
}
