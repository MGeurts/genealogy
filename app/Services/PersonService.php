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
            ->whereRaw('CASE WHEN MONTH(NOW()) +' . $months . " > 12 THEN date_format(dob, '%m-%d') >= date_format(NOW(), '%m-%d') OR date_format(dob, '%m-%d') <= date_format(NOW() + INTERVAL " . $months . " MONTH, '%m-%d') ELSE date_format(dob, '%m-%d') >= date_format(NOW(), '%m-%d') AND date_format(dob, '%m-%d') <= date_format(NOW() + INTERVAL " . $months . " MONTH, '%m-%d') END")
            ->orderByRaw("(case when date_format(dob, '%m-%d') >= date_format(now(), '%m-%d') then 0 else 1 end), date_format(dob, '%m-%d')")
            ->get();
    }
}
