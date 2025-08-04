<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Person;
use Illuminate\Support\Collection;

class PersonService
{
    public function upcomingBirthdayPeople(int $months): Collection
    {
        return Person::whereNotNull('dob')
            ->whereDate('dob', '>=', today())
            ->whereDate('dob', '<=', today()->addMonths($months)->endOfMonth())
            ->orderby('dob')
            ->get();
    }
}
