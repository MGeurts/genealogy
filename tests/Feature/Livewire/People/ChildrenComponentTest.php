<?php

declare(strict_types=1);

use App\Models\Person;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

// memberWithRole() is a helper method on Tests\TestCase (tests/TestCase.php)

// ---------------------------------------------------------------------------
// Guard: children::confirm() / children::disconnect() require person:update
// ---------------------------------------------------------------------------

test('disconnecting a child is forbidden without the person:update permission', function (): void {
    // 'member' role only has person:read (see JetstreamServiceProvider)
    $member = $this->memberWithRole('member');
    $this->actingAs($member);

    $parent = Person::factory()->create(['sex' => 'f', 'team_id' => $member->current_team_id]);
    $child  = Person::factory()->create(['mother_id' => $parent->id, 'team_id' => $member->current_team_id]);

    // Each call uses a fresh component instance: a forbidden response does not
    // return a valid updated snapshot to chain a second call from.
    Livewire::test('people::children', ['person' => $parent])
        ->call('confirm', $child->id)
        ->assertForbidden();

    Livewire::test('people::children', ['person' => $parent])
        ->call('disconnect', $child->id)
        ->assertForbidden();

    // Nothing should have changed
    expect($child->fresh()->mother_id)->toBe($parent->id);
});

test('a user with the person:update permission can disconnect a child', function (): void {
    // 'editor' role includes person:update (see JetstreamServiceProvider)
    $editor = $this->memberWithRole('editor');
    $this->actingAs($editor);

    $parent = Person::factory()->create(['sex' => 'f', 'team_id' => $editor->current_team_id]);
    $child  = Person::factory()->create(['mother_id' => $parent->id, 'team_id' => $editor->current_team_id]);

    Livewire::test('people::children', ['person' => $parent])
        ->call('disconnect', $child->id)
        ->assertOk()
        ->assertDispatched('person_disconnected_as_child');

    expect($child->fresh()->mother_id)->toBeNull();
});
