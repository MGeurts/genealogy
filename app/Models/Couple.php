<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

final class Couple extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
     * @var array<int, string>
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
                'person_1.name',
                'person_2.name',
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
        $activity->team_id = auth()->user()?->currentTeam?->id ?? null;
    }

    /* -------------------------------------------------------------------------------------------- */
    // Local Scopes
    /* -------------------------------------------------------------------------------------------- */
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
    public function person_1(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person1_id');
    }

    /* returns PARTNER 2 (1 Person) based on person2_id in Couple model */
    public function person_2(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person2_id');
    }

    /* returns ALL CHILDREN (n Person) based on parents_id in Person model */
    public function children(): HasMany
    {
        return $this->hasMany(Person::class, 'parents_id');
    }

    /* returns TEAM (1 Team) based on team_id in Team model */
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

            $builder->where('couples.team_id', auth()->user()->currentTeam->id);
        });
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function getNameAttribute(): ?string
    {
        $names = array_filter([
            optional($this->person_1)->name,
            optional($this->person_2)->name,
        ]);

        return $names !== [] ? implode(' - ', $names) : null;
    }

    protected function getDateStartFormattedAttribute(): ?string
    {
        return $this->date_start ? Carbon::parse($this->date_start)->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : null;
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
