<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

final class Team extends JetstreamTeam
{
    use HasFactory;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Log activities
    /* -------------------------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('user_team')
            ->setDescriptionForEvent(fn (string $eventName): string => __('team.team') . ' ' . __('app.event_' . $eventName))
            ->logOnly([
                'name',
                'description',
                'personal_team',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->team_id = auth()->user()?->currentTeam?->id ?? null;
    }

    /* -------------------------------------------------------------------------------------------- */
    public function isDeletable(): bool
    {
        // Prevent deletion of personal teams
        if ($this->personal_team) {
            return false;
        }

        // Use exists() queries instead of loading relationships
        // This only counts records without loading them into memory
        if ($this->users()->exists()) {
            return false;
        }

        if ($this->persons()->exists()) {
            return false;
        }

        if ($this->couples()->exists()) {
            return false;
        }

        return true;
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* returns ALL PERSONS (n Person) */
    public function persons(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    /* returns ALL COUPLES (n Couple) */
    public function couples(): HasMany
    {
        return $this->hasMany(Couple::class);
    }

    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }
}
