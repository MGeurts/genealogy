<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $person_id
 * @property string $type
 * @property string|null $date
 * @property int|null $year
 * @property string|null $place
 * @property string|null $address
 * @property string|null $description
 * @property-read string $type_label
 * @property-read string|null $date_formatted
 */
final class PersonEvent extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    // Event type constants
    public const string TYPE_BAPTISM = 'baptism';

    public const string TYPE_CHRISTENING = 'christening';

    public const string TYPE_BURIAL = 'burial';

    public const string TYPE_MILITARY_SERVICE = 'military_service';

    public const string TYPE_MIGRATION = 'migration';

    public const string TYPE_EDUCATION = 'education';

    public const string TYPE_OCCUPATION = 'occupation';

    public const string TYPE_RESIDENCE = 'residence';

    public const string TYPE_EMIGRATION = 'emigration';

    public const string TYPE_IMMIGRATION = 'immigration';

    public const string TYPE_NATURALIZATION = 'naturalization';

    public const string TYPE_CENSUS = 'census';

    public const string TYPE_WILL = 'will';

    public const string TYPE_PROBATE = 'probate';

    public const string TYPE_OTHER = 'other';

    public const array EVENT_TYPES = [
        self::TYPE_BAPTISM,
        self::TYPE_CHRISTENING,
        self::TYPE_BURIAL,
        self::TYPE_MILITARY_SERVICE,
        self::TYPE_MIGRATION,
        self::TYPE_EDUCATION,
        self::TYPE_OCCUPATION,
        self::TYPE_RESIDENCE,
        self::TYPE_EMIGRATION,
        self::TYPE_IMMIGRATION,
        self::TYPE_NATURALIZATION,
        self::TYPE_CENSUS,
        self::TYPE_WILL,
        self::TYPE_PROBATE,
        self::TYPE_OTHER,
    ];

    protected $fillable = [
        'person_id',
        'type',
        'description',
        'date',
        'year',
        'place',
        'street',
        'number',
        'postal_code',
        'city',
        'province',
        'state',
        'country',
        'metadata',
    ];

    protected $appends = [
        'type_label',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Log activities
    /* -------------------------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('person_couple')
            ->setDescriptionForEvent(fn (string $eventName): string => __('personevents.event') . ' ' . __('app.event_' . $eventName))
            ->logOnly([
                'person.name',
                'type',
                'description',
                'date',
                'year',
                'place',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        // $activity->team_id = auth()->user()?->currentTeam?->id ?? null;
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function typeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => __('personevents.' . $this->type) !== 'personevents.' . $this->type
                    ? __('personevents.' . $this->type)
                    : $this->type
        );
    }

    protected function dateFormatted(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            if ($this->date) {
                return Carbon::parse($this->date)->timezone(session('timezone') ?? 'UTC')->isoFormat('LL');
            }

            return $this->year ? (string) $this->year : null;
        });
    }

    protected function eventYear(): Attribute
    {
        return Attribute::make(get: function (): ?int {
            if ($this->date) {
                return (int) Carbon::parse($this->date)->format('Y');
            }

            return $this->year;
        });
    }

    protected function address(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            $components = array_filter([
                mb_trim("{$this->street} {$this->number}"),
                mb_trim("{$this->postal_code} {$this->city}"),
                mb_trim("{$this->province} {$this->state}"),
            ]);

            return $components !== [] ? implode(', ', $components) : null;
        });
    }

    protected function casts(): array
    {
        return [
            'date'     => 'date:Y-m-d',
            'year'     => 'integer',
            'metadata' => 'array',
        ];
    }
}
