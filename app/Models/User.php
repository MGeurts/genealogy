<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

// ------------------------------------------------------------------------------------------------------
// ATTENTION :
// the user attribute "is_developer" should be set directly in the database
// by the application developer on the user account he want to use to manage the whole applacation
// ------------------------------------------------------------------------------------------------------

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

    protected $fillable = [
        'firstname',
        'surname',

        'email',
        'password',

        'language',
        'is_developer',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_developer' => 'boolean',
    ];

    protected $appends = [
        'profile_photo_url',
        'name',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function getNameAttribute(): ?string
    {
        return implode(' ', array_filter([$this->firstname, $this->surname]));
    }

    public function hasPermission(string $permission): bool
    {
        return $this->hasTeamPermission($this->currentTeam, $permission);
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* OK : returns ALL USERLOG (N USERLOG) */
    public function userlogs(): HasMany
    {
        return $this->hasMany(Userlog::class);
    }
}
