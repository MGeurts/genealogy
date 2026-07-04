<?php

declare(strict_types=1);

use App\Models\Person;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

// memberWithRole() is a helper method on Tests\TestCase (tests/TestCase.php)

// ---------------------------------------------------------------------------
// Guard: profile::confirm() / profile::delete() require person:delete
// ---------------------------------------------------------------------------

test('deleting a person is forbidden without the person:delete permission', function (): void {
    // 'editor' role has person:update but NOT person:delete (see JetstreamServiceProvider)
    $editor = $this->memberWithRole('editor');
    $this->actingAs($editor);

    // No children/couples, so isDeletable() alone would allow the delete
    $person = Person::factory()->create(['team_id' => $editor->current_team_id]);

    // Each call uses a fresh component instance: a forbidden response does not
    // return a valid updated snapshot to chain a second call from.
    Livewire::test('people::profile', ['person' => $person])
        ->call('confirm')
        ->assertForbidden();

    Livewire::test('people::profile', ['person' => $person])
        ->call('delete')
        ->assertForbidden();

    // Nothing should have changed
    $this->assertDatabaseHas('people', ['id' => $person->id, 'deleted_at' => null]);
});

test('a user with the person:delete permission can delete a deletable person', function (): void {
    // 'manager' role includes person:delete (see JetstreamServiceProvider)
    $manager = $this->memberWithRole('manager');
    $this->actingAs($manager);

    $person = Person::factory()->create(['team_id' => $manager->current_team_id]);

    Livewire::test('people::profile', ['person' => $person])
        ->call('delete')
        ->assertRedirect('/search');

    $this->assertSoftDeleted($person);
});
