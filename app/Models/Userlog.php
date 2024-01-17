<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Userlog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'country_name',
        'country_code',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function date(): Attribute
    {
        return new Attribute(
            get: fn (mixed $value, array $attributes) => Carbon::parse($attributes['created_at'])->isoFormat('dddd LL'),
        );
    }

    protected function time(): Attribute
    {
        return new Attribute(
            get: fn (mixed $value, array $attributes) => Carbon::parse($attributes['created_at'])->format('H:i:s'),
        );
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relationships
    /* -------------------------------------------------------------------------------------------- */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
