<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PersonMetadata extends Model
{
    const METADATA_KEYS = [
        'cemetery_location_name',
        'cemetery_location_address',
        'cemetery_location_latitude',
        'cemetery_location_longitude',
    ];

    protected $fillable = [
        'person_id',
        'key',
        'value',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function key(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value ? strtolower($value) : null,
        );
    }
}
