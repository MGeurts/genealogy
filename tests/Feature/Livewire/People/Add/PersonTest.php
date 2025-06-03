<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\People\Add;

use App\Livewire\People\Add\Person as AddPerson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_person(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        Livewire::actingAs($user)
            ->test(AddPerson::class)
            ->set('form.firstname', 'John')
            ->set('form.surname', 'Doe')
            ->set('form.sex', 'm')
            ->set('form.dob', '1980-01-01')
            ->call('savePerson')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('people', [
            'firstname' => 'John',
            'surname'   => 'Doe',
            'sex'       => 'm',
            'dob'       => '1980-01-01',
            'team_id'   => $user->currentTeam->id,
        ]);
    }

    public function test_validation_errors_when_required_fields_are_missing(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user);

        Livewire::actingAs($user)
            ->test(AddPerson::class)
            ->set('form.surname', '')
            ->set('form.sex', '')
            ->call('savePerson')
            ->assertHasErrors(['form.surname' => 'required', 'form.sex' => 'required']);
    }
}
