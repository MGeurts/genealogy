<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    public function getTeamStatistics(User $user): Collection
    {
        return Team::query()
            ->where('user_id', $user->id)
            ->withCount(['couples', 'persons', 'users'])
            ->orderBy('name')
            ->get();
    }

    public function isUserDeletable(User $user): bool
    {
        return $this->getTeamStatistics($user)
            ->sum(fn (Team $team) => $team->users_count + $team->persons_count + $team->couples_count) === 0;
    }
}
