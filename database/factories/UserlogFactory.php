<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Userlog>
 */
class UserlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'country_name' => $this->faker->country(),
            'country_code' => $this->faker->countryCode(),
            'created_at'   => $this->faker->dateTimeBetween('-2 year', '-1 day'),
        ];
    }
}
