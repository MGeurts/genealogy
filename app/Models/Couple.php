<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $person1_id
 * @property int $person2_id
 * @property string|null $date_start
 * @property string|null $date_end
 * @property bool $is_married
 * @property bool $has_ended
 * @property-read string|null $name
 * @property-read string|null $date_start_formatted
 * @property-read Person $person1
 * @property-read Person $person2
 */
final class Couple extends Model
{
    /** @use HasFactory<\Database\Factories\PersonFactory> */
    use HasFactory;

    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'person1_id',
        'person2_id',
        'date_start',
        'date_end',
        'is_married',
        'has_ended',
        'team_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'name',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Log activities
    /* -------------------------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('person_couple')
            ->setDescriptionForEvent(fn (string $eventName): string => __('couple.couple') . ' ' . __('app.event_' . $eventName))
            ->logOnly([
                'person1.name',
                'person2.name',
                'date_start',
                'date_end',
                'is_married',
                'has_ended',
                'team.name',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->team_id = auth()->user()?->currentTeam->id ?? null;
    }

    /* -------------------------------------------------------------------------------------------- */
    // Local Scopes
    /* -------------------------------------------------------------------------------------------- */
    /**
     * @param  Builder<self>  $query
     */
    #[Scope]
    public function scopeYoungerThan(Builder $query, ?string $year = null): void
    {
        if ($year) {
            $query->where(function ($q) use ($year): void {
                $q->whereNull('date_start')
                    ->orWhereYear('date_start', '>=', $year);
            });
        }
    }

    /**
     * @param  Builder<self>  $query
     */
    #[Scope]
    public function scopeOlderThan(Builder $query, ?string $year = null): void
    {
        if ($year) {
            $query->where(function ($q) use ($year): void {
                $q->whereNull('date_start')
                    ->orWhereYear('date_start', '<=', $year);
            });
        }
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* returns PARTNER 1 (1 Person) based on person1_id in Couple model */
    /** @return BelongsTo<Person, covariant self> */
    public function person1(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person1_id');
    }

    /* returns PARTNER 2 (1 Person) based on person2_id in Couple model */
    /** @return BelongsTo<Person, covariant self> */
    public function person2(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person2_id');
    }

    /* returns ALL CHILDREN (n Person) based on parents_id in Person model */
    /** @return HasMany<Person, covariant self> */
    public function children(): HasMany
    {
        return $this->hasMany(Person::class, 'parents_id');
    }

    /* returns TEAM (1 Team) based on team_id in Team model */
    /** @return BelongsTo<Team, covariant self> */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /* -------------------------------------------------------------------------------------------- */
    // Global Scopes
    /* -------------------------------------------------------------------------------------------- */
    #[Override]
    protected static function booted(): void
    {
        self::addGlobalScope('team', function (Builder $builder): void {
            // Skip if the user is a guest
            if (auth()->guest()) {
                return;
            }

            // Apply team scope if the user is not a developer
            if (auth()->user()->is_developer) {
                return;
            }

            $builder->where('couples.team_id', auth()->user()->currentTeam?->id);
        });
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    /** @return Attribute<string|null, never> */
    protected function name(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            // Guard against unloaded relationships
            if (! $this->relationLoaded('person1') || ! $this->relationLoaded('person2')) {
                return null;
            }

            if (! $this->person1 || ! $this->person2) {
                return null;
            }

            $names = array_filter([
                $this->person1->name,
                $this->person2->name,
            ]);

            return $names !== [] ? implode(' & ', $names) : null;
        });
    }

    /** @return Attribute<string|null, never> */
    protected function dateStartFormatted(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            return $this->date_start ? Carbon::parse($this->date_start)->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : null;
        });
    }

    protected function casts(): array
    {
        return [
            'date_start' => 'date:Y-m-d',
            'date_end'   => 'date:Y-m-d',
            'is_married' => 'boolean',
            'has_ended'  => 'boolean',
        ];
    }
}
