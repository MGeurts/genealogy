<?php

declare(strict_types=1);

namespace Tests\Feature\People;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeopleTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_person_can_be_created(): void
    {
        $person = Person::factory()->create();

        $this->assertDatabaseHas('people', [
            'id' => $person->id,
        ]);
    }

    public function test_a_person_can_be_updated(): void
    {
        $person = Person::factory()->create();

        $person->update([
            'firstname' => 'Updated',
        ]);

        $this->assertDatabaseHas('people', [
            'id'        => $person->id,
            'firstname' => 'Updated',
        ]);
    }

    public function test_a_person_can_be_soft_deleted(): void
    {
        $person = Person::factory()->create();

        $person->delete();

        $this->assertSoftDeleted($person);
    }

    public function test_a_person_can_be_hard_deleted(): void
    {
        $person = Person::factory()->create();

        $person->forceDelete();

        $this->assertDatabaseMissing('people', [
            'id' => $person->id,
        ]);
    }
}
