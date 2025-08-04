<?php

declare(strict_types=1);

use App\Facades\UserService;
use App\Models\Couple;
use App\Models\Person;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns team statistics', function () {
    $user  = User::factory()->create();
    $team1 = Team::factory()->create(['user_id' => $user->id]);
    Person::factory()->count(2)->for($team1)->create();
    Couple::factory()->count(3)->for($team1)->create();
    $ids = User::factory()->count(3)->create()->pluck('id');
    $team1->users()->attach($ids);

    $team2 = Team::factory()->create(['user_id' => $user->id]);
    Person::factory()->count(4)->for($team2)->create();
    Couple::factory()->count(5)->for($team2)->create();
    $ids = User::factory()->count(2)->create()->pluck('id');
    $team2->users()->attach($ids);

    // Another user's team
    Team::factory()->create();

    $teams = UserService::getTeamStatistics($user);

    expect($teams)->toHaveCount(2)
        ->and($teams->sum('couples_count'))->toBe(8)
        ->and($teams->sum('persons_count'))->toBe(6)
        ->and($teams->sum('users_count'))->toBe(5);
});

it('returns true for isUserDeletable when team stats zero', function (array $teams, bool $expected) {
    $user = User::factory()->create();
    UserService::partialMock()
        ->shouldReceive('getTeamStatistics')
        ->andReturn(collect([
            new Team()->forceFill($teams),
        ]));

    expect(UserService::isUserDeletable($user))->toBe($expected);
})->with([
    'user with teams count' => [['users_count' => 0, 'persons_count' => 0, 'couples_count' => 0], true],
    'user with empty team'  => [['users_count' => 1, 'persons_count' => 1, 'couples_count' => 1], false],
]);
