<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

final class Userlog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'country_name',
        'country_code',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Relationships
    /* -------------------------------------------------------------------------------------------- */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    public function date(): Attribute
    {
        return new Attribute(
            get: fn (mixed $value, array $attributes): string => Carbon::parse($attributes['created_at'])->timezone(session('timezone') ?? 'UTC')->isoFormat('dddd LL'),
        );
    }

    public function time(): Attribute
    {
        return new Attribute(
            get: fn (mixed $value, array $attributes): string => Carbon::parse($attributes['created_at'])->timezone(session('timezone') ?? 'UTC')->format('H:i:s'),
        );
    }
}
