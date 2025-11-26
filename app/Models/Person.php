<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Countries;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
                'firstname', 'surname', 'birthname', 'nickname',

                'sex', 'gender.name',

                'father.name', 'mother.name', 'parents.name',

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
        // The system will look up every word in the search value in the attributes surname, firstname, birthname and nickname
        // Begin the search string with % if you want to search parts of names, for instance %Jr.
        // Be aware that this kinds of searches are slower.
        // If a name contains any spaces, enclose the name in double quotes, for instance "John Fitzgerald Jr." Kennedy.
        /* -------------------------------------------------------------------------------------------- */
        if (mb_trim($searchString) === '%' || empty(mb_trim($searchString))) {
            return;
        }

        // Sanitize: strip HTML tags and trim spaces
        $searchString = strip_tags(mb_trim($searchString));

        // Escape SQL wildcard characters in search terms
        $escapeLike = fn (string $value): string => str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $value);

        collect(str_getcsv($searchString, ' ', '"'))
            ->filter()
            ->each(function (string $searchTerm) use ($query, $escapeLike): void {
                // Check if term starts with % for wildcard search
                $isWildcard = str_starts_with($searchTerm, '%');

                if ($isWildcard) {
                    // Remove the % prefix and escape the rest
                    $term        = mb_ltrim($searchTerm, '%');
                    $escapedTerm = $escapeLike($term);
                    // Add % at both ends for LIKE %term% (search anywhere in field)
                    $term = '%' . $escapedTerm . '%';
                } else {
                    // Normal prefix search - escape and add % at end
                    $term = $escapeLike($searchTerm) . '%';
                }

                $query->whereAny(['firstname', 'surname', 'birthname', 'nickname'], 'like', $term);
            });
    }

    #[Scope]
    public function scopeYoungerThan(Builder $query, ?string $dob, ?int $yob): void
    {
        if (empty($dob) && empty($yob)) {
            return; // No input → return all
        }

        $query->where(function ($q) use ($dob, $yob): void {
            // Case: dob is given (most accurate)
            if (! empty($dob)) {
                $dobYear = (int) mb_substr($dob, 0, 4);

                $q->where(function ($sub) use ($dob, $dobYear): void {
                    $sub->whereNull('dob')->whereNull('yob') // no data, assume younger
                        ->orWhere('dob', '>', $dob)
                        ->orWhere(function ($inner) use ($dobYear): void {
                            $inner->whereNull('dob')->where('yob', '>', $dobYear);
                        });
                });
            } elseif (! empty($yob)) {
                // Case: only yob is given
                $q->where(function ($sub) use ($yob): void {
                    $sub->whereNull('dob')->whereNull('yob') // no data, assume younger
                        ->orWhere('dob', '>', "{$yob}-12-31")
                        ->orWhere(function ($inner) use ($yob): void {
                            $inner->whereNull('dob')->where('yob', '>', $yob);
                        });
                });
            }
        });
    }

    #[Scope]
    public function scopeOlderThan(Builder $query, ?string $dob, ?int $yob): void
    {
        if (empty($dob) && empty($yob)) {
            return; // No input → return all
        }

        $query->where(function ($q) use ($dob, $yob): void {
            // Case: Input dob is given (most accurate)
            if (! empty($dob)) {
                $dobYear = (int) mb_substr($dob, 0, 4);

                $q->where(function ($sub) use ($dob, $dobYear): void {
                    $sub->whereNull('dob')->whereNull('yob') // no data, assume older
                        ->orWhere('dob', '<', $dob)
                        ->orWhere(function ($inner) use ($dobYear): void {
                            $inner->whereNull('dob')->where('yob', '<', $dobYear);
                        });
                });
            } elseif (! empty($yob)) {
                // Case: Only yob is given
                $q->where(function ($sub) use ($yob): void {
                    $sub->whereNull('dob')->whereNull('yob') // no data, assume older
                        ->orWhere('dob', '<', "{$yob}-01-01")
                        ->orWhere(function ($inner) use ($yob): void {
                            $inner->whereNull('dob')->where('yob', '<', $yob);
                        });
                });
            }
        });
    }

    #[Scope]
    public function scopePartnerOffset(Builder $query, ?string $dob, ?int $yob, int $offset = 40): void
    {
        if (empty($dob) && empty($yob)) {
            return; // No input → return all
        }

        $query->where(function ($q) use ($dob, $yob, $offset): void {
            if (! empty($dob)) {
                $refDate = Carbon::parse($dob);
                $minDate = $refDate->copy()->subYears($offset)->toDateString();
                $maxDate = $refDate->copy()->addYears($offset)->toDateString();
                $refYear = (int) $refDate->format('Y');
                $minYear = $refYear - $offset;
                $maxYear = $refYear + $offset;

                $q->where(function ($sub) use ($minDate, $maxDate, $minYear, $maxYear): void {
                    $sub->whereNull('dob')->whereNull('yob') // no data, include by default
                        ->orWhereBetween('dob', [$minDate, $maxDate])
                        ->orWhere(function ($inner) use ($minYear, $maxYear): void {
                            $inner->whereNull('dob')->whereBetween('yob', [$minYear, $maxYear]);
                        });
                });
            } elseif (! empty($yob)) {
                $minYear = $yob - $offset;
                $maxYear = $yob + $offset;
                $minDate = "{$minYear}-01-01";
                $maxDate = "{$maxYear}-12-31";

                $q->where(function ($sub) use ($minDate, $maxDate, $minYear, $maxYear): void {
                    $sub->whereNull('dob')->whereNull('yob') // no data, include by default
                        ->orWhereBetween('dob', [$minDate, $maxDate])
                        ->orWhere(function ($inner) use ($minYear, $maxYear): void {
                            $inner->whereNull('dob')->whereBetween('yob', [$minYear, $maxYear]);
                        });
                });
            }
        });
    }

    /* -------------------------------------------------------------------------------------------- */
    // Checks
    /* -------------------------------------------------------------------------------------------- */
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
        return $this->belongsTo(Team::class);
    }

    /* lookup table */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
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
        return $this->belongsTo(Couple::class)->with(['person1', 'person2']);
    }

    /* returns OWN NATURAL CHILDREN (n Person) based on father_id OR mother_id, ordered by dob */
    public function children(): HasManyMerged
    {
        return $this->hasManyMerged(self::class, ['father_id', 'mother_id'])->orderBy('dob');
    }

    public function children_with_children(): HasManyMerged // only used in family chart
    {
        return $this->hasManyMerged(self::class, ['father_id', 'mother_id'])->with('children')->orderBy('dob');
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
            // Fetch partners where this person is person1
            $partnersAsPerson1 = $this->belongsToMany(self::class, 'couples', 'person1_id', 'person2_id')
                ->withPivot(['id', 'date_start', 'date_end', 'is_married', 'has_ended'])
                ->orderByPivot('date_start')
                ->get();

            // Fetch partners where this person is person2
            $partnersAsPerson2 = $this->belongsToMany(self::class, 'couples', 'person2_id', 'person1_id')
                ->withPivot(['id', 'date_start', 'date_end', 'is_married', 'has_ended'])
                ->orderByPivot('date_start')
                ->get();

            // Merge both collections FIRST
            $partners = $partnersAsPerson1->merge($partnersAsPerson2);

            // THEN eager load children for ALL partners at once
            $partners->load('children');

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
        return $this->hasManyMerged(Couple::class, ['person1_id', 'person2_id'])->with(['person1', 'person2']);
    }

    /* returns ALL METADATA (n PersonMetadata) related to the person */
    public function metadata(): HasMany
    {
        return $this->hasMany(PersonMetadata::class);
    }

    /* returns 1 METADATA (1 PersonMetadata value) related to the person */
    public function getMetadataValue(?string $key = null): ?string
    {
        if (! $key) {
            return null;
        }

        $metadata = $this->metadata->firstWhere('key', $key);

        return $metadata ? $metadata->value : null;
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
        // Early return if no parent information
        if (! $this->father_id && ! $this->mother_id && ! $this->parents_id) {
            return collect([]);
        }

        $siblings = collect();

        if ($this->father_id) {
            $siblings = $siblings->merge(
                $withChildren ? $this->halfSiblingsFather()->with('children')->get() : $this->halfSiblingsFather()->get()
            );
        }

        if ($this->mother_id) {
            $siblings = $siblings->merge(
                $withChildren ? $this->halfSiblingsMother()->with('children')->get() : $this->halfSiblingsMother()->get()
            );
        }

        if ($this->parents_id) {
            $siblings = $siblings->merge(
                $withChildren ? $this->fullSiblings()->with('children')->get() : $this->fullSiblings()->get()
            );
        }

        // Remove duplicates and add type information
        return $siblings->unique('id')
            ->map(function (Person $sibling): Person {
                $sibling['type'] = $this->determineSiblingType($sibling);

                return $sibling;
            })
            ->sortBy(['birthYear', 'type']);
    }

    /* -------------------------------------------------------------------------------------------- */
    // Scopes (global)
    /* -------------------------------------------------------------------------------------------- */
    #[Override]
    protected static function booted(): void
    {
        // Team scope
        self::addGlobalScope('team', function (Builder $builder): void {
            if (Auth::guest() || auth()->user()->is_developer) {
                return;
            }

            $builder->where('people.team_id', auth()->user()->currentTeam->id);
        });

        // Handle force deletes (permanent deletion only)
        self::forceDeleted(function (Person $person): void {
            // Clean up photos
            Storage::disk('photos')->deleteDirectory($person->team_id . '/' . $person->id);

            // Clean up files
            $person->clearMediaCollection('files');
        });
    }

    /* -------------------------------------------------------------------------------------------- */
    // Accessors & Mutators
    /* -------------------------------------------------------------------------------------------- */
    protected function name(): Attribute
    {
        return Attribute::get(function (): ?string {
            $name = Str::of("{$this->firstname} {$this->surname}")->trim()->value();

            return $name === '' ? null : $name;
        });
    }

    protected function age(): Attribute
    {
        return Attribute::make(get: function (): ?int {
            if ($this->dob) {
                if ($this->dod) {
                    // deceased based on dob & dod
                    $age = (int) Carbon::parse($this->dob)->diffInYears($this->dod);
                } elseif ($this->yod) {
                    // deceased based on dob & yod
                    $age = $this->yod - (int) Carbon::parse($this->dob)->format('Y');
                } else {
                    // living
                    $age = (int) Carbon::parse($this->dob)->diffInYears();
                }
            } elseif ($this->yob) {
                if ($this->dod) {
                    // deceased based on yob & dod
                    $age = (int) Carbon::parse($this->dod)->format('Y') - $this->yob;
                } elseif ($this->yod) {
                    // deceased based on yob & yod
                    $age = $this->yod - $this->yob;
                } else {
                    // living
                    $age = (int) Carbon::today()->format('Y') - $this->yob;
                }
            } else {
                $age = null;
            }

            return $age !== null && $age >= 0 ? $age : null;
        });
    }

    protected function nextBirthday(): Attribute
    {
        return Attribute::make(get: function (): ?Carbon {
            if (! $this->dob) {
                return null;
            }

            $today             = Carbon::today();
            $thisYearsBirthday = Carbon::parse($this->dob)->year($today->year);

            return $today->gt($thisYearsBirthday) ? $thisYearsBirthday->addYear() : $thisYearsBirthday;
        });
    }

    protected function nextBirthdayAge(): Attribute
    {
        return Attribute::make(get: function (): ?int {
            return $this->dob ? Carbon::parse($this->dob)->age + 1 : null;
        });
    }

    protected function nextBirthdayRemainingDays(): Attribute
    {
        return Attribute::make(get: function (): ?int {
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
        });
    }

    protected function lifetime(): Attribute
    {
        return Attribute::make(get: function (): ?string {
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
        });
    }

    protected function birthYear(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            if ($this->dob) {
                $year = Carbon::parse($this->dob)->format('Y');
            } elseif ($this->yob) {
                $year = (string) $this->yob;
            } else {
                $year = null;
            }

            return $year;
        });
    }

    protected function deathYear(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            if ($this->dod) {
                $year = Carbon::parse($this->dod)->format('Y');
            } elseif ($this->yod) {
                $year = (string) $this->yod;
            } else {
                $year = null;
            }

            return $year;
        });
    }

    protected function birthFormatted(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            if ($this->dob) {
                $birth = Carbon::parse($this->dob)->timezone(session('timezone') ?? 'UTC')->isoFormat('LL');
            } elseif ($this->yob) {
                $birth = $this->yob;
            } else {
                $birth = null;
            }

            return (string) $birth;
        });
    }

    protected function deathFormatted(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            if ($this->dod) {
                $dead = Carbon::parse($this->dod)->timezone(session('timezone') ?? 'UTC')->isoFormat('LL');
            } elseif ($this->yod) {
                $dead = $this->yod;
            } else {
                $dead = null;
            }

            return (string) $dead;
        });
    }

    protected function address(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            $countries = new Countries(app()->getLocale());

            $components = array_filter([
                mb_trim("{$this->street} {$this->number}"),
                mb_trim("{$this->postal_code} {$this->city}"),
                mb_trim("{$this->province} {$this->state}"),
                $this->country ? $countries->getCountryName($this->country) : null,
            ]);

            // Implode with newline characters.
            $address = implode("\n", $components);

            return $address ?: null;
        });
    }

    protected function addressGoogle(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            $countries = new Countries(app()->getLocale());

            $components = array_filter([
                mb_trim("{$this->street} {$this->number}"),
                mb_trim("{$this->postal_code} {$this->city}"),
                mb_trim("{$this->province} {$this->state}"),
                $this->country ? $countries->getCountryName($this->country) : null,
            ]);

            if (empty($components)) {
                return null;
            }

            $address = implode(',', $components);

            return 'https://www.google.com/maps/search/' . urlencode($address);
        });
    }

    protected function cemeteryGoogle(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            $latitude  = $this->getMetadataValue('cemetery_location_latitude');
            $longitude = $this->getMetadataValue('cemetery_location_longitude');
            $address   = $this->getMetadataValue('cemetery_location_address');

            return match (true) {
                $latitude && $longitude => 'https://www.google.com/maps/search/?api=1&query=' . urlencode("{$latitude},{$longitude}"),
                $address                => 'https://www.google.com/maps/search/' . urlencode(str_replace("\n", ',', $address)),
                default                 => null
            };
        });
    }

    protected function casts(): array
    {
        return [
            'dob' => 'date:Y-m-d',
            'dod' => 'date:Y-m-d',
            'yob' => 'integer',
            'yod' => 'integer',
        ];
    }

    /* -------------------------------------------------------------------------------------------- */
    // Optimized relationship methods for siblings
    /* -------------------------------------------------------------------------------------------- */
    private function fullSiblings(): HasMany
    {
        return $this->hasMany(self::class, 'parents_id', 'parents_id')
            ->where('id', '!=', $this->id);
    }

    private function halfSiblingsFather(): HasMany
    {
        return $this->hasMany(self::class, 'father_id', 'father_id')
            ->where('id', '!=', $this->id)
            ->whereNull('parents_id');
    }

    private function halfSiblingsMother(): HasMany
    {
        return $this->hasMany(self::class, 'mother_id', 'mother_id')
            ->where('id', '!=', $this->id)
            ->whereNull('parents_id');
    }

    // Determine the sibling's type based on the shared parent(s)
    private function determineSiblingType(self $sibling): string
    {
        $sharedFather  = $this->father_id && $this->father_id === $sibling->father_id;
        $sharedMother  = $this->mother_id && $this->mother_id === $sibling->mother_id;
        $sharedParents = $this->parents_id && $this->parents_id === $sibling->parents_id;

        return match (true) {
            $sharedParents || ($sharedFather && $sharedMother) => '',       // Full siblings
            $sharedFather || $sharedMother                     => '[1/2]',  // Half siblings
            default                                            => '[+]'     // Step-siblings
        };
    }
}
