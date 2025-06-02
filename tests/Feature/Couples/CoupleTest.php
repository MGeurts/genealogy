<?php

declare(strict_types=1);

namespace Tests\Feature\Couples;

use App\Models\Couple;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoupleTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_couple_can_be_created_with_two_people()
    {
        $husband = Person::factory()->create();
        $wife    = Person::factory()->create();

        $couple = Couple::create([
            'person1_id' => $husband->id,
            'person2_id' => $wife->id,
        ]);

        $this->assertDatabaseHas('couples', [
            'id'         => $couple->id,
            'person1_id' => $husband->id,
            'person2_id' => $wife->id,
        ]);
    }

    public function test_a_couple_can_be_updated()
    {
        $husband = Person::factory()->create();
        $wife    = Person::factory()->create();

        $couple = Couple::create([
            'person1_id' => $husband->id,
            'person2_id' => $wife->id,
        ]);

        $couple->update([
            'date_start' => '2023-01-01',
            'is_married' => true,
        ]);

        $this->assertDatabaseHas('couples', [
            'date_start' => '2023-01-01',
            'is_married' => true,
        ]);
    }

    public function test_a_couple_can_be_deleted(): void
    {
        $husband = Person::factory()->create();
        $wife    = Person::factory()->create();

        $couple = Couple::create([
            'person1_id' => $husband->id,
            'person2_id' => $wife->id,
        ]);

        $couple->delete();

        $this->assertDatabaseMissing('couples', [
            'id' => $couple->id,
        ]);
    }
}
