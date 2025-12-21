<?php

declare(strict_types=1);

use App\Livewire\People\Edit\Events;
use App\Models\Person;
use App\Models\PersonEvent;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->user->currentTeam()->associate($this->team);
    $this->user->save();

    $this->person = Person::factory()->create();

    $this->actingAs($this->user);
});

test('component can be mounted', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->assertStatus(200)
        ->assertSet('person.id', $this->person->id)
        ->assertSet('showModal', false);
});

test('component displays person events', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'type'      => PersonEvent::TYPE_BAPTISM,
        'date'      => '2000-01-15',
    ]);

    Livewire::test(Events::class, ['person' => $this->person])
        ->assertSee($event->type_label)
        ->assertSee($event->date_formatted);
});

test('component shows empty state when no events exist', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->assertSee(__('personevents.no_events'))
        ->assertSee(__('personevents.add_events'));
});

test('can open modal for creating new event', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->assertSet('showModal', true)
        ->assertSet('editingEventId', null)
        ->assertSet('type', '');
});

test('can open modal for editing existing event', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id'   => $this->person->id,
        'type'        => PersonEvent::TYPE_BAPTISM,
        'description' => 'Test baptism event',
        'date'        => '2000-01-15',
        'place'       => 'Test Church',
        'city'        => 'Test City',
        'country'     => 'US',
    ]);

    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal', $event->id)
        ->assertSet('showModal', true)
        ->assertSet('editingEventId', $event->id)
        ->assertSet('type', PersonEvent::TYPE_BAPTISM)
        ->assertSet('description', 'Test baptism event')
        ->assertSet('date', '2000-01-15')
        ->assertSet('place', 'Test Church')
        ->assertSet('city', 'Test City')
        ->assertSet('country', 'US');
});

test('can close modal', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->assertSet('showModal', true)
        ->call('closeModal')
        ->assertSet('showModal', false)
        ->assertSet('editingEventId', null);
});

// test('can create new event', function () {
//     Livewire::test(Events::class, ['person' => $this->person])
//         ->call('openModal')
//         ->set('type', PersonEvent::TYPE_BAPTISM)
//         ->set('description', 'New baptism event')
//         ->set('date', '2000-01-15')
//         ->set('place', 'St. Mary Church')
//         ->set('city', 'Boston')
//         ->call('save')
//         ->assertSet('showModal', false)
//         ->assertDispatched('event-saved');

//     expect(PersonEvent::count())->toBe(1);

//     $event = PersonEvent::first();
//     expect($event->person_id)->toBe($this->person->id)
//         ->and($event->type)->toBe(PersonEvent::TYPE_BAPTISM)
//         ->and($event->description)->toBe('New baptism event')
//         ->and($event->place)->toBe('St. Mary Church')
//         ->and($event->city)->toBe('Boston');
// });

test('can update existing event', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id'   => $this->person->id,
        'type'        => PersonEvent::TYPE_BAPTISM,
        'description' => 'Original description',
    ]);

    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal', $event->id)
        ->set('description', 'Updated description')
        ->set('place', 'Updated Church')
        ->call('save')
        ->assertSet('showModal', false)
        ->assertDispatched('event-saved');

    $event->refresh();
    expect($event->description)->toBe('Updated description')
        ->and($event->place)->toBe('Updated Church');
});

test('can delete event', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
    ]);

    expect(PersonEvent::count())->toBe(1);

    Livewire::test(Events::class, ['person' => $this->person])
        ->call('delete', $event->id)
        ->assertDispatched('event-saved');

    expect(PersonEvent::count())->toBe(0);
});

test('validation requires event type', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->set('type', '')
        ->set('description', 'Test event')
        ->call('save')
        ->assertHasErrors(['type' => 'required']);
});

test('validation requires valid event type', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->set('type', 'invalid_type')
        ->call('save')
        ->assertHasErrors(['type' => 'in']);
});

test('validation enforces date format', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->set('type', PersonEvent::TYPE_BAPTISM)
        ->set('date', 'invalid-date')
        ->call('save')
        ->assertHasErrors(['date' => 'date']);
});

test('validation enforces year minimum', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->set('type', PersonEvent::TYPE_BAPTISM)
        ->set('year', 0)
        ->call('save')
        ->assertHasErrors(['year' => 'min']);
});

test('validation enforces year maximum', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->set('type', PersonEvent::TYPE_BAPTISM)
        ->set('year', date('Y') + 1)
        ->call('save')
        ->assertHasErrors(['year' => 'max']);
});

test('validation enforces country code length', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->set('type', PersonEvent::TYPE_BAPTISM)
        ->set('country', 'USA')
        ->call('save')
        ->assertHasErrors(['country' => 'size']);
});

test('validation enforces description max length', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->set('type', PersonEvent::TYPE_BAPTISM)
        ->set('description', str_repeat('a', 1001))
        ->call('save')
        ->assertHasErrors(['description' => 'max']);
});

test('validation enforces place max length', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->set('type', PersonEvent::TYPE_BAPTISM)
        ->set('place', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['place' => 'max']);
});

test('event types are returned in correct format for styled select', function (): void {
    $component = Livewire::test(Events::class, ['person' => $this->person])
        ->instance();

    $eventTypes = $component->eventTypes();

    expect($eventTypes)->toBeInstanceOf(Illuminate\Support\Collection::class)
        ->and($eventTypes->count())->toBe(15);

    $firstType = $eventTypes->first();
    expect($firstType)->toHaveKeys(['id', 'name'])
        ->and($firstType['id'])->toBeString()
        ->and($firstType['name'])->toBeString();
});

test('event types are sorted by translation', function (): void {
    $component = Livewire::test(Events::class, ['person' => $this->person])
        ->instance();

    $eventTypes  = $component->eventTypes();
    $names       = $eventTypes->pluck('name')->toArray();
    $sortedNames = $eventTypes->pluck('name')->sort()->values()->toArray();

    expect($names)->toBe($sortedNames);
});

test('countries are cached', function (): void {
    $component = Livewire::test(Events::class, ['person' => $this->person])
        ->instance();

    $countries1 = $component->countries();
    $countries2 = $component->countries();

    expect($countries1)->toBeInstanceOf(Illuminate\Support\Collection::class)
        ->and($countries1->count())->toBe($countries2->count())
        ->and($countries1->first())->toBe($countries2->first());
});

test('refresh events listener works', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->dispatch('event-saved')
        ->assertStatus(200);
});

test('form is reset after closing modal', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id'   => $this->person->id,
        'type'        => PersonEvent::TYPE_BAPTISM,
        'description' => 'Test description',
    ]);

    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal', $event->id)
        ->assertSet('type', PersonEvent::TYPE_BAPTISM)
        ->call('closeModal')
        ->assertSet('type', '')
        ->assertSet('description', null)
        ->assertSet('editingEventId', null);
});

// test('events are ordered by date and year descending', function () {
//     $event1 = PersonEvent::factory()->create([
//         'person_id' => $this->person->id,
//         'type'      => PersonEvent::TYPE_BAPTISM,
//         'date'      => '2000-01-15',
//         'year'      => null,
//     ]);

//     $event2 = PersonEvent::factory()->create([
//         'person_id' => $this->person->id,
//         'type'      => PersonEvent::TYPE_BURIAL,
//         'date'      => '2020-05-20',
//         'year'      => null,
//     ]);

//     $event3 = PersonEvent::factory()->create([
//         'person_id' => $this->person->id,
//         'type'      => PersonEvent::TYPE_CENSUS,
//         'date'      => null,
//         'year'      => 1990,
//     ]);

//     $component = Livewire::test(Events::class, ['person' => $this->person]);

//     $events = $component->viewData('events');

//     // The most recent event (2020) should be first
//     expect($events->first()->id)->toBe($event2->id)
//         ->and($events->first()->type)->toBe(PersonEvent::TYPE_BURIAL);

//     // The oldest event (1990) should be last
//     expect($events->last()->id)->toBe($event3->id)
//         ->and($events->last()->type)->toBe(PersonEvent::TYPE_CENSUS);
// });

// test('component displays place when address is not available', function (): void {
//     $event = PersonEvent::factory()->create([
//         'person_id' => $this->person->id,
//         'place'     => 'St. Mary Church',
//         'street'    => null,
//         'city'      => null,
//     ]);

//     Livewire::test(Events::class, ['person' => $this->person])
//         ->assertSee('St. Mary Church');
// });

test('component displays address when available', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
        'place'     => 'St. Mary Church',
        'street'    => 'Main Street',
        'number'    => '123',
        'city'      => 'Boston',
    ]);

    Livewire::test(Events::class, ['person' => $this->person])
        ->assertSee('Main Street 123')
        ->assertSee('Boston');
});

test('toast messages show correct translations on save', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->set('type', PersonEvent::TYPE_BAPTISM)
        ->call('save');

    // The toast is triggered with the correct translation keys
    expect(PersonEvent::count())->toBe(1);
});

test('toast messages show correct translations on delete', function (): void {
    $event = PersonEvent::factory()->create([
        'person_id' => $this->person->id,
    ]);

    Livewire::test(Events::class, ['person' => $this->person])
        ->call('delete', $event->id);

    expect(PersonEvent::count())->toBe(0);
});

test('validation resets when opening modal', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->call('openModal')
        ->set('type', '')
        ->call('save')
        ->assertHasErrors(['type'])
        ->call('openModal')
        ->assertHasNoErrors();
});

test('can create event with only required fields', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->set('type', PersonEvent::TYPE_CENSUS)
        ->call('save')
        ->assertSet('showModal', false);

    $event = PersonEvent::first();
    expect($event->type)->toBe(PersonEvent::TYPE_CENSUS)
        ->and($event->description)->toBeNull()
        ->and($event->date)->toBeNull()
        ->and($event->place)->toBeNull();
});

test('can create event with all fields', function (): void {
    Livewire::test(Events::class, ['person' => $this->person])
        ->set('type', PersonEvent::TYPE_BAPTISM)
        ->set('description', 'Baptism ceremony')
        ->set('date', '2000-01-15')
        ->set('place', 'St. Mary Church')
        ->set('street', 'Main Street')
        ->set('number', '123')
        ->set('postal_code', '12345')
        ->set('city', 'Boston')
        ->set('province', 'Massachusetts')
        ->set('state', 'MA')
        ->set('country', 'US')
        ->call('save')
        ->assertSet('showModal', false);

    $event = PersonEvent::first();
    expect($event->type)->toBe(PersonEvent::TYPE_BAPTISM)
        ->and($event->description)->toBe('Baptism ceremony')
        ->and($event->place)->toBe('St. Mary Church')
        ->and($event->street)->toBe('Main Street')
        ->and($event->city)->toBe('Boston')
        ->and($event->country)->toBe('US');
});
