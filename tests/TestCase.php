<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Create a user attached to a (non-owned) team with the given role.
     *
     * Team owners are granted every permission automatically by Jetstream's
     * hasTeamPermission(), so authorization tests must use a non-owner member
     * with a specific role instead of the team owner.
     *
     * Used by the Livewire component permission-guard tests
     * (tests/Feature/Livewire/People/*ComponentTest.php).
     */
    protected function memberWithRole(string $role): User
    {
        $owner = User::factory()->withPersonalTeam()->create();
        $team  = $owner->currentTeam;

        $member = User::factory()->create();
        $team->users()->attach($member->id, ['role' => $role]);
        $member->forceFill(['current_team_id' => $team->id])->save();

        return $member;
    }
}
