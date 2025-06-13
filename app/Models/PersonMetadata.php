<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

final class PersonMetadata extends Model
{
    use LogsActivity;

    public const array METADATA_KEYS = [
        'cemetery_location_name',
        'cemetery_location_address',
        'cemetery_location_latitude',
        'cemetery_location_longitude',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person.name',
        'key',
        'value',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Log activities
    /* -------------------------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('person_couple')
            ->setDescriptionForEvent(fn (string $eventName): string => __('person.person_metadata') . ' ' . __('app.event_' . $eventName))
            ->logOnly([
                'person.name',
                'key',
                'value',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->team_id = auth()->user()?->currentTeam?->id ?? null;
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* returns PERSON (1 Person) */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    public function key(): Attribute
    {
        return new Attribute(
            set: fn ($value) => $value ? mb_strtolower((string) $value) : null,
        );
    }
}
