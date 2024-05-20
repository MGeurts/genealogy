<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Couple extends Model
{
    use LogsActivity;

    protected $fillable = [
        'person1_id',
        'person2_id',

        'date_start',
        'date_end',

        'is_married',
        'has_ended',

        'team_id',
    ];

    protected function casts(): array
    {
        return [
            'date_start' => 'date:Y-m-d',
            'date_end'   => 'date:Y-m-d',
            'is_married' => 'boolean',
            'has_ended'  => 'boolean',
        ];
    }

    protected $appends = [
        'name',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Log activity
    /* -------------------------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    /* -------------------------------------------------------------------------------------------- */
    // Scopes (Global)
    /* -------------------------------------------------------------------------------------------- */
    protected static function booted(): void
    {
        static::addGlobalScope('team', function (Builder $builder) {
            if (! auth()) {
                return;
            } elseif (config('app.god_mode') && auth()->user()->is_developer) {
                return true;
            } else {
                $builder->where('couples.team_id', auth()->user()->currentTeam->id);
            }
        });
    }

    /* -------------------------------------------------------------------------------------------- */
    // Scopes (Local)
    /* -------------------------------------------------------------------------------------------- */
    public function scopeOlderThan(Builder $query, ?string $date_start): void
    {
        if (empty($date_start)) {
            return;
        } else {
            $query->where(function ($q) use ($date_start) {
                $q->whereNull('date_start')->orWhere('date_start', '<=', $date_start);
            });
        }
    }

    public function scopeYoungerThan(Builder $query, ?string $date_start): void
    {
        if (empty($date_start)) {
            return;
        } else {
            $query->where(function ($q) use ($date_start) {
                $q->whereNull('date_start')->orWhere('date_start', '>=', $date_start);
            });
        }
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    public function getNameAttribute(): ?string
    {
        return implode(' - ', array_filter([
            implode(' ', array_filter([$this->person_1->firstname, $this->person_1->surname])),
            implode(' ', array_filter([$this->person_2->firstname, $this->person_2->surname])),
        ]));
    }

    protected function getDateStartFormattedAttribute(): ?string
    {
        return $this->date_start ? Carbon::parse($this->date_start)->isoFormat('LL') : '';
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* returns PARTNER 1 (1 Person) based on person1_id in Couple model */
    public function person_1(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person1_id')->withDefault(['name' => 'N/A']);
    }

    /* returns PARTNER 2 (1 Person) based on person2_id in Couple model */
    public function person_2(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person2_id')->withDefault(['name' => 'N/A']);
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
}
