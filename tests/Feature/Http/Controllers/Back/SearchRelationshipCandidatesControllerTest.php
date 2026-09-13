<?php

declare(strict_types=1);

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

test('relationship candidates are team-scoped, prefix-searched, and capped', function (): void {
    /** @var Tests\TestCase $this */
    $manager = $this->memberWithRole('manager');
    $parent  = Person::factory()->create([
        'team_id' => $manager->current_team_id,
        'dob'     => '2000-01-01',
    ]);

    $candidates = Person::factory()
        ->count(55)
        ->sequence(fn (Sequence $sequence): array => [
            'firstname' => 'Alfred ' . $sequence->index,
            'surname'   => 'Candidate',
            'sex'       => 'm',
            'dob'       => '1970-01-01',
            'yob'       => 1970,
            'team_id'   => $manager->current_team_id,
        ])
        ->create();

    $foreignCandidate = Person::factory()->create([
        'firstname' => 'Alfred Foreign',
        'surname'   => 'Candidate',
        'sex'       => 'm',
        'dob'       => '1970-01-01',
        'yob'       => 1970,
    ]);

    $response = $this->actingAs($manager)->getJson(route('people.relationship-candidates', [
        'person'       => $parent,
        'relationship' => 'father',
        'search'       => 'Al',
    ]));

    $response->assertOk()
        ->assertJsonCount(50)
        ->assertJsonFragment(['id' => $candidates->first()->id])
        ->assertJsonMissing(['id' => $foreignCandidate->id]);
});

test('relationship candidates require at least two search characters unless preserving a selection', function (): void {
    /** @var Tests\TestCase $this */
    $manager = $this->memberWithRole('manager');
    $parent  = Person::factory()->create([
        'team_id' => $manager->current_team_id,
        'dob'     => '2000-01-01',
    ]);
    $candidate = Person::factory()->create([
        'firstname' => 'Alfred',
        'sex'       => 'm',
        'dob'       => '1970-01-01',
        'yob'       => 1970,
        'team_id'   => $manager->current_team_id,
    ]);

    $route = route('people.relationship-candidates', [
        'person'       => $parent,
        'relationship' => 'father',
    ]);

    $this->actingAs($manager)
        ->getJson($route . '?search=A')
        ->assertExactJson([]);

    $this->actingAs($manager)
        ->getJson($route . '?selected=' . urlencode(json_encode([$candidate->id], JSON_THROW_ON_ERROR)))
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['id' => $candidate->id]);
});

test('relationship candidates require the matching create permission and current team', function (): void {
    /** @var Tests\TestCase $this */
    $member = $this->memberWithRole('member');
    $parent = Person::factory()->create(['team_id' => $member->current_team_id]);

    $this->actingAs($member)
        ->getJson(route('people.relationship-candidates', [
            'person'       => $parent,
            'relationship' => 'father',
            'search'       => 'Al',
        ]))
        ->assertForbidden();

    $otherUser   = User::factory()->withPersonalTeam()->create();
    $otherPerson = Person::factory()->create(['team_id' => $otherUser->current_team_id]);

    $this->actingAs($member)
        ->getJson(route('people.relationship-candidates', [
            'person'       => $otherPerson,
            'relationship' => 'father',
            'search'       => 'Al',
        ]))
        ->assertNotFound();
});
