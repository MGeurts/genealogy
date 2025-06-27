<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Couple;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoupleFactory extends Factory
{
    protected $model = Couple::class;

    public function definition(): array
    {
        // Get two different people
        $person1 = Person::inRandomOrder()->first() ?? Person::factory()->create();
        do {
            $person2 = Person::inRandomOrder()->first() ?? Person::factory()->create();
        } while ($person1->id === $person2->id);

        $dateStart = $this->faker->optional()->date();
        $dateEnd   = $this->faker->optional()->dateTimeBetween($dateStart, '+20 years');

        return [
            'person1_id' => $person1->id,
            'person2_id' => $person2->id,
            'date_start' => $dateStart,
            'date_end'   => $dateEnd,
            'is_married' => $this->faker->boolean(70),
            'has_ended'  => $dateEnd !== null,
            'team_id'    => null, // or provide a Team::factory() if teams are used
        ];
    }
}
