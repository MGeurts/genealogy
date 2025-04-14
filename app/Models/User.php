<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

// -------------------------------------------------------------------------------------------
// ATTENTION :
// -------------------------------------------------------------------------------------------
// the user attribute "is_developer" should be set directly in the database
// by the application developer on the one user account needed to manage the whole application
// including user management and managing all people in all teams
// -------------------------------------------------------------------------------------------

final class User extends Authenticatable
    // ---------------------------------------------------------------------------------------
    // class User extends Authenticatable implements MustVerifyEmail
    //
    // Ref : https://jetstream.laravel.com/features/registration.html#email-verification
    // ---------------------------------------------------------------------------------------
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'surname',

        'email',
        'password',

        'language',
        'timezone',
        'is_developer',

        'seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'name',
        'profile_photo_url',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Log activities
    /* -------------------------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('user_team')
            ->setDescriptionForEvent(fn (string $eventName) => __('user.user') . ' ' . __('app.event_' . $eventName))
            ->logOnly([
                'firstname',
                'surname',

                'email',

                'language',
                'timezone',

                'is_developer',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->team_id = auth()->user()?->currentTeam?->id ?? null;
    }

    public function hasPermission(string $permission): bool
    {
        return $this->hasTeamPermission($this->currentTeam, $permission);
    }

    public function teamsStatistics(): Collection
    {
        return collect(DB::select('
            SELECT
                `id`, `name`, `personal_team`,
                (SELECT COUNT(*) FROM `users` INNER JOIN `team_user` ON `users`.`id` = `team_user`.`user_id` WHERE `teams`.`id` = `team_user`.`team_id` AND `users`.`deleted_at` IS NULL) AS `users_count`,
                (SELECT COUNT(*) FROM `people` WHERE `teams`.`id` = `people`.`team_id` AND `people`.`deleted_at` IS NULL) AS `persons_count`,
                (SELECT COUNT(*) FROM `couples` WHERE `teams`.`id` = `couples`.`team_id`) AS `couples_count`
            FROM `teams` WHERE `user_id` = ' . $this->id . ' ORDER BY `name` ASC;
        '));
    }

    public function isDeletable(): bool
    {
        $total = $this->teamsStatistics()->sum(fn($team) => $team->users_count + $team->persons_count + $team->couples_count);

        return $total === 0;
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* returns ALL USERLOGS (n Userlog) */
    public function userlogs(): HasMany
    {
        return $this->hasMany(Userlog::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_developer'      => 'boolean',
            'seen_at'           => 'datetime',
        ];
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function getNameAttribute(): ?string
    {
        return implode(' ', array_filter([$this->firstname, $this->surname]));
    }
}
