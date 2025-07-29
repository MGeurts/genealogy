<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Countries;
use Carbon\Carbon;
use FilesystemIterator;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Korridor\LaravelHasManyMerged\HasManyMerged;
use Korridor\LaravelHasManyMerged\HasManyMergedRelation;
use Override;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

final class Person extends Model implements HasMedia
{
    use HasFactory;
    use HasManyMergedRelation;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

        'summary',

        'street', 'number',
        'postal_code', 'city',
        'province', 'state',
        'country',
        'phone',

        'photo',

        'team_id',
    ];

    /**
     * Use the built-in $casts property for automatic casting.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'dob' => 'date:Y-m-d',
        'dod' => 'date:Y-m-d',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'name',
    ];

    /* -------------------------------------------------------------------------------------------- */
    // Log activities
    /* -------------------------------------------------------------------------------------------- */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('person_couple')
            ->setDescriptionForEvent(fn (string $eventName): string => __('person.person') . ' ' . __('app.event_' . $eventName))
            ->logOnly([
                'firstname',
                'surname',
                'birthname',
                'nickname',

                'sex',
                'gender.name',

                'father.name',
                'mother.name',
                'parents.name',

                'dob', 'yob', 'pob',
                'dod', 'yod', 'pod',

                'summary',

                'street', 'number',
                'postal_code', 'city',
                'province', 'state',
                'country',
                'phone',

                'photo',

                'team.name',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->team_id = auth()->user()?->currentTeam?->id ?? null;
    }

    /* -------------------------------------------------------------------------------------------- */
    // Local Scopes
    /* -------------------------------------------------------------------------------------------- */
    #[Scope]
    public function scopeSearch(Builder $query, string $searchString): void
    {
        /* -------------------------------------------------------------------------------------------- */
        // The system wil look up every word in the search value in the attributes surname, firstname, birthname and nickname
        // Begin the search string with % if you want to search parts of names, for instance %Jr.
        // Be aware that this kinds of searches are slower.
        // If a name containes any spaces, enclose the name in double quoutes, for instance "John Fitzgerald Jr." Kennedy.
        /* -------------------------------------------------------------------------------------------- */
        if ($searchString !== '%') {
            collect(str_getcsv($searchString, ' ', '"'))->filter()->each(function (string $searchTerm) use ($query): void {
                $query->whereAny(['firstname', 'surname', 'birthname', 'nickname'], 'like', $searchTerm . '%');
            });
        }
    }

    #[Scope]
    public function scopeYoungerThan(Builder $query, ?string $year): void
    {
        if ($year !== null) {
            $year = (int) $year;

            $query
                ->where(function ($q) use ($year): void {
                    $q->whereNull('dob')->orWhere(DB::raw('YEAR(dob)'), '>=', $year);
                })
                ->where(function ($q) use ($year): void {
                    $q->whereNull('yob')->orWhere('yob', '>=', $year);
                });
        }
    }

    #[Scope]
    public function scopeOlderThan(Builder $query, ?string $year): void
    {
        if ($year !== null) {
            $year = (int) $year;

            $query
                ->where(function ($q) use ($year): void {
                    $q->whereNull('dob')->orWhere(DB::raw('YEAR(dob)'), '<=', $year);
                })
                ->where(function ($q) use ($year): void {
                    $q->whereNull('yob')->orWhere('yob', '<=', $year);
                });
        }
    }

    #[Scope]
    public function scopePartnerOffset(Builder $query, ?string $year, int $offset = 40): void
    {
        // ------------------------------------------------------------------------
        // offset : possible partners can be +/- n ($offset) years older or younger
        // ------------------------------------------------------------------------
        if ($year !== null) {
            $year     = (int) $year;
            $min_year = $year - $offset;
            $max_year = $year + $offset;

            $query
                ->where(function ($q) use ($min_year, $max_year): void {
                    $q->whereNull('dob')->orWhereBetween(DB::raw('YEAR(dob)'), [$min_year, $max_year]);
                })
                ->where(function ($q) use ($min_year, $max_year): void {
                    $q->whereNull('yob')->orWhereBetween('yob', [$min_year, $max_year]);
                });
        }
    }

    /* -------------------------------------------------------------------------------------------- */
    // Counters and checks
    /* -------------------------------------------------------------------------------------------- */
    public function countFiles(): int
    {
        return $this->getMedia('files')?->count() ?? 0;
    }

    public function countPhotos(): int
    {
        // Define the path
        $directory = public_path('storage/photos/' . $this->team_id);

        // Check if the directory exists
        if (! is_dir($directory)) {
            return 0;
        }

        $count = 0;
        foreach (new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS) as $file) {
            if ($file->isFile() && str_starts_with($file->getFilename(), "{$this->id}_") && str_ends_with($file->getFilename(), '.webp')) {
                $count++;
            }
        }

        return $count;
    }

    public function isDeceased(): bool
    {
        return ! is_null($this->dod) || ! is_null($this->yod);
    }

    public function isDeletable(): bool
    {
        return $this->children->isEmpty() && $this->couples->isEmpty();
    }

    public function isBirthdayToday(): bool
    {
        return $this->dob && Carbon::parse($this->dob)->isBirthday();
    }

    public function isDeathdayToday(): bool
    {
        return $this->dod && Carbon::parse($this->dod)->isBirthday();
    }

    /* -------------------------------------------------------------------------------------------- */
    // Relations
    /* -------------------------------------------------------------------------------------------- */
    /* returns TEAM (1 Team) based on team_id */
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
        return $this->belongsTo(self::class);
    }

    /* returns MOTHER (1 Person) based on mother_id */
    public function mother(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    /* returns PARENTS (1 Couple) based on parents_id */
    public function parents(): BelongsTo
    {
        return $this->belongsTo(Couple::class)->with(['person_1', 'person_2']);
    }

    /* returns OWN NATURAL CHILDREN (n Person) based on father_id OR mother_id, ordered by dob */
    public function children(): HasManyMerged
    {
        return $this->HasManyMerged(self::class, ['father_id', 'mother_id'])->orderBy('dob');
    }

    public function children_with_children(): HasManyMerged // only used in family chart
    {
        return $this->HasManyMerged(self::class, ['father_id', 'mother_id'])->with('children')->orderBy('dob');
    }

    /* returns ALL NATURAL CHILDREN (n Person) (OWN + CURRENT PARTNER), ordered by type, birthyear */
    public function childrenNaturalAll(): Collection
    {
        $children_natural = $this->children;
        $children_partner = $this->currentPartner()?->children ?: collect([]);

        return $children_natural->merge($children_partner)->map(function (Person $child) use ($children_natural, $children_partner): mixed {
            $child['type'] = $children_natural->contains('id', $child->id) ? null : ($children_partner->contains('id', $child->id) ? '+' : null);

            return $child;
        })->sortBy(['birthYear', 'type']);
    }

    /* returns ALL PARTNERS (n Person) related to the person, ordered by date_start */
    public function getPartnersAttribute(): Collection
    {
        if (! array_key_exists('partners', $this->relations)) {
            $partners = $this->belongsToMany(self::class, 'couples', 'person1_id', 'person2_id')
                ->withPivot(['id', 'date_start', 'date_end', 'is_married', 'has_ended'])
                ->with('children')
                ->orderByPivot('date_start')
                ->get()
                ->merge(
                    $this->belongsToMany(self::class, 'couples', 'person2_id', 'person1_id')
                        ->withPivot(['id', 'date_start', 'date_end', 'is_married', 'has_ended'])
                        ->with('children')
                        ->orderByPivot('date_start')
                        ->get()
                );

            $this->setRelation('partners', $partners);
        }

        return $this->getRelation('partners');
    }

    /* returns CURRENT PARTNER (1 Person) related to the person, where relation not_ended and date_end === null */
    public function currentPartner(): ?self
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
            $metadata = $this->metadata->firstWhere('key', $key);

            return $metadata ? $metadata->value : null;
        }

        return null;
    }

    /* updates, deletes if empty or creates 1 to n METADATA related to the person */
    public function updateMetadata(Collection $personMetadata): void
    {
        // First, delete any existing metadata where the value is empty
        foreach (PersonMetadata::METADATA_KEYS as $key) {
            if ($personMetadata->has($key) && empty($personMetadata->get($key))) {
                PersonMetadata::where('person_id', $this->id)
                    ->where('key', $key)
                    ->delete();
            }
        }

        // Collect data to update or create
        $data = [];
        foreach (PersonMetadata::METADATA_KEYS as $key) {
            if ($personMetadata->has($key) && ! empty($personMetadata->get($key))) {
                $data[] = [
                    'person_id' => $this->id,
                    'key'       => $key,
                    'value'     => $personMetadata->get($key),
                ];
            }
        }

        // Perform bulk insert or update if there is data
        if ($data !== []) {
            PersonMetadata::upsert($data, ['person_id', 'key'], ['value']);
        }
    }

    /* returns ALL SIBLINGS (n Person) related to the person, either through father_id, mother_id or parents_id ordered by type, birthyear */
    public function siblings(bool $withChildren = false): Collection
    {
        // Check if there are any parent identifiers to avoid unnecessary queries
        if (! $this->father_id && ! $this->mother_id && ! $this->parents_id) {
            return collect([]);
        }

        // Prepare the query conditionally based on $withChildren
        $query = (fn ($column, $id) => Person::where('id', '!=', $this->id)
            ->where($column, $id)
            ->when($withChildren, fn ($q) => $q->with('children'))
            ->get());

        // Get siblings from each parent or both parents
        $siblings_father  = $this->father_id ? $query('father_id', $this->father_id) : collect([]);
        $siblings_mother  = $this->mother_id ? $query('mother_id', $this->mother_id) : collect([]);
        $siblings_parents = $this->parents_id ? $query('parents_id', $this->parents_id) : collect([]);

        // Merge the results and ensure no duplicate siblings are included
        $siblings = $siblings_father->merge($siblings_mother)->merge($siblings_parents)->unique('id');

        return $siblings->map(function (Person $sibling) use ($siblings_father, $siblings_mother, $siblings_parents): Person {
            // Determine the sibling's type based on the shared parent(s)
            if ($siblings_father->contains('id', $sibling->id) && $siblings_mother->contains('id', $sibling->id)) {
                $sibling['type'] = ''; // Full siblings (same mother and father)
            } elseif ($siblings_father->contains('id', $sibling->id) || $siblings_mother->contains('id', $sibling->id)) {
                $sibling['type'] = '[1/2]'; // Half siblings (same father or mother)
            } elseif ($siblings_parents->contains('id', $sibling->id)) {
                $sibling['type'] = '[+]'; // Step-siblings or other variations
            }

            return $sibling;
        })->sortBy(['birthYear', 'type']);
    }

    /* -------------------------------------------------------------------------------------------- */
    // Scopes (global)
    /* -------------------------------------------------------------------------------------------- */
    #[Override]
    protected static function booted(): void
    {
        self::addGlobalScope('team', function (Builder $builder): void {
            if (Auth::guest() || auth()->user()->is_developer) {
                return;
            }

            $builder->where('people.team_id', auth()->user()->currentTeam->id);
        });
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function getNameAttribute(): ?string
    {
        $name = mb_trim("{$this->firstname} {$this->surname}");

        return $name ?: null;
    }

    protected function getAgeAttribute(): ?int
    {
        if ($this->dob) {
            if ($this->dod) {
                // deceased based on dob & dod
                $age = (int) Carbon::parse($this->dob)->diffInYears($this->dod);
            } elseif ($this->yod) {
                // deceased based on dob & yod
                $age = $this->yod - Carbon::parse($this->dob)->format('Y');
            } else {
                // living
                $age = (int) Carbon::parse($this->dob)->diffInYears();
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
                $age = Carbon::today()->format('Y') - $this->yob;
            }
        } else {
            $age = null;
        }

        return $age >= 0 ? $age : null;
    }

    protected function getNextBirthdayAttribute(): ?Carbon
    {
        if ($this->dob) {
            $today               = Carbon::today();
            $this_years_birthday = Carbon::parse(date('Y') . mb_substr((string) ($this->dob), 4));

            return $today->gt($this_years_birthday) ? $this_years_birthday->copy()->addYear() : $this_years_birthday;
        }

        return null;
    }

    protected function getNextBirthdayAgeAttribute(): ?int
    {
        return $this->dob ? Carbon::parse($this->dob)->age + 1 : null;
    }

    protected function getNextBirthdayRemainingDaysAttribute(): ?int
    {
        if (! $this->dob) {
            return null;
        }

        $today            = Carbon::today();
        $birthdayThisYear = Carbon::parse($this->dob)->year($today->year);

        // If the birthday is today, return 0 days remaining
        if ($birthdayThisYear->isToday()) {
            return 0;
        }

        // Determine if the next birthday is this year or next year
        $nextBirthday = $birthdayThisYear->isPast() ? $birthdayThisYear->addYear() : $birthdayThisYear;

        return (int) $today->diffInDays($nextBirthday, false);
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
                $lifetime = (string) ($this->yob);
            }
        } else {
            $lifetime = null;
        }

        return $lifetime ? $lifetime : null; // returns YEAR(dob) - YEAR(dod) or null
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

        return (string) $year;
    }

    protected function getDeathYearAttribute(): ?string
    {
        if ($this->dod) {
            $year = Carbon::parse($this->dod)->format('Y');
        } elseif ($this->yod) {
            $year = $this->yod;
        } else {
            $year = null;
        }

        return (string) $year;
    }

    protected function getBirthFormattedAttribute(): ?string
    {
        if ($this->dob) {
            $birth = Carbon::parse($this->dob)->timezone(session('timezone') ?? 'UTC')->isoFormat('LL');
        } elseif ($this->yob) {
            $birth = $this->yob;
        } else {
            $birth = null;
        }

        return (string) $birth;
    }

    protected function getDeathFormattedAttribute(): ?string
    {
        if ($this->dod) {
            $dead = Carbon::parse($this->dod)->timezone(session('timezone') ?? 'UTC')->isoFormat('LL');
        } elseif ($this->yod) {
            $dead = $this->yod;
        } else {
            $dead = null;
        }

        return (string) $dead;
    }

    protected function getAddressAttribute(): ?string
    {
        $countries = new Countries(app()->getLocale());

        $components = [
            mb_trim("{$this->street} {$this->number}"),
            mb_trim("{$this->postal_code} {$this->city}"),
            mb_trim("{$this->province} {$this->state}"),
            $this->country ? $countries->getCountryName($this->country) : null,
        ];

        // Filter empty components and implode with newline characters.
        $address = implode("\n", array_filter($components));

        return $address ?: null;
    }

    protected function getAddressGoogleAttribute(): ?string
    {
        $countries         = new Countries(app()->getLocale());
        $hrefGoogleAddress = 'https://www.google.com/maps/search/';

        $components = [
            mb_trim("{$this->street} {$this->number}"),
            mb_trim("{$this->postal_code} {$this->city}"),
            mb_trim("{$this->province} {$this->state}"),
            $this->country ? $countries->getCountryName($this->country) : null,
        ];

        // Filter empty components, implode with commas, and URL-encode the address.
        $address = implode(',', array: array_filter(array : $components));

        return $address !== '' ? $hrefGoogleAddress . urlencode($address) : null;
    }

    protected function getCemeteryGoogleAttribute(): ?string
    {
        $hrefGoogleGeo     = 'https://www.google.com/maps/search/?api=1&query=';
        $hrefGoogleAddress = 'https://www.google.com/maps/search/';

        $latitude  = $this->getMetadataValue('cemetery_location_latitude');
        $longitude = $this->getMetadataValue('cemetery_location_longitude');
        $address   = $this->getMetadataValue('cemetery_location_address');

        if ($latitude && $longitude) {
            return $hrefGoogleGeo . urlencode("{$latitude},{$longitude}");
        }
        if ($address) {
            return $hrefGoogleAddress . urlencode(str_replace("\n", ',', $address));
        }

        return null;
    }
}
