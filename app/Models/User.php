<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * User Model
 *
 * Represents a user in the application with team management capabilities,
 * activity logging, and two-factor authentication support.
 *
 * Note: The 'is_developer' attribute should be set directly in the database
 * by the application developer for administrative access.
 *
 * @property int $id
 * @property string $firstname
 * @property string $surname
 * @property string $email
 * @property string $password
 * @property string $language
 * @property string $timezone
 * @property bool $is_developer
 * @property Carbon|null $seen_at
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string|null $name
 * @property-read string $profile_photo_url
 */
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

    /**
     * Cache for isDeletable check to avoid repeated queries
     */
    private ?bool $isDeletableCache = null;

    /* -------------------------------------------------------------------------------------------- */
    // Log activities
    /* -------------------------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('user_team')
            ->setDescriptionForEvent(fn (string $eventName): string => __('user.user') . ' ' . __('app.event_' . $eventName))
            ->logOnly([
                'firstname', 'surname',

                'email',

                'language', 'timezone',

                'is_developer',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->team_id = auth()->user()?->currentTeam?->id;
    }

    /* -------------------------------------------------------------------------------------------- */
    public function hasPermission(string $permission): bool
    {
        return $this->hasTeamPermission($this->currentTeam, $permission);
    }

    /**
     * Get statistics for all teams owned by this user
     */
    public function teamsStatistics(): Collection
    {
        return $this->ownedTeams()->withCount(['users as users_count', 'persons as persons_count', 'couples as couples_count'])->get(['id', 'name', 'personal_team']);
    }

    /**
     * Determine if this user can be safely deleted
     *
     * A user is deletable if they don't have any associated
     * users, persons, or couples in their teams.
     */
    public function isDeletable(): bool
    {
        if ($this->isDeletableCache !== null) {
            return $this->isDeletableCache;
        }

        $totalAssociations = $this->teamsStatistics()->sum(
            fn ($team) => $team->users_count + $team->persons_count + $team->couples_count
        );

        return $this->isDeletableCache = ($totalAssociations === 0);
    }

    /**
     * Check if user is a developer with admin privileges
     */
    public function isDeveloper(): bool
    {
        return $this->is_developer === true;
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
     * Boot method to handle model events
     */
    #[Override]
    protected static function boot(): void
    {
        parent::boot();

        // Clear cache when user is updated
        self::updated(function (User $user): void {
            $user->isDeletableCache = null;
        });
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function name(): Attribute
    {
        return Attribute::get(
            fn () => ($name = Str::of("{$this->firstname} {$this->surname}")->trim()->value()) === '' ? null : $name
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'password'                => 'hashed',
            'is_developer'            => 'boolean',
            'seen_at'                 => 'datetime',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}
