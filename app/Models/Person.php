<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Korridor\LaravelHasManyMerged\HasManyMerged;
use Korridor\LaravelHasManyMerged\HasManyMergedRelation;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Person extends Model implements HasMedia
{
    use HasManyMergedRelation;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'firstname',
        'surname',
        'birthname',
        'nickname',

        'sex',
        'gender_id',

        'father_id',
        'mother_id',
        'parents_id',

        'dob', 'yob', 'pob',
        'dod', 'yod', 'pod',

        'street', 'number',
        'postal_code', 'city',
        'province', 'state',
        'country_id',
        'phone',

        'photo',

        'team_id',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date:Y-m-d',
            'dod' => 'date:Y-m-d',
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
    // Scopes (global)
    /* -------------------------------------------------------------------------------------------- */
    protected static function booted(): void
    {
        static::addGlobalScope('team', function (Builder $builder) {
            if (! auth()->user()) {
                return;
            } elseif (config('app.god_mode') && auth()->user()->is_developer) {
                return true;
            } else {
                $builder->where('people.team_id', auth()->user()->currentTeam->id);
            }
        });
    }

    /* -------------------------------------------------------------------------------------------- */
    // Scopes (local)
    /* -------------------------------------------------------------------------------------------- */
    public function scopeSearch(Builder $query, string $value): void
    {
        if ($value != '%') {
            $query->whereAny(['firstname', 'surname', 'birthname', 'nickname'], 'LIKE', "%$value%");
        }
    }

    public function scopeOlderThan(Builder $query, ?string $birth_date, ?string $birth_year): void
    {
        if (empty($birth_date) and empty($birth_year)) {
            return;
        } else {
            $query->where(function ($q) use ($birth_date) {
                $q->whereNull('dob')->orWhere('dob', '<=', $birth_date);
            })->where(function ($q) use ($birth_year) {
                $q->whereNull('yob')->orWhere('yob', '<=', $birth_year);
            });
        }
    }

    public function scopeYoungerThan(Builder $query, ?string $birth_date, ?string $birth_year): void
    {
        if (empty($birth_date) and empty($birth_year)) {
            return;
        } else {
            $query->where(function ($q) use ($birth_date) {
                $q->whereNull('dob')->orWhere('dob', '>=', $birth_date);
            })->where(function ($q) use ($birth_year) {
                $q->whereNull('yob')->orWhere('yob', '>=', $birth_year);
            });
        }
    }

    public function scopePartnerOffset(Builder $query, ?string $birth_date, ?int $birth_year, int $offset = 40): void
    {
        // ------------------------------------------------------------------
        // offset : possible partners can be +/- n years older or younger
        // ------------------------------------------------------------------
        if (empty($birth_date) and empty($birth_year)) {
            return;
        } else {
            $query->where(function ($q) use ($birth_date, $offset) {
                $q->whereNull('dob')->orWhereBetween('dob', [date('Y-m-d', strtotime($birth_date . ' - ' . $offset . ' years')), date('Y-m-d', strtotime($birth_date . ' + ' . $offset . ' years'))]);
            })->where(function ($q) use ($birth_year, $offset) {
                $q->whereNull('yob')->orWhereBetween('yob', [$birth_year - $offset, $birth_year + $offset]);
            });
        }
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function getNameAttribute(): ?string
    {
        return implode(' ', array_filter([$this->firstname, $this->surname]));
    }

    protected function getAgeAttribute(): ?int
    {
        $age = null;

        if ($this->dob) {
            if ($this->dod) {
                // deceased based on dob & dod
                $age = Carbon::parse($this->dob)->diffInYears($this->dod);
            } elseif ($this->yod) {
                // deceased based on dob & yod
                $age = $this->yod - Carbon::parse($this->dob)->format('Y');
            } else {
                // living
                $age = Carbon::parse($this->dob)->diffInYears();
            }
        } elseif ($this->yob) {
            if ($this->dod) {
                // deceased based on yob & dod
                $age = Carbon::parse($this->dod)->format('Y') - $this->yod;
            } elseif ($this->yod) {
                // deceased based on yob & yod
                $age = $this->yod - $this->yob;
            } else {
                // living
                $age = Carbon::now()->format('Y') - $this->yob;
            }
        }

        return $age >= 0 ? $age : null;
    }

    protected function getNextBirthdayAttribute(): ?Carbon
    {
        if ($this->dob) {
            $today               = Carbon::parse(date('Y-m-d') . ' 00:00:00');
            $this_years_birthday = Carbon::parse(date('Y') . substr(strval($this->dob), 4));

            return $today->gt($this_years_birthday) ? $this_years_birthday->addYear() : $this_years_birthday;
        } else {
            return null;
        }
    }

    protected function getNextBirthdayAgeAttribute(): ?int
    {
        return $this->dob ? Carbon::parse($this->dob)->diffInYears() + 1 : null;
    }

    protected function getNextBirthdayRemainingDaysAttribute(): ?int
    {
        return $this->dob ? Carbon::now()->subDay()->diffInDays($this->next_birthday, false) : null;
    }

    protected function getLifetimeAttribute(): ?string
    {
        $lifetime = null;

        if ($this->dob) {
            if ($this->dod) {
                // deceased based on dob & dod
                $lifetime = Carbon::parse($this->dob)->format('Y') . ' - ' . Carbon::parse($this->dod)->format('Y');
            } elseif ($this->yod) {
                // deceased based on dob & yod
                $lifetime = Carbon::parse($this->dob)->format('Y') . ' - ' . $this->yod;
            } else {
                // living
                $lifetime = Carbon::parse($this->dob)->format('Y');
            }
        } elseif ($this->yob) {
            if ($this->dod) {
                // deceased based on yob & dod
                $lifetime = $this->yod . ' - ' . Carbon::parse($this->dod)->format('Y');
            } elseif ($this->yod) {
                // deceased based on yob & yod
                $lifetime = $this->yob . ' - ' . $this->yod;
            } else {
                // living
                $lifetime = strval($this->yob);
            }
        }

        return $lifetime; //returns YEAR(dob) - YEAR(dod)
    }

    protected function getBirthDateAttribute(): ?string
    {
        if ($this->dob) {
            $dob = $this->dob;
        } elseif ($this->yob) {
            $dob = $this->yob;
        } else {
            $dob = null;
        }

        return strval($dob);
    }

    protected function getBirthYearAttribute(): ?string
    {
        if ($this->dob) {
            $yob = Carbon::parse($this->dob)->isoFormat('Y');
        } elseif ($this->yob) {
            $yob = $this->yob;
        } else {
            $yob = null;
        }

        return strval($yob);
    }

    protected function getBirthFormattedAttribute(): ?string
    {
        if ($this->dob) {
            $dob = Carbon::parse($this->dob)->isoFormat('LL');
        } elseif ($this->yob) {
            $dob = $this->yob;
        } else {
            $dob = '??';
        }

        return strval($dob);
    }

    protected function getDeathFormattedAttribute(): ?string
    {
        if ($this->dod) {
            $dod = Carbon::parse($this->dod)->isoFormat('LL');
        } elseif ($this->yod) {
            $dod = $this->yod;
        } else {
            $dod = '??';
        }

        return strval($dod);
    }

    protected function getAddressAttribute(): ?string
    {
        return implode("\n", array_filter([
            implode(' ', array_filter([$this->street, $this->number])),
            implode(' ', array_filter([$this->postal_code, $this->city])),
            implode(' ', array_filter([$this->province, $this->state])),
            is_null($this->country_id) ? '' : $this->country->name,
        ]));
    }

    protected function getAddressGoogleAttribute(): ?string
    {
        $href_google_address = 'https://www.google.com/maps/search/';

        $address = implode(',', array_filter([
            implode(' ', array_filter([$this->street, $this->number])),
            implode(' ', array_filter([$this->postal_code, $this->city])),
            implode(' ', array_filter([$this->province, $this->state])),
            is_null($this->country_id) ? '' : $this->country->name,
        ]));

        return $address ? $href_google_address . $address : '';
    }

    protected function getCemeteryGoogleAttribute(): ?string
    {
        $href_google_address = 'https://www.google.com/maps/search/';
        $href_google_geo     = 'https://www.google.com/maps/search/?api=1&query=';

        if ($this->getMetadataValue('cemetery_location_latitude') && $this->getMetadataValue('cemetery_location_longitude')) {
            return $href_google_geo . implode('%2C', [
                $this->getMetadataValue('cemetery_location_latitude'),
                $this->getMetadataValue('cemetery_location_longitude'),
            ]);
        } elseif ($this->getMetadataValue('cemetery_location_address')) {
            return $href_google_address . str_replace("\n", ',', $this->getMetadataValue('cemetery_location_address'));
        } else {
            return '';
        }
    }

    public function isDeceased(): bool
    {
        return $this->dod != null or $this->yod != null;
    }

    public function isDeletable(): bool
    {
        return count($this->children) == 0 and count($this->couples) == 0;
    }

    public function isBirthdayToday(): bool
    {
        return $this->dob ? Carbon::parse($this->dob)->isBirthday() : false;
    }

    public function isDeathdayToday(): bool
    {
        return $this->dod ? Carbon::parse($this->dod)->isBirthday() : false;
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* returns TEAM (1 Team) based on team_id  */
    public function team(): BelongsTo
    {
        return $this->BelongsTo(Team::class);
    }

    /* lookup table */
    public function gender(): BelongsTo
    {
        return $this->BelongsTo(Gender::class);
    }

    /* returns FATHER (1 Person) based on father_id */
    public function father(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /* returns MOTHER (1 Person) based on mother_id */
    public function mother(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /* returns PARENTS (1 Couple) based on parents_id  */
    public function parents(): BelongsTo
    {
        return $this->belongsTo(Couple::class)->with(['person_1', 'person_2']);
    }

    /* returns OWN NATURAL CHILDREN (n Person) based on father_id OR mother_id, ordered by dob */
    public function children(): HasManyMerged
    {
        return $this->HasManyMerged(Person::class, ['father_id', 'mother_id'])->orderBy('dob');
    }

    public function children_with_children(): HasManyMerged // only used in family chart
    {
        return $this->HasManyMerged(Person::class, ['father_id', 'mother_id'])->with('children')->orderBy('dob');
    }

    /* returns ALL NATURAL CHILDREN (n Person) (OWN + CURRENT PARTNER), ordered by type and dob */
    public function childrenNaturalAll(): Collection
    {
        $children_natural = $this->children;
        $children_partner = $this->currentPartner() ? $this->currentPartner()->children : collect([]);

        $children = $children_natural->merge($children_partner);

        return $children->map(function ($child) use ($children_natural, $children_partner) {
            if ($children_natural->contains('id', $child->id)) {
                $child['type'] = null;
            } elseif ($children_partner->contains('id', $child->id)) {
                $child['type'] = '+';
            }

            return $child;
        })->sortby('dob')->sortBy('type');
    }

    /* returns ALL PARTNERS (n Person) related to the person, ordered by date_start */
    public function getPartnersAttribute(): Collection
    {
        if (! array_key_exists('partners', $this->relations)) {
            $partners_1 = $this->belongsToMany(Person::class, 'couples', 'person1_id', 'person2_id')
                ->withPivot(['id', 'date_start', 'date_end', 'is_married', 'has_ended'])
                ->with('children')
                ->orderByPivot('date_start')
                ->get();

            $partners_2 = $this->belongsToMany(Person::class, 'couples', 'person2_id', 'person1_id')
                ->withPivot(['id', 'date_start', 'date_end', 'is_married', 'has_ended'])
                ->with('children')
                ->orderByPivot('date_start')
                ->get();

            $this->setRelation('partners', $partners_1->merge($partners_2));
        }

        return $this->getRelation('partners');
    }

    /* returns CURRENT PARTNER (1 Person) related to the person, where relation not_ended and date_end == null */
    public function currentPartner(): ?Person
    {
        return $this->partners->where('pivot.has_ended', false)->whereNull('pivot.date_end')->sortBy('pivot.date_start')->last();
    }

    /* returns ALL PARTNERSHIPS (n Couple) related to the person, ordered by date_start */
    public function couples(): HasManyMerged
    {
        return $this->HasManyMerged(Couple::class, ['person1_id', 'person2_id'])->with(['person_1', 'person_2']);
    }

    /* returns country related to the persons address */
    public function country(): BelongsTo
    {
        return $this->BelongsTo(Country::class);
    }

    /* returns ALL METADATA (n PersonMetadata) related to the person */
    public function metadata(): HasMany
    {
        return $this->hasMany(PersonMetadata::class);
    }

    /* returns 1 METADATA (1 PersonMetadata value) related to the person */
    public function getMetadataValue($key = null): ?string
    {
        if ($key) {
            return $this->metadata->firstWhere('key', $key) ? $this->metadata->firstWhere('key', $key)->value : null;
        } else {
            return null;
        }
    }

    /* updates or creates 1 to n METADATA related to the person */
    public function updateMetadata(Collection $personMetadata)
    {
        foreach (PersonMetadata::METADATA_KEYS as $key) {
            if ($personMetadata->has($key)) {
                PersonMetadata::updateOrCreate(
                    [
                        // find using ...
                        'person_id' => $this->id,
                        'key'       => $key,
                    ],
                    [
                        // update or create using above and ...
                        'value' => $personMetadata->get($key),
                    ]
                );
            }
        }
    }

    /* returns ALL SIBLINGS (n Person) related to the person, either through father_id, mother_id or parents_id ordered by birth */
    public function siblings(): Collection
    {
        if (! $this->father_id && ! $this->mother_id && ! $this->parents_id) {
            return collect([]);
        } else {
            $siblings_father  = $this->father_id ? Person::where('id', '!=', $this->id)->where('father_id', $this->father_id)->get() : collect([]);
            $siblings_mother  = $this->mother_id ? Person::where('id', '!=', $this->id)->where('mother_id', $this->mother_id)->get() : collect([]);
            $siblings_parents = $this->parents_id ? Person::where('id', '!=', $this->id)->where('parents_id', $this->parents_id)->get() : collect([]);

            $siblings = $siblings_father->merge($siblings_mother)->merge($siblings_parents);

            return $siblings->map(function ($sibling) use ($siblings_father, $siblings_mother, $siblings_parents) {
                if ($siblings_father->contains('id', $sibling->id) && $siblings_mother->contains('id', $sibling->id)) {
                    $sibling['type'] = '';
                } elseif ($siblings_father->contains('id', $sibling->id) || $siblings_mother->contains('id', $sibling->id)) {
                    $sibling['type'] = '[1/2]';
                } elseif ($siblings_parents->contains('id', $sibling->id)) {
                    $sibling['type'] = '[+]';
                }

                return $sibling;
            })->sortByDesc('birth');
        }
    }

    public function siblings_with_children(): Collection // only used in family chart
    {
        if (! $this->father_id && ! $this->mother_id && ! $this->parents_id) {
            return collect([]);
        } else {
            $siblings_father  = $this->father_id ? Person::where('id', '!=', $this->id)->where('father_id', $this->father_id)->with('children')->get() : collect([]);
            $siblings_mother  = $this->mother_id ? Person::where('id', '!=', $this->id)->where('mother_id', $this->mother_id)->with('children')->get() : collect([]);
            $siblings_parents = $this->parents_id ? Person::where('id', '!=', $this->id)->where('parents_id', $this->parents_id)->with('children')->get() : collect([]);

            $siblings = $siblings_father->merge($siblings_mother)->merge($siblings_parents);

            return $siblings->map(function ($sibling) use ($siblings_father, $siblings_mother, $siblings_parents) {
                if ($siblings_father->contains('id', $sibling->id) && $siblings_mother->contains('id', $sibling->id)) {
                    $sibling['type'] = '';
                } elseif ($siblings_father->contains('id', $sibling->id) || $siblings_mother->contains('id', $sibling->id)) {
                    $sibling['type'] = '[1/2]';
                } elseif ($siblings_parents->contains('id', $sibling->id)) {
                    $sibling['type'] = '[+]';
                }

                return $sibling;
            })->sortByDesc('birth');
        }
    }
}
