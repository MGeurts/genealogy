<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'iso2',
        'iso3',
        'name',
        'name_nl',
        'isd',
        'is_eu',
    ];

    protected function casts(): array
    {
        return [
            'is_eu' => 'boolean',
        ];
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function Iso2(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value ? strtoupper($value) : null,
        );
    }

    protected function Iso3(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value ? strtoupper($value) : null,
        );
    }

    protected function Name(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value ? $value : null,
        );
    }
}
