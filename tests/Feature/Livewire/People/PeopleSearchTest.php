<?php

declare(strict_types=1);

use App\Livewire\People\Search;
use App\Models\Person;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->withPersonalTeam()->create();
    $this->team = $this->user->currentTeam;

    $this->actingAs($this->user);
});

// ------------------------------------------------------------------------------
// Component Mounting Tests
// ------------------------------------------------------------------------------
test('component can be mounted', function (): void {
    Livewire::test(Search::class)
        ->assertStatus(200);
});

test('mount initializes people count correctly', function (): void {
    Person::factory()->count(5)->create(['team_id' => $this->team->id]);

    Livewire::test(Search::class)
        ->assertSet('people_db', 5);
});

test('mount sets default perpage to 10', function (): void {
    Livewire::test(Search::class)
        ->assertSet('perpage', 10);
});

test('mount sets search to null by default', function (): void {
    Livewire::test(Search::class)
        ->assertSet('search', null);
});

// ------------------------------------------------------------------------------
// Search Functionality Tests
// ------------------------------------------------------------------------------
test('search by firstname returns matching results', function (): void {
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Doe',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'Jane',
        'surname'   => 'Smith',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', 'John')
        ->assertSee('John')
        ->assertDontSee('Jane');
});

test('search by surname returns matching results', function (): void {
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Doe',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'Jane',
        'surname'   => 'Smith',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', 'Doe')
        ->assertViewHas('people', function ($people) {
            return $people->count() === 1 &&
                   $people->first()->surname === 'Doe';
        });
});

test('search by birthname returns matching results', function (): void {
    Person::factory()->create([
        'firstname' => 'Jane',
        'surname'   => 'Doe',
        'birthname' => 'Johnson',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'Mary',
        'surname'   => 'Williams',
        'birthname' => null,
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', 'Johnson')
        ->assertViewHas('people', function ($people) {
            return $people->count() === 1 &&
                   $people->first()->birthname === 'Johnson';
        });
});

test('search by nickname returns matching results', function (): void {
    Person::factory()->create([
        'firstname' => 'Robert',
        'surname'   => 'Smith',
        'nickname'  => 'Bob',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Doe',
        'nickname'  => null,
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', 'Bob')
        ->assertViewHas('people', function ($people) {
            return $people->count() === 1 &&
                   $people->first()->nickname === 'Bob';
        });
});

test('search with multiple words uses AND logic', function (): void {
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Doe',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Smith',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'Jane',
        'surname'   => 'Doe',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', 'John Doe')
        ->assertSee('John Doe')
        ->assertDontSee('John Smith')
        ->assertDontSee('Jane Doe');
});

test('search with quoted phrase treats it as single term', function (): void {
    Person::factory()->create([
        'firstname' => 'John Fitzgerald',
        'surname'   => 'Kennedy',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Smith',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', '"John Fitzgerald" Kennedy')
        ->assertSee('Kennedy')
        ->assertDontSee('Smith');
});

test('search with wildcard prefix finds partial matches anywhere in field', function (): void {
    Person::factory()->create([
        'firstname' => 'Robert',
        'surname'   => 'Johnson Jr.',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Smith',
        'team_id'   => $this->team->id,
    ]);

    // Wildcard search should find "Jr." anywhere in the surname
    Livewire::test(Search::class)
        ->set('search', '%Jr')
        ->assertViewHas('people', function ($people) {
            return $people->count() === 1 &&
                   str_contains($people->first()->surname, 'Jr');
        });
});

test('search is case insensitive', function (): void {
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Doe',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', 'john')
        ->assertSee('John');

    Livewire::test(Search::class)
        ->set('search', 'JOHN')
        ->assertSee('John');
});

test('empty search returns all people', function (): void {
    Person::factory()->count(3)->create(['team_id' => $this->team->id]);

    Livewire::test(Search::class)
        ->set('search', '')
        ->assertViewHas('people', function ($people) {
            return $people->total() === 3;
        });
});

test('search with only whitespace returns all people', function (): void {
    Person::factory()->count(3)->create(['team_id' => $this->team->id]);

    Livewire::test(Search::class)
        ->set('search', '   ')
        ->assertViewHas('people', function ($people) {
            return $people->total() === 3;
        });
});

test('search with only percent sign returns all people', function (): void {
    Person::factory()->count(3)->create(['team_id' => $this->team->id]);

    Livewire::test(Search::class)
        ->set('search', '%')
        ->assertViewHas('people', function ($people) {
            return $people->total() === 3;
        });
});

// ------------------------------------------------------------------------------
// Input Sanitization Tests
// ------------------------------------------------------------------------------
test('search sanitizes HTML tags', function (): void {
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Doe',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', '<script>alert("XSS")</script>John')
        ->assertSee('John');
});

test('search trims leading and trailing spaces', function (): void {
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Doe',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', '   John   ')
        ->assertSee('John');
});

test('search validates max length of 255 characters', function (): void {
    $longString = str_repeat('a', 256);

    Livewire::test(Search::class)
        ->set('search', $longString)
        ->assertHasErrors(['search' => 'max']);
});

test('search escapes SQL wildcard characters when not using wildcard prefix', function (): void {
    Person::factory()->create([
        'firstname' => 'John_Doe',
        'surname'   => 'Smith',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'JohnXDoe',
        'surname'   => 'Johnson',
        'team_id'   => $this->team->id,
    ]);

    // Without % prefix, underscore should be escaped and only match literal underscore
    Livewire::test(Search::class)
        ->set('search', 'John_')
        ->assertViewHas('people', function ($people) {
            return $people->count() === 1 &&
                   $people->first()->firstname === 'John_Doe';
        });
});

test('wildcard search still escapes special characters in the search term', function (): void {
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'O\'Brien',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'Jane',
        'surname'   => 'Smith',
        'team_id'   => $this->team->id,
    ]);

    // Wildcard search with apostrophe should be safe
    Livewire::test(Search::class)
        ->set('search', '%O\'Brien')
        ->assertViewHas('people', function ($people) {
            return $people->count() === 1 &&
                   $people->first()->surname === 'O\'Brien';
        });
});

// ------------------------------------------------------------------------------
// Pagination Tests
// ------------------------------------------------------------------------------
test('perpage changes pagination size', function (): void {
    Person::factory()->count(30)->create(['team_id' => $this->team->id]);

    Livewire::test(Search::class)
        ->set('perpage', 5)
        ->assertViewHas('people', function ($people) {
            return $people->perPage() === 5;
        });
});

test('perpage validates allowed values', function (): void {
    Livewire::test(Search::class)
        ->set('perpage', 999)
        ->assertHasErrors(['perpage' => 'in']);
});

test('perpage accepts valid values', function (): void {
    $validValues = [5, 10, 25, 50, 100];

    foreach ($validValues as $value) {
        Livewire::test(Search::class)
            ->set('perpage', $value)
            ->assertHasNoErrors(['perpage']);
    }
});

test('changing search resets pagination to first page', function (): void {
    Person::factory()->count(30)->create(['team_id' => $this->team->id]);

    Livewire::test(Search::class)
        ->set('perpage', 10)
        ->call('gotoPage', 2)
        ->assertSet('paginators.page', 2)
        ->set('search', 'test')
        ->assertSet('paginators.page', 1);
});

test('changing perpage resets pagination to first page', function (): void {
    Person::factory()->count(30)->create(['team_id' => $this->team->id]);

    Livewire::test(Search::class)
        ->call('gotoPage', 2)
        ->assertSet('paginators.page', 2)
        ->set('perpage', 25)
        ->assertSet('paginators.page', 1);
});

// ------------------------------------------------------------------------------
// Ordering Tests
// ------------------------------------------------------------------------------
test('results are ordered by firstname then surname', function (): void {
    Person::factory()->create([
        'firstname' => 'Bob',
        'surname'   => 'Smith',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'Alice',
        'surname'   => 'Jones',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'Alice',
        'surname'   => 'Anderson',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->assertViewHas('people', function ($people) {
            return $people[0]->firstname === 'Alice' &&
                   $people[0]->surname === 'Anderson' &&
                   $people[1]->firstname === 'Alice' &&
                   $people[1]->surname === 'Jones' &&
                   $people[2]->firstname === 'Bob';
        });
});

// ------------------------------------------------------------------------------
// Relationship Loading Tests
// ------------------------------------------------------------------------------
test('component loads father and mother relationships', function (): void {
    $father = Person::factory()->create([
        'firstname' => 'Father',
        'surname'   => 'Doe',
        'sex'       => 'M',
        'team_id'   => $this->team->id,
    ]);
    $mother = Person::factory()->create([
        'firstname' => 'Mother',
        'surname'   => 'Doe',
        'sex'       => 'F',
        'team_id'   => $this->team->id,
    ]);
    $child = Person::factory()->create([
        'firstname' => 'Child',
        'surname'   => 'Doe',
        'father_id' => $father->id,
        'mother_id' => $mother->id,
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', 'Child')
        ->assertViewHas('people', function ($people) {
            return $people[0]->father !== null &&
                   $people[0]->father->firstname === 'Father' &&
                   $people[0]->mother !== null &&
                   $people[0]->mother->firstname === 'Mother';
        });
});

// ------------------------------------------------------------------------------
// Session Persistence Tests
// ------------------------------------------------------------------------------
test('search value persists in session', function (): void {
    $component = Livewire::test(Search::class)
        ->set('search', 'John');

    // Simulate a new request
    $newComponent = Livewire::test(Search::class);

    expect($newComponent->get('search'))->toBe('John');
});

// ------------------------------------------------------------------------------
// Team Scope Tests
// ------------------------------------------------------------------------------
test('search only returns people from current team', function (): void {
    $otherTeam = Team::factory()->create();

    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Doe',
        'team_id'   => $this->team->id,
    ]);
    Person::factory()->create([
        'firstname' => 'Jane',
        'surname'   => 'Smith',
        'team_id'   => $otherTeam->id,
    ]);

    Livewire::test(Search::class)
        ->assertViewHas('people', function ($people) {
            return $people->total() === 1 &&
                   $people[0]->firstname === 'John';
        });
});

// ------------------------------------------------------------------------------
// Edge Cases Tests
// ------------------------------------------------------------------------------
test('search with special characters is handled correctly', function (): void {
    Person::factory()->create([
        'firstname' => "O'Brien",
        'surname'   => 'Smith',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', "O'Brien")
        ->assertSee("O'Brien");
});

test('search with unicode characters works correctly', function (): void {
    Person::factory()->create([
        'firstname' => 'José',
        'surname'   => 'García',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', 'José')
        ->assertSee('José');
});

test('null search value is handled gracefully', function (): void {
    Person::factory()->count(3)->create(['team_id' => $this->team->id]);

    Livewire::test(Search::class)
        ->set('search', null)
        ->assertViewHas('people', function ($people) {
            return $people->total() === 3;
        });
});

test('search returns empty result when no matches found', function (): void {
    Person::factory()->create([
        'firstname' => 'John',
        'surname'   => 'Doe',
        'team_id'   => $this->team->id,
    ]);

    Livewire::test(Search::class)
        ->set('search', 'NonExistent')
        ->assertViewHas('people', function ($people) {
            return $people->total() === 0;
        });
});
