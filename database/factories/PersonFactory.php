<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Person>
 */
class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'firstname'   => $this->faker->firstName,
            'surname'     => $this->faker->lastName,
            'birthname'   => $this->faker->lastName,
            'nickname'    => $this->faker->userName,
            'sex'         => $this->faker->randomElement(['m', 'f']),
            'gender_id'   => null,
            'father_id'   => null,
            'mother_id'   => null,
            'parents_id'  => null,
            'dob'         => $this->faker->optional()->date(),
            'yob'         => $this->faker->optional()->year(),
            'pob'         => $this->faker->optional()->city(),
            'dod'         => $this->faker->optional()->date(),
            'yod'         => $this->faker->optional()->year(),
            'pod'         => $this->faker->optional()->city(),
            'summary'     => $this->faker->optional()->paragraph(),
            'street'      => $this->faker->optional()->streetName(),
            'number'      => $this->faker->optional()->buildingNumber(),
            'postal_code' => $this->faker->optional()->postcode(),
            'city'        => $this->faker->optional()->city(),
            'province'    => $this->faker->optional()->state(),
            'state'       => $this->faker->optional()->state(),
            'country'     => $this->faker->optional()->countryCode(),
            'phone'       => $this->faker->optional()->phoneNumber(),
            'photo'       => null,
            'team_id'     => null,
        ];
    }

    public function withUser(User $user): static
    {
        return $this->state(fn (array $attributes): array => [
            'team_id' => $user->currentTeam?->id,
        ]);
    }
}
