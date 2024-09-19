<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Countries;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Korridor\LaravelHasManyMerged\HasManyMerged;
use Korridor\LaravelHasManyMerged\HasManyMergedRelation;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Symfony\Component\Finder\Finder;

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
        'country',
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
            if (Auth::guest()) {
                return;
            } elseif (Auth::user()->is_developer) {
                return true;
            } else {
                $builder->where('people.team_id', Auth::user()->currentTeam->id);
            }
        });
    }

    /* -------------------------------------------------------------------------------------------- */
    // Scopes (local)
    // The system wil look up every word in the search value in the attributes surname, firstname, birthname and nickname
    // Begin the search string with % if you want to search parts of names, for instance %Jr.
    // Be aware that this kinds of searches are slower.
    // If a name containes any spaces, enclose the name in double quoutes, for instance "John Jr." Kennedy.
    /* -------------------------------------------------------------------------------------------- */
    public function scopeSearch(Builder $query, string $searchString): void
    {
        if ($searchString != '%') {
            collect(str_getcsv($searchString, ' ', '"'))->filter()->each(function (string $searchTerm) use ($query) {
                $query->whereAny(['firstname', 'surname', 'birthname', 'nickname'], 'like', $searchTerm . '%');
            });
        }
    }

    public function scopeOlderThan(Builder $query, ?string $birth_year): void
    {
        if (empty($birth_year)) {
            return;
        } else {
            $query->where(function ($q) use ($birth_year) {
                $q->whereNull('dob')->orWhere(DB::raw('YEAR(dob)'), '<=', $birth_year);
            })->where(function ($q) use ($birth_year) {
                $q->whereNull('yob')->orWhere('yob', '<=', $birth_year);
            });
        }
    }

    public function scopeYoungerThan(Builder $query, ?string $birth_year): void
    {
        if (empty($birth_year)) {
            return;
        } else {
            $query->where(function ($q) use ($birth_year) {
                $q->whereNull('dob')->orWhere(DB::raw('YEAR(dob)'), '>=', $birth_year);
            })->where(function ($q) use ($birth_year) {
                $q->whereNull('yob')->orWhere('yob', '>=', $birth_year);
            });
        }
    }

    public function scopePartnerOffset(Builder $query, ?string $birth_year, int $offset = 40): void
    {
        // -------------------------------------------------------------------------
        // offset : possible partners can be +/- n ($offeset) years older or younger
        // -------------------------------------------------------------------------
        if (empty($birth_year)) {
            return;
        } else {
            $query->where(function ($q) use ($birth_year, $offset) {
                $q->whereNull('dob')->orWhereBetween(DB::raw('YEAR(dob)'), [intval($birth_year) - $offset, intval($birth_year) + $offset]);
            })->where(function ($q) use ($birth_year, $offset) {
                $q->whereNull('yob')->orWhereBetween('yob', [intval($birth_year) - $offset, intval($birth_year) + $offset]);
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
        } else {
            $age = null;
        }

        return $age > 0 ? $age : null;
    }

    protected function getNextBirthdayAttribute(): ?Carbon
    {
        if ($this->dob) {
            $today               = Carbon::today();
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
        } else {
            $lifetime = null;
        }

        return strval($lifetime); //returns YEAR(dob) - YEAR(dod)
    }

    protected function getBirthYearAttribute(): ?string
    {
        if ($this->dob) {
            $year = Carbon::parse($this->dob)->format('Y');
        } elseif ($this->yob) {
            $year = $this->yob;
        } else {
            $year = null;
        }

        return strval($year);
    }

    protected function getBirthFormattedAttribute(): ?string
    {
        if ($this->dob) {
            $birth = Carbon::parse($this->dob)->isoFormat('LL');
        } elseif ($this->yob) {
            $birth = $this->yob;
        } else {
            $birth = null;
        }

        return strval($birth);
    }

    protected function getDeathFormattedAttribute(): ?string
    {
        if ($this->dod) {
            $dead = Carbon::parse($this->dod)->isoFormat('LL');
        } elseif ($this->yod) {
            $dead = $this->yod;
        } else {
            $dead = null;
        }

        return strval($dead);
    }

    protected function getAddressAttribute(): ?string
    {
        $countries = new Countries(app()->getLocale());

        return implode("\n", array_filter([
            implode(' ', array_filter([$this->street, $this->number])),
            implode(' ', array_filter([$this->postal_code, $this->city])),
            implode(' ', array_filter([$this->province, $this->state])),
            $this->country ? $countries->get($this->country) : '',
        ]));
    }

    protected function getAddressGoogleAttribute(): ?string
    {
        $countries = new Countries(app()->getLocale());

        $href_google_address = 'https://www.google.com/maps/search/';

        $address = implode(',', array_filter([
            implode(' ', array_filter([$this->street, $this->number])),
            implode(' ', array_filter([$this->postal_code, $this->city])),
            implode(' ', array_filter([$this->province, $this->state])),
            $this->country ? $countries->get($this->country) : '',
        ]));

        return $address ? $href_google_address . $address : '';
    }

    protected function getCemeteryGoogleAttribute(): ?string
    {
        $href_google_address = 'https://www.google.com/maps/search/';
        $href_google_geo     = 'https://www.google.com/maps/search/?api=1&query=';

        if ($this->getMetadataValue('cemetery_location_latitude') and $this->getMetadataValue('cemetery_location_longitude')) {
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

    public function countFiles(): int
    {
        return $this->getMedia('files')->count();
    }

    public function countPhotos(): int
    {
        return count(Finder::create()->in(public_path('storage/photos/' . $this->team_id))->name($this->id . '_*.webp'));
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

    /* returns ALL NATURAL CHILDREN (n Person) (OWN + CURRENT PARTNER), ordered by type, birthdate */
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
        })->sortBy('birthDate')->sortBy('type');
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

    /* returns ALL SIBLINGS (n Person) related to the person, either through father_id, mother_id or parents_id ordered by type, birthdate */
    public function siblings(): Collection
    {
        if (! $this->father_id and ! $this->mother_id and ! $this->parents_id) {
            return collect([]);
        } else {
            $siblings_father  = $this->father_id ? Person::where('id', '!=', $this->id)->where('father_id', $this->father_id)->get() : collect([]);
            $siblings_mother  = $this->mother_id ? Person::where('id', '!=', $this->id)->where('mother_id', $this->mother_id)->get() : collect([]);
            $siblings_parents = $this->parents_id ? Person::where('id', '!=', $this->id)->where('parents_id', $this->parents_id)->get() : collect([]);

            $siblings = $siblings_father->merge($siblings_mother)->merge($siblings_parents);

            return $siblings->map(function ($sibling) use ($siblings_father, $siblings_mother, $siblings_parents) {
                if ($siblings_father->contains('id', $sibling->id) and $siblings_mother->contains('id', $sibling->id)) {
                    $sibling['type'] = '';
                } elseif ($siblings_father->contains('id', $sibling->id) or $siblings_mother->contains('id', $sibling->id)) {
                    $sibling['type'] = '[1/2]';
                } elseif ($siblings_parents->contains('id', $sibling->id)) {
                    $sibling['type'] = '[+]';
                }

                return $sibling;
            })->sortBy('birthDate')->sortBy('type');
        }
    }

    public function siblings_with_children(): Collection // only used in family chart
    {
        if (! $this->father_id and ! $this->mother_id and ! $this->parents_id) {
            return collect([]);
        } else {
            $siblings_father  = $this->father_id ? Person::where('id', '!=', $this->id)->where('father_id', $this->father_id)->with('children')->get() : collect([]);
            $siblings_mother  = $this->mother_id ? Person::where('id', '!=', $this->id)->where('mother_id', $this->mother_id)->with('children')->get() : collect([]);
            $siblings_parents = $this->parents_id ? Person::where('id', '!=', $this->id)->where('parents_id', $this->parents_id)->with('children')->get() : collect([]);

            $siblings = $siblings_father->merge($siblings_mother)->merge($siblings_parents);

            return $siblings->map(function ($sibling) use ($siblings_father, $siblings_mother, $siblings_parents) {
                if ($siblings_father->contains('id', $sibling->id) and $siblings_mother->contains('id', $sibling->id)) {
                    $sibling['type'] = '';
                } elseif ($siblings_father->contains('id', $sibling->id) or $siblings_mother->contains('id', $sibling->id)) {
                    $sibling['type'] = '[1/2]';
                } elseif ($siblings_parents->contains('id', $sibling->id)) {
                    $sibling['type'] = '[+]';
                }

                return $sibling;
            })->sortBy('birthDate')->sortBy('type');
        }
    }
}
