<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Person;
use App\Models\PersonEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonEvent>
 */
final class PersonEventFactory extends Factory
{
    protected $model = PersonEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $useDate = fake()->boolean(70); // 70% chance of having a specific date

        return [
            'person_id'   => Person::factory(),
            'type'        => fake()->randomElement(PersonEvent::EVENT_TYPES),
            'description' => fake()->boolean(60) ? fake()->sentence() : null,
            'date'        => $useDate ? fake()->dateTimeBetween('-100 years', 'now')->format('Y-m-d') : null,
            'year'        => $useDate ? null : fake()->numberBetween(1850, (int) date('Y')),
            'place'       => fake()->boolean(50) ? fake()->words(3, true) : null,
            'street'      => fake()->boolean(40) ? fake()->streetName() : null,
            'number'      => fake()->boolean(40) ? fake()->buildingNumber() : null,
            'postal_code' => fake()->boolean(40) ? fake()->postcode() : null,
            'city'        => fake()->boolean(50) ? fake()->city() : null,
            'province'    => fake()->boolean(30) ? fake()->state() : null,
            'state'       => fake()->boolean(30) ? fake()->stateAbbr() : null,
            'country'     => fake()->boolean(50) ? fake()->countryCode() : null,
            'metadata'    => fake()->boolean(20) ? ['source' => fake()->word(), 'notes' => fake()->sentence()] : null,
        ];
    }

    /**
     * Indicate that the event is a baptism.
     */
    public function baptism(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'  => PersonEvent::TYPE_BAPTISM,
            'place' => fake()->words(2, true) . ' Church',
        ]);
    }

    /**
     * Indicate that the event is a christening.
     */
    public function christening(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'  => PersonEvent::TYPE_CHRISTENING,
            'place' => fake()->words(2, true) . ' Church',
        ]);
    }

    /**
     * Indicate that the event is a burial.
     */
    public function burial(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'  => PersonEvent::TYPE_BURIAL,
            'place' => fake()->words(2, true) . ' Cemetery',
        ]);
    }

    /**
     * Indicate that the event is military service.
     */
    public function militaryService(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'        => PersonEvent::TYPE_MILITARY_SERVICE,
            'description' => 'Served in ' . fake()->word() . ' Regiment',
        ]);
    }

    /**
     * Indicate that the event is migration.
     */
    public function migration(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'        => PersonEvent::TYPE_MIGRATION,
            'description' => 'Moved from ' . fake()->city() . ' to ' . fake()->city(),
        ]);
    }

    /**
     * Indicate that the event is education.
     */
    public function education(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'  => PersonEvent::TYPE_EDUCATION,
            'place' => fake()->words(2, true) . ' University',
        ]);
    }

    /**
     * Indicate that the event is occupation.
     */
    public function occupation(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'        => PersonEvent::TYPE_OCCUPATION,
            'description' => fake()->jobTitle(),
        ]);
    }

    /**
     * Indicate that the event is residence.
     */
    public function residence(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'   => PersonEvent::TYPE_RESIDENCE,
            'street' => fake()->streetAddress(),
            'city'   => fake()->city(),
        ]);
    }

    /**
     * Indicate that the event is emigration.
     */
    public function emigration(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'        => PersonEvent::TYPE_EMIGRATION,
            'description' => 'Emigrated from ' . fake()->country(),
            'place'       => fake()->city() . ' Port',
        ]);
    }

    /**
     * Indicate that the event is immigration.
     */
    public function immigration(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'        => PersonEvent::TYPE_IMMIGRATION,
            'description' => 'Immigrated to ' . fake()->country(),
            'place'       => fake()->city() . ' Port',
        ]);
    }

    /**
     * Indicate that the event is naturalization.
     */
    public function naturalization(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'    => PersonEvent::TYPE_NATURALIZATION,
            'place'   => fake()->city() . ' Court',
            'country' => fake()->countryCode(),
        ]);
    }

    /**
     * Indicate that the event is a census.
     */
    public function census(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'        => PersonEvent::TYPE_CENSUS,
            'description' => fake()->numberBetween(1850, 2020) . ' Census',
        ]);
    }

    /**
     * Indicate that the event is a will.
     */
    public function will(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'  => PersonEvent::TYPE_WILL,
            'place' => fake()->city() . ' Probate Court',
        ]);
    }

    /**
     * Indicate that the event is probate.
     */
    public function probate(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'  => PersonEvent::TYPE_PROBATE,
            'place' => fake()->city() . ' Probate Court',
        ]);
    }

    /**
     * Indicate that the event is other.
     */
    public function other(): static
    {
        return $this->state(fn (array $attributes) => [
            'type'        => PersonEvent::TYPE_OTHER,
            'description' => fake()->sentence(),
        ]);
    }

    /**
     * Indicate that the event has a specific date.
     */
    public function withDate(?string $date = null): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $date ?? fake()->dateTimeBetween('-100 years', 'now')->format('Y-m-d'),
            'year' => null,
        ]);
    }

    /**
     * Indicate that the event has only a year (no specific date).
     */
    public function withYearOnly(?int $year = null): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => null,
            'year' => $year ?? fake()->numberBetween(1850, (int) date('Y')),
        ]);
    }

    /**
     * Indicate that the event has a full address.
     */
    public function withFullAddress(): static
    {
        return $this->state(fn (array $attributes) => [
            'street'      => fake()->streetName(),
            'number'      => fake()->buildingNumber(),
            'postal_code' => fake()->postcode(),
            'city'        => fake()->city(),
            'province'    => fake()->state(),
            'state'       => fake()->stateAbbr(),
            'country'     => fake()->countryCode(),
        ]);
    }

    /**
     * Indicate that the event has metadata.
     */
    public function withMetadata(?array $metadata = null): static
    {
        return $this->state(fn (array $attributes) => [
            'metadata' => $metadata ?? [
                'source'     => fake()->randomElement(['Church Record', 'Census', 'Family Bible', 'Newspaper']),
                'confidence' => fake()->randomElement(['high', 'medium', 'low']),
                'notes'      => fake()->sentence(),
            ],
        ]);
    }

    /**
     * Indicate that the event is in the past (historical).
     */
    public function historical(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => fake()->dateTimeBetween('-150 years', '-50 years')->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the event is recent.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
        ]);
    }
}
