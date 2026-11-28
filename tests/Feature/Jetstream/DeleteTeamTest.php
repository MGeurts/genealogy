<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Http\Livewire\DeleteTeamForm;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function (): void {
    Storage::fake('photos');
});

test('the photos disk is isolated from demo photos while testing', function (): void {
    expect(config('filesystems.disks.photos.root'))
        ->toBe(storage_path('app/testing/photos'));
});

test('teams can be deleted', function (): void {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user->ownedTeams()->save($team = Team::factory()->make([
        'personal_team' => false,
    ]));

    $team->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'test-role']
    );
    Storage::disk('photos')->put($team->id . '/demo.webp', 'demo photo');

    $component = Livewire::test(DeleteTeamForm::class, ['team' => $team->fresh()])
        ->call('deleteTeam');

    expect($team->fresh())->toBeNull();
    expect($otherUser->fresh()->teams)->toHaveCount(0);
    Storage::disk('photos')->assertMissing($team->id . '/demo.webp');
});

test('personal teams cant be deleted', function (): void {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $component = Livewire::test(DeleteTeamForm::class, ['team' => $user->currentTeam])
        ->call('deleteTeam')
        ->assertHasErrors(['team']);

    expect($user->currentTeam->fresh())->not->toBeNull();
});

test('deleting a team restores the personal team for affected members only', function (): void {
    $owner            = User::factory()->withPersonalTeam()->create();
    $team             = Team::factory()->create(['user_id' => $owner->id, 'personal_team' => false]);
    $member           = User::factory()->withPersonalTeam()->create();
    $unaffectedMember = User::factory()->withPersonalTeam()->create();
    $personalTeamId   = $member->current_team_id;
    $unaffectedTeamId = $unaffectedMember->current_team_id;

    $team->users()->attach([$member->id => ['role' => 'editor'], $unaffectedMember->id => ['role' => 'editor']]);
    $member->switchTeam($team);

    $this->actingAs($owner);
    app(App\Actions\Jetstream\DeleteTeam::class)->delete($team);

    expect($member->fresh()->current_team_id)->toBe($personalTeamId)
        ->and($unaffectedMember->fresh()->current_team_id)->toBe($unaffectedTeamId);
});

test('deleting a team without an alternative leaves its member without a team', function (): void {
    $owner  = User::factory()->withPersonalTeam()->create();
    $team   = Team::factory()->create(['user_id' => $owner->id, 'personal_team' => false]);
    $member = User::factory()->create();
    $team->users()->attach($member, ['role' => 'editor']);
    $member->switchTeam($team);

    $this->actingAs($owner);
    app(App\Actions\Jetstream\DeleteTeam::class)->delete($team);

    expect($member->fresh()->current_team_id)->toBeNull();
});
