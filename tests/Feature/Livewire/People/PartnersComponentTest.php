<?php

declare(strict_types=1);

use App\Models\Couple;
use App\Models\Person;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

// memberWithRole() is a helper method on Tests\TestCase (tests/TestCase.php)

// ---------------------------------------------------------------------------
// Guard: partners::confirm() / partners::delete() require couple:delete
// ---------------------------------------------------------------------------

test('deleting a couple is forbidden without the couple:delete permission', function (): void {
    // 'editor' role has couple:update but NOT couple:delete (see JetstreamServiceProvider)
    $editor = $this->memberWithRole('editor');
    $this->actingAs($editor);

    $person  = Person::factory()->create(['team_id' => $editor->current_team_id]);
    $partner = Person::factory()->create(['team_id' => $editor->current_team_id]);

    $couple = Couple::factory()->create([
        'person1_id' => $person->id,
        'person2_id' => $partner->id,
        'team_id'    => $editor->current_team_id,
    ]);

    // Each call uses a fresh component instance: a forbidden response does not
    // return a valid updated snapshot to chain a second call from.
    Livewire::test('people::partners', ['person' => $person])
        ->call('confirm', $couple->id, $partner->name)
        ->assertForbidden();

    Livewire::test('people::partners', ['person' => $person])
        ->call('delete', ['id' => $couple->id, 'name' => $partner->name])
        ->assertForbidden();

    // Nothing should have changed
    $this->assertDatabaseHas('couples', ['id' => $couple->id]);
});

test('a user with the couple:delete permission can delete a couple', function (): void {
    // 'manager' role includes couple:delete (see JetstreamServiceProvider)
    $manager = $this->memberWithRole('manager');
    $this->actingAs($manager);

    $person  = Person::factory()->create(['team_id' => $manager->current_team_id]);
    $partner = Person::factory()->create(['team_id' => $manager->current_team_id]);

    $couple = Couple::factory()->create([
        'person1_id' => $person->id,
        'person2_id' => $partner->id,
        'team_id'    => $manager->current_team_id,
    ]);

    Livewire::test('people::partners', ['person' => $person])
        ->call('delete', ['id' => $couple->id, 'name' => $partner->name])
        ->assertOk()
        ->assertDispatched('couple_deleted');

    $this->assertModelMissing($couple);
});
