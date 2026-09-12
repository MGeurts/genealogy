<?php

declare(strict_types=1);

use App\Models\Couple;
use App\Models\Person;
use App\Models\Team;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('an editor cannot link a father, mother, or partner from another team', function (): void {
    $editor = $this->memberWithRole('editor');
    $this->actingAs($editor);

    $person      = Person::factory()->create(['sex' => 'f', 'team_id' => $editor->current_team_id]);
    $foreignTeam = Team::factory()->create();
    $father      = Person::factory()->create(['sex' => 'm', 'team_id' => $foreignTeam->id]);
    $mother      = Person::factory()->create(['sex' => 'f', 'team_id' => $foreignTeam->id]);
    $partner     = Person::factory()->create(['team_id' => $foreignTeam->id]);

    Livewire::test('people::add.father', ['person' => $person])
        ->set('form.person_id', $father->id)
        ->call('saveFather')
        ->assertNotFound();

    Livewire::test('people::add.mother', ['person' => $person])
        ->set('form.person_id', $mother->id)
        ->call('saveMother')
        ->assertNotFound();

    Livewire::test('people::add.partner', ['person' => $person])
        ->set('form.person_id', $partner->id)
        ->call('savePartner')
        ->assertNotFound();

    expect($person->fresh()->father_id)->toBeNull()
        ->and($person->fresh()->mother_id)->toBeNull();
});

test('an editor cannot assign parents from another team through the family editor', function (): void {
    $editor = $this->memberWithRole('editor');
    $this->actingAs($editor);

    $person      = Person::factory()->create(['team_id' => $editor->current_team_id]);
    $foreignTeam = Team::factory()->create();
    $father      = Person::factory()->create(['sex' => 'm', 'team_id' => $foreignTeam->id]);
    $parentOne   = Person::factory()->create(['team_id' => $foreignTeam->id]);
    $parentTwo   = Person::factory()->create(['team_id' => $foreignTeam->id]);
    $parents     = Couple::query()->create([
        'person1_id' => $parentOne->id,
        'person2_id' => $parentTwo->id,
        'team_id'    => $foreignTeam->id,
        'is_married' => false,
        'has_ended'  => false,
    ]);

    Livewire::test('people::edit.family', ['person' => $person])
        ->set('father_id', $father->id)
        ->call('saveFamily')
        ->assertNotFound();

    Livewire::test('people::edit.family', ['person' => $person])
        ->set('parents_id', $parents->id)
        ->call('saveFamily')
        ->assertNotFound();

    expect($person->fresh()->father_id)->toBeNull()
        ->and($person->fresh()->parents_id)->toBeNull();
});
