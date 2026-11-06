<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Userlog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the user log excludes soft deleted users', function (): void {
    $developer  = User::factory()->withPersonalTeam()->create(['is_developer' => true]);
    $activeUser = User::factory()->create([
        'firstname' => 'Active',
        'surname'   => 'User',
    ]);
    $deletedUser = User::factory()->create([
        'firstname' => 'Deleted',
        'surname'   => 'User',
    ]);

    Userlog::factory()->for($activeUser)->create(['created_at' => now()]);
    Userlog::factory()->for($deletedUser)->create(['created_at' => now()]);
    $deletedUser->delete();

    $this->actingAs($developer)
        ->get(route('developer.userlog.log'))
        ->assertOk()
        ->assertSeeText('User Active')
        ->assertDontSeeText('User Deleted');
});
