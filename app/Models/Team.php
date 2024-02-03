<?php

namespace App\Models;

use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends JetstreamTeam
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'personal_team' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* OK : returns ALL PERSON (n Person) */
    public function persons(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    /* OK : returns ALL COUPLE (n Couple) */
    public function couples(): HasMany
    {
        return $this->hasMany(Couple::class);
    }
}
