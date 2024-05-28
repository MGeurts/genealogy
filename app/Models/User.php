<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

// -------------------------------------------------------------------------------------------
// ATTENTION :
// -------------------------------------------------------------------------------------------
// the user attribute "is_developer" should be set directly in the database
// by the application developer on the one user account needed to manage the whole application
// including user management and managing all people in all teams
// -------------------------------------------------------------------------------------------

class User extends Authenticatable
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
        'profile_photo_url',
        'name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'seen_at'           => 'datetime',
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_developer'      => 'boolean',
        ];
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    public function getNameAttribute(): ?string
    {
        return implode(' ', array_filter([$this->firstname, $this->surname]));
    }

    public function hasPermission(string $permission): bool
    {
        return $this->hasTeamPermission($this->currentTeam, $permission);
    }

    public function teamsStatistics()
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
        return array_sum(collect(json_decode(json_encode($this->teamsStatistics()), true))->pipe(function ($collection) {
            return collect([
                'users_count'   => $collection->sum('users_count'),
                'persons_count' => $collection->sum('persons_count'),
                'couples_count' => $collection->sum('couples_count'),
            ]);
        })->toArray()) == 0;
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* returns ALL USERLOGS (n Userlog) */
    public function userlogs(): HasMany
    {
        return $this->hasMany(Userlog::class);
    }
}
