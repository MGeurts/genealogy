<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PersonService
{
    public function upcomingBirthdayPeople(int $months): Collection
    {
        $t = today();

        return Person::whereNotNull('dob')
            ->where(fn (Builder $q) => $q
                ->whereMonth('dob', '=', $t->month)
                ->whereDay('dob', '>=', $t->day)
            )
            ->orWhere(fn (Builder $q) => $q
                ->whereMonth('dob', '>', $t->month)
                ->whereMonth('dob', '<=', $t->addMonths($months)->month)
            )
            ->orderby('dob')
            ->get();
    }
}
