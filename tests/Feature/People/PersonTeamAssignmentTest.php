<?php

declare(strict_types=1);

namespace Tests\Feature\People;

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PersonTeamAssignmentTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_person_assigned_to_the_users_current_team(): void
    {
        $user = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user);

        $person = Person::factory()
            ->withUser($user)
            ->create();

        $this->assertDatabaseHas('people', [
            'id'      => $person->id,
            'team_id' => $user->currentTeam->id,
        ]);
    }
}
