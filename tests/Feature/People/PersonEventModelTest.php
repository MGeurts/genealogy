<?php

declare(strict_types=1);

use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->user->currentTeam()->associate($this->team);
    $this->user->save();

    $this->person = Person::factory()->create([]);

    $this->actingAs($this->user);
});

test('person event can be created', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id'   => $this->person->id,
        'type'        => PersonEvent::TYPE_BAPTISM,
        'description' => 'Test baptism',
        'date'        => '2000-01-15',
    ]);

    expect($event)->toBeInstanceOf(PersonEvent::class)
        ->and($event->person_id)->toBe($this->person->id)
        ->and($event->type)->toBe(PersonEvent::TYPE_BAPTISM)
        ->and($event->description)->toBe('Test baptism');
});

test('type label accessor returns translated label', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'type'      => PersonEvent::TYPE_BAPTISM,
    ]);

    expect($event->type_label)->toBeString()
        ->and($event->type_label)->toBe(__('personevents.' . PersonEvent::TYPE_BAPTISM));
});

test('type label is appended to array', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'type'      => PersonEvent::TYPE_BAPTISM,
    ]);

    $array = $event->toArray();
    expect($array)->toHaveKey('type_label');
});

test('date formatted accessor returns formatted date', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'date'      => '2000-01-15',
    ]);

    $expectedFormat = Carbon::parse('2000-01-15')
        ->timezone(session('timezone') ?? 'UTC')
        ->isoFormat('LL');

    expect($event->date_formatted)->toBe($expectedFormat);
});

test('date formatted accessor returns year when only year is set', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'date'      => null,
        'year'      => 1950,
    ]);

    expect($event->date_formatted)->toBe('1950');
});

test('date formatted accessor returns null when no date or year', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'date'      => null,
        'year'      => null,
    ]);

    expect($event->date_formatted)->toBeNull();
});

test('event year accessor returns year from date', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'date'      => '2000-01-15',
        'year'      => 1999,
    ]);

    expect($event->event_year)->toBe(2000);
});

test('event year accessor returns year field when no date', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'date'      => null,
        'year'      => 1950,
    ]);

    expect($event->event_year)->toBe(1950);
});

test('event year accessor returns null when no date or year', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'date'      => null,
        'year'      => null,
    ]);

    expect($event->event_year)->toBeNull();
});

test('address accessor builds full address from components', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id'   => $this->person->id,
        'street'      => 'Main Street',
        'number'      => '123',
        'postal_code' => '12345',
        'city'        => 'Boston',
        'province'    => 'Massachusetts',
        'state'       => 'MA',
    ]);

    expect($event->address)->toBe('Main Street 123, 12345 Boston, Massachusetts MA');
});

test('address accessor handles partial address components', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id'   => $this->person->id,
        'street'      => 'Main Street',
        'number'      => null,
        'postal_code' => null,
        'city'        => 'Boston',
        'province'    => null,
        'state'       => null,
    ]);

    expect($event->address)->toBe('Main Street, Boston');
});

test('address accessor returns null when no address components', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id'   => $this->person->id,
        'street'      => null,
        'number'      => null,
        'postal_code' => null,
        'city'        => null,
        'province'    => null,
        'state'       => null,
    ]);

    expect($event->address)->toBeNull();
});

test('address accessor trims whitespace correctly', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id'   => $this->person->id,
        'street'      => 'Main Street',
        'number'      => '',
        'postal_code' => '',
        'city'        => 'Boston',
        'province'    => '',
        'state'       => '',
    ]);

    expect($event->address)->toBe('Main Street, Boston');
});

test('all event type constants are valid', function (): void {
    $types = [
        PersonEvent::TYPE_BAPTISM,
        PersonEvent::TYPE_CHRISTENING,
        PersonEvent::TYPE_BURIAL,
        PersonEvent::TYPE_MILITARY_SERVICE,
        PersonEvent::TYPE_MIGRATION,
        PersonEvent::TYPE_EDUCATION,
        PersonEvent::TYPE_OCCUPATION,
        PersonEvent::TYPE_RESIDENCE,
        PersonEvent::TYPE_EMIGRATION,
        PersonEvent::TYPE_IMMIGRATION,
        PersonEvent::TYPE_NATURALIZATION,
        PersonEvent::TYPE_CENSUS,
        PersonEvent::TYPE_WILL,
        PersonEvent::TYPE_PROBATE,
        PersonEvent::TYPE_OTHER,
    ];

    expect($types)->toBe(PersonEvent::EVENT_TYPES)
        ->and(count(PersonEvent::EVENT_TYPES))->toBe(15);
});

test('event type constants have correct values', function (): void {
    expect(PersonEvent::TYPE_BAPTISM)->toBe('baptism')
        ->and(PersonEvent::TYPE_CHRISTENING)->toBe('christening')
        ->and(PersonEvent::TYPE_BURIAL)->toBe('burial')
        ->and(PersonEvent::TYPE_MILITARY_SERVICE)->toBe('military_service')
        ->and(PersonEvent::TYPE_MIGRATION)->toBe('migration')
        ->and(PersonEvent::TYPE_EDUCATION)->toBe('education')
        ->and(PersonEvent::TYPE_OCCUPATION)->toBe('occupation')
        ->and(PersonEvent::TYPE_RESIDENCE)->toBe('residence')
        ->and(PersonEvent::TYPE_EMIGRATION)->toBe('emigration')
        ->and(PersonEvent::TYPE_IMMIGRATION)->toBe('immigration')
        ->and(PersonEvent::TYPE_NATURALIZATION)->toBe('naturalization')
        ->and(PersonEvent::TYPE_CENSUS)->toBe('census')
        ->and(PersonEvent::TYPE_WILL)->toBe('will')
        ->and(PersonEvent::TYPE_PROBATE)->toBe('probate')
        ->and(PersonEvent::TYPE_OTHER)->toBe('other');
});

test('person event uses soft deletes', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
    ]);

    $event->delete();

    expect($event->trashed())->toBeTrue()
        ->and(PersonEvent::withTrashed()->count())->toBe(1)
        ->and(PersonEvent::count())->toBe(0);
});

test('person event can be restored after soft delete', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
    ]);

    $event->delete();
    expect($event->trashed())->toBeTrue();

    $event->restore();
    expect($event->trashed())->toBeFalse()
        ->and(PersonEvent::count())->toBe(1);
});

test('person event can be force deleted', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
    ]);

    $event->forceDelete();

    expect(PersonEvent::withTrashed()->count())->toBe(0);
});

test('metadata is cast to array', function (): void {
    $metadata = ['key' => 'value', 'nested' => ['data' => 123]];

    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'metadata'  => $metadata,
    ]);

    expect($event->metadata)->toBeArray()
        ->and($event->metadata)->toBe($metadata);
});

test('metadata can be null', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'metadata'  => null,
    ]);

    expect($event->metadata)->toBeNull();
});

test('date is cast to date object', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'date'      => '2000-01-15',
    ]);

    expect($event->date)->toBeInstanceOf(CarbonImmutable::class)
        ->and($event->date->format('Y-m-d'))->toBe('2000-01-15');
});

test('date can be null', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'date'      => null,
    ]);

    expect($event->date)->toBeNull();
});

test('year is cast to integer', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'year'      => '1950',
    ]);

    expect($event->year)->toBeInt()
        ->and($event->year)->toBe(1950);
});

test('year can be null', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'year'      => null,
    ]);

    expect($event->year)->toBeNull();
});

test('global scope does not apply when user is guest', function (): void {
    auth()->logout();

    PersonEvent::factory()->create([
        'person_id' => $this->person->id,
    ]);

    expect(PersonEvent::count())->toBe(1);
});

test('activity logging is configured correctly', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'type'      => PersonEvent::TYPE_BAPTISM,
    ]);

    $options = $event->getActivitylogOptions();

    expect($options->logName)->toBe('person_couple')
        ->and($options->logOnlyDirty)->toBeTrue()
        ->and($options->submitEmptyLogs)->toBeFalse();
});

test('type label accessor handles missing translation gracefully', function (): void {
    $event = PersonEvent::factory()->make([
        'person_id' => $this->person->id,
        'type'      => 'non_existent_type',
    ]);

    $label = $event->type_label;

    expect($label)->toBeString()
        ->and($label)->toBe('non_existent_type');
});

test('all required fields can be mass assigned', function (): void {
    $data = [
        'person_id'   => $this->person->id,
        'type'        => PersonEvent::TYPE_BAPTISM,
        'description' => 'Test description',
        'date'        => '2000-01-15',
        'year'        => 2000,
        'place'       => 'Test Place',
        'street'      => 'Main Street',
        'number'      => '123',
        'postal_code' => '12345',
        'city'        => 'Boston',
        'province'    => 'Massachusetts',
        'state'       => 'MA',
        'country'     => 'US',
        'metadata'    => ['test' => 'data'],
    ];

    $event = PersonEvent::create($data);

    expect($event->person_id)->toBe($data['person_id'])
        ->and($event->type)->toBe($data['type'])
        ->and($event->description)->toBe($data['description'])
        ->and($event->place)->toBe($data['place'])
        ->and($event->street)->toBe($data['street'])
        ->and($event->city)->toBe($data['city'])
        ->and($event->country)->toBe($data['country'])
        ->and($event->metadata)->toBe($data['metadata']);
});

test('factory creates valid events', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
    ]);

    expect($event)->toBeInstanceOf(PersonEvent::class)
        ->and($event->type)->toBeIn(PersonEvent::EVENT_TYPES);
});

test('factory baptism method creates baptism event', function (): void {
    $event = PersonEvent::factory()->baptism()->create([
        'person_id' => $this->person->id,
    ]);

    expect($event->type)->toBe(PersonEvent::TYPE_BAPTISM)
        ->and($event->place)->toContain('Church');
});

test('factory withDate method creates event with specific date', function (): void {
    $event = PersonEvent::factory()->withDate('2000-01-15')->create([
        'person_id' => $this->person->id,
    ]);

    expect($event->date->format('Y-m-d'))->toBe('2000-01-15')
        ->and($event->year)->toBeNull();
});

test('factory withYearOnly method creates event with only year', function (): void {
    $event = PersonEvent::factory()->withYearOnly(1950)->create([
        'person_id' => $this->person->id,
    ]);

    expect($event->year)->toBe(1950)
        ->and($event->date)->toBeNull();
});

test('factory withFullAddress method creates event with complete address', function (): void {
    $event = PersonEvent::factory()->withFullAddress()->create([
        'person_id' => $this->person->id,
    ]);

    expect($event->street)->not->toBeNull()
        ->and($event->number)->not->toBeNull()
        ->and($event->postal_code)->not->toBeNull()
        ->and($event->city)->not->toBeNull()
        ->and($event->country)->not->toBeNull();
});

test('factory withMetadata method creates event with metadata', function (): void {
    $event = PersonEvent::factory()->withMetadata()->create([
        'person_id' => $this->person->id,
    ]);

    expect($event->metadata)->toBeArray()
        ->and($event->metadata)->toHaveKeys(['source', 'confidence', 'notes']);
});
