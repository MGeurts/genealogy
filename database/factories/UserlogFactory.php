<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Userlog>
 */
final class UserlogFactory extends Factory
{
    /**
     * Cached pool of valid datetimes (strings) excluding DST days.
     *
     * @var string[]|null
     */
    private static ?array $validDateTimes = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Pick a random valid day/time for created_at
        $createdAt = $this->randomValidDateTime('-2 year', '-1 day');

        // Ensure updated_at >= created_at
        $updatedAt = $this->randomValidDateTimeAfter($createdAt);

        return [
            'user_id'      => User::factory(),
            'country_name' => $this->faker->country(),
            'country_code' => $this->faker->countryCode(),
            'created_at'   => $createdAt,
            'updated_at'   => $updatedAt,
        ];
    }

    /**
     * Pick a random datetime from the precomputed valid pool.
     */
    private function randomValidDateTime(string $start, string $end): DateTimeImmutable
    {
        $appTimezone = config('app.timezone', 'UTC');
        $timezone    = new DateTimeZone($appTimezone);

        if (self::$validDateTimes === null) {
            self::$validDateTimes = $this->buildValidDateTimePool($timezone, $start, $end);
        }

        $randomDate = self::$validDateTimes[array_rand(self::$validDateTimes)];

        return new DateTimeImmutable($randomDate, $timezone);
    }

    /**
     * Pick a random datetime that is >= the given datetime.
     */
    private function randomValidDateTimeAfter(DateTimeImmutable $after): DateTimeImmutable
    {
        $timezone = $after->getTimezone();

        $filtered = array_filter(
            self::$validDateTimes,
            fn ($d) => new DateTimeImmutable($d, $timezone) >= $after
        );

        // Fallback if somehow no date is after $after (should not happen)
        $randomDate = $filtered ? $filtered[array_rand($filtered)] : $after->format('Y-m-d H:i:s');

        return new DateTimeImmutable($randomDate, $timezone);
    }

    /**
     * Build an array of datetimes (Y-m-d H:i:s) excluding DST transition days.
     *
     * @return string[]
     */
    private function buildValidDateTimePool(DateTimeZone $timezone, string $start, string $end): array
    {
        $startDate = new DateTimeImmutable($start, $timezone);
        $endDate   = new DateTimeImmutable($end, $timezone);

        // Get DST transition days
        $transitions = $timezone->getTransitions(
            $startDate->getTimestamp(),
            $endDate->getTimestamp()
        );

        $excludedDays = [];
        foreach ($transitions as $t) {
            $excludedDays[] = (new DateTimeImmutable('@' . $t['ts']))
                ->setTimezone($timezone)
                ->format('Y-m-d');
        }
        $excludedDays = array_unique($excludedDays);

        $pool   = [];
        $period = new DatePeriod(
            $startDate,
            new DateInterval('P1D'),
            $endDate->modify('+1 day')
        );

        foreach ($period as $day) {
            $dayStr = $day->format('Y-m-d');
            if (! in_array($dayStr, $excludedDays, true)) {
                // Random time for the day
                $hour   = random_int(0, 23);
                $minute = random_int(0, 59);
                $second = random_int(0, 59);

                $pool[] = sprintf(
                    '%s %02d:%02d:%02d',
                    $dayStr,
                    $hour,
                    $minute,
                    $second
                );
            }
        }

        return $pool;
    }
}
