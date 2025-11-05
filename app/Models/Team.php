<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
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
            ->setDescriptionForEvent(function (string $eventName): string {
                return __('team.team') . ' ' . __('app.event_' . $eventName);
            })
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
        $user = auth()->user();

        if (! $user) {
            $activity->team_id = null;

            return;
        }

        $currentTeam = $user->currentTeam;

        // Don't set team_id if this team is being deleted or if no current team exists
        if (! $currentTeam || $currentTeam->id === $this->id) {
            // Try to use the user's personal team as fallback
            $personalTeam      = $user->personalTeam();
            $activity->team_id = $personalTeam?->id;
        } else {
            $activity->team_id = $currentTeam->id;
        }
    }

    /* -------------------------------------------------------------------------------------------- */
    public function isDeletable(): bool
    {
        // Prevent deletion of personal teams
        if ($this->personal_team) {
            return false;
        }

        // Developers can delete any non-personal team
        if (auth()->user()?->isDeveloper()) {
            return true;
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

    public function delete(): ?bool
    {
        // If user is a developer and this is not a personal team, handle cleanup
        if (auth()->user()?->isDeveloper() && ! $this->personal_team) {
            $this->handleCurrentTeamSwitch();
            $this->performDeveloperDelete();
        }

        return parent::delete();
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

    protected function handleCurrentTeamSwitch(): void
    {
        $user = auth()->user();

        // If this team is the user's current team, switch to their personal team
        if ($user && $user->currentTeam && $user->currentTeam->id === $this->id) {
            $personalTeam = $user->personalTeam();

            if ($personalTeam) {
                $user->switchTeam($personalTeam);
            } else {
                // Fallback: find another team the user belongs to
                $otherTeam = $user->allTeams()->where('id', '!=', $this->id)->first();
                if ($otherTeam) {
                    $user->switchTeam($otherTeam);
                }
            }
        }
    }

    protected function performDeveloperDelete(): void
    {
        DB::transaction(function (): void {
            // Load relationships once to avoid N+1 queries
            $this->load(['couples', 'users']);

            // Delete all persons and their photos and files
            $this->persons->each(function ($person): void {
                // TODO
                // remove photos
                // remove files

                $person->forceDelete();
            });

            // Delete all couples
            $this->couples->each(function ($couple): void {
                $couple->delete();
            });

            // Disconnect all users from this team
            $this->users()->detach();
        });
    }
}
