<?php

namespace Database\Seeders;

use App\Models\Couple;
use App\Models\Person;
use App\Models\PersonMetadata;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    protected $britisch_royals_team = 4;

    protected $kennedy_team = 5;

    protected $editors_team = 7;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->importBritishRoyalsPeople();
        $this->importBritishRoyalsCouples();

        $this->generateTestData();

        $this->importKennedyPeople();
        $this->importKennedyCouples();
    }

    protected function importBritishRoyalsPeople(): void
    {
        $xmlFile = file_get_contents(public_path('xml/british_royals_people.xml'));

        $xmlObject = simplexml_load_string($xmlFile);

        $json = json_encode($xmlObject);
        $result = json_decode($json, true);

        $people = ($result['people']);

        foreach ($people as $person) {
            Person::create([
                'id' => $person['id'],
                'firstname' => ! empty($person['firstname']) ? $person['firstname'] : null,
                'surname' => ! empty($person['surname']) ? $person['surname'] : null,
                'birthname' => ! empty($person['birthname']) ? $person['birthname'] : null,
                'nickname' => ! empty($person['nickname']) ? $person['nickname'] : null,

                'sex' => strtolower($person['sex']),

                'father_id' => ! empty($person['father_id']) ? $person['father_id'] : null,
                'mother_id' => ! empty($person['mother_id']) ? $person['mother_id'] : null,
                'parents_id' => ! empty($person['parents_id']) ? $person['parents_id'] : null,

                'dob' => ! empty($person['dob']) ? $person['dob'] : null,
                'yob' => ! empty($person['yob']) ? $person['yob'] : null,
                'pob' => ! empty($person['birth_place']) ? $person['birth_place'] : null,
                'dod' => ! empty($person['dod']) ? $person['dod'] : null,
                'yod' => ! empty($person['yod']) ? $person['yod'] : null,
                'pod' => ! empty($person['death_place']) ? $person['death_place'] : null,

                'photo' => $person['id'] . '_001_demo.webp',

                'team_id' => $this->britisch_royals_team,
            ]);
        }

        // -----------------------------------------------------
        // Metadata
        // -----------------------------------------------------
        $king_george_chapel = [1, 2, 31, 32, 33, 37];

        foreach ($king_george_chapel as $person) {
            PersonMetadata::create([
                'person_id' => $person,
                'key' => 'cemetery_location_name',
                'value' => 'King George VI Memorial Chapel',
            ]);
            PersonMetadata::create([
                'person_id' => $person,
                'key' => 'cemetery_location_address',
                'value' => 'Castle, 2 The Cloisters' . "\n" . 'Windsor SL4 1NJ' . "\n" . 'United Kindgom',
            ]);
            PersonMetadata::create([
                'person_id' => $person,
                'key' => 'cemetery_location_latitude',
                'value' => '51.483812',
            ]);
            PersonMetadata::create([
                'person_id' => $person,
                'key' => 'cemetery_location_longitude',
                'value' => '-0.606639',
            ]);
        }
        // -----------------------------------------------------
        PersonMetadata::create([
            'person_id' => 7,
            'key' => 'cemetery_location_name',
            'value' => 'Althorp Park, Northamptonshire (UK)',
        ]);
        PersonMetadata::create([
            'person_id' => 7,
            'key' => 'cemetery_location_address',
            'value' => 'Northampton NN7 4HG' . "\n" . 'United Kingdom',
        ]);
        PersonMetadata::create([
            'person_id' => 7,
            'key' => 'cemetery_location_latitude',
            'value' => '52.283112',
        ]);
        PersonMetadata::create([
            'person_id' => 7,
            'key' => 'cemetery_location_longitude',
            'value' => '-1.000299',
        ]);
    }

    protected function importBritishRoyalsCouples(): void
    {
        $xmlFile = file_get_contents(public_path('xml/british_royals_couples.xml'));

        $xmlObject = simplexml_load_string($xmlFile);

        $json = json_encode($xmlObject);
        $result = json_decode($json, true);

        $couples = ($result['couples']);

        foreach ($couples as $couple) {
            Couple::create([
                'id' => $couple['id'],

                'person1_id' => $couple['person1_id'],
                'person2_id' => $couple['person2_id'],

                'date_start' => ! empty($couple['date_start']) ? $couple['date_start'] : null,
                'date_end' => ! empty($couple['date_end']) ? $couple['date_end'] : null,

                'is_married' => $couple['status'] >= 1 ? 1 : 0,
                'has_ended' => $couple['status'] == 2 ? 1 : 0,

                'team_id' => $this->britisch_royals_team,
            ]);
        }
    }

    protected function importKennedyPeople(): void
    {
        $xmlFile = file_get_contents(public_path('xml/kennedy_people.xml'));

        $xmlObject = simplexml_load_string($xmlFile);

        $json = json_encode($xmlObject);
        $result = json_decode($json, true);

        $people = ($result['people']);

        foreach ($people as $person) {
            Person::create([
                'id' => $person['id'],
                'firstname' => ! empty($person['firstname']) ? $person['firstname'] : null,
                'surname' => ! empty($person['surname']) ? $person['surname'] : null,
                'birthname' => ! empty($person['birthname']) ? $person['birthname'] : null,
                'nickname' => ! empty($person['nickname']) ? $person['nickname'] : null,

                'sex' => strtolower($person['sex']),

                'father_id' => ! empty($person['father_id']) ? $person['father_id'] : null,
                'mother_id' => ! empty($person['mother_id']) ? $person['mother_id'] : null,
                'parents_id' => ! empty($person['parents_id']) ? $person['parents_id'] : null,

                'dob' => ! empty($person['dob']) ? $person['dob'] : null,
                'yob' => ! empty($person['yob']) ? $person['yob'] : null,
                'pob' => ! empty($person['birth_place']) ? $person['birth_place'] : null,
                'dod' => ! empty($person['dod']) ? $person['dod'] : null,
                'yod' => ! empty($person['yod']) ? $person['yod'] : null,
                'pod' => ! empty($person['death_place']) ? $person['death_place'] : null,

                'photo' => $person['id'] . '_001_demo.webp',

                'team_id' => $this->kennedy_team,
            ]);
        }
    }

    protected function importKennedyCouples(): void
    {
        $xmlFile = file_get_contents(public_path('xml/kennedy_couples.xml'));

        $xmlObject = simplexml_load_string($xmlFile);

        $json = json_encode($xmlObject);
        $result = json_decode($json, true);

        $couples = ($result['couples']);

        foreach ($couples as $couple) {
            Couple::create([
                'id' => $couple['id'],

                'person1_id' => $couple['person1_id'],
                'person2_id' => $couple['person2_id'],

                'date_start' => ! empty($couple['date_start']) ? $couple['date_start'] : null,
                'date_end' => ! empty($couple['date_end']) ? $couple['date_end'] : null,

                'is_married' => $couple['status'] >= 1 ? 1 : 0,
                'has_ended' => $couple['status'] == 2 ? 1 : 0,

                'team_id' => $this->kennedy_team,
            ]);
        }
    }

    protected function generateTestData(): void
    {
        // -----------------------------------------------------------------------
        // half-siblings
        // -----------------------------------------------------------------------
        Person::create([
            'id' => 101,
            'firstname' => 'Child',
            'surname' => 'Only FATHER side',
            'sex' => 'm',
            'dob' => '1999-01-04',

            'father_id' => 2,

            'team_id' => $this->britisch_royals_team,
        ]);

        Person::create([
            'id' => 102,
            'firstname' => 'Child',
            'surname' => 'Only MOTHER side',
            'sex' => 'm',
            'yob' => '1999',
            'yod' => '2009',

            'mother_id' => 1,

            'team_id' => $this->britisch_royals_team,
        ]);

        Person::create([
            'id' => 103,
            'firstname' => 'Child',
            'surname' => 'Only through PARENTS',
            'sex' => 'f',
            'yob' => '1999',

            'parents_id' => 1,

            'team_id' => $this->britisch_royals_team,
        ]);

        // -----------------------------------------------------------------------
        // gay relations
        // -----------------------------------------------------------------------
        Person::create([
            'id' => 201,
            'firstname' => 'Parent 1',
            'surname' => 'Gay',
            'sex' => 'f',
            'dob' => '1970-01-01',

            'team_id' => $this->britisch_royals_team,
        ]);

        Person::create([
            'id' => 202,
            'firstname' => 'Parent 2',
            'surname' => 'Gay',
            'sex' => 'f',
            'dob' => '1971-01-01',

            'team_id' => $this->britisch_royals_team,
        ]);

        Person::create([
            'id' => 203,
            'firstname' => 'Child 1',
            'surname' => 'Gay parents',
            'sex' => 'm',
            'dob' => '2000-01-01',
            'mother_id' => 201,

            'team_id' => $this->britisch_royals_team,
        ]);

        Person::create([
            'id' => 204,
            'firstname' => 'Child 2',
            'surname' => 'Gay parents',
            'sex' => 'm',
            'dob' => '2001-01-01',
            'mother_id' => 201,

            'team_id' => $this->britisch_royals_team,
        ]);

        Person::create([
            'id' => 205,
            'firstname' => 'New Partner',
            'surname' => 'King Charles',
            'sex' => 'm',
            'dob' => '1960-01-01',

            'team_id' => $this->britisch_royals_team,
        ]);

        Person::create([
            'id' => 206,
            'firstname' => 'Child 1',
            'surname' => 'New Partner King Charles',
            'sex' => 'm',
            'dob' => '2015-01-01',
            'father_id' => 205,

            'team_id' => $this->britisch_royals_team,
        ]);

        Couple::create([
            'id' => 101,
            'person1_id' => 201,
            'person2_id' => 202,
            'date_start' => '1995-01-01',

            'team_id' => $this->britisch_royals_team,
        ]);

        Couple::create([
            'id' => 102,
            'person1_id' => 3,
            'person2_id' => 205,
            'date_start' => '2010-01-01',

            'team_id' => $this->britisch_royals_team,
        ]);

        Person::create([
            'id' => 207,
            'firstname' => 'Child 3',
            'surname' => 'Gay parents',
            'sex' => 'f',
            'dob' => '2002-01-01',
            'parents_id' => 101,

            'team_id' => $this->britisch_royals_team,
        ]);

        Person::create([
            'id' => 208,
            'firstname' => 'Child 4',
            'surname' => 'Gay parents',
            'sex' => 'f',
            'dob' => '2003-01-01',
            'parents_id' => 101,

            'team_id' => $this->britisch_royals_team,
        ]);

        // -----------------------------------------------------------------------
        // re-married previous partner
        // -----------------------------------------------------------------------
        Couple::create([
            'id' => 201,
            'person1_id' => 5,
            'person2_id' => 18,
            'date_start' => '2005-01-01',
            'is_married' => true,

            'team_id' => $this->britisch_royals_team,
        ]);

        // -----------------------------------------------------------------------
        // people in other teams (than BRITISH ROYALS)
        // -----------------------------------------------------------------------
        Person::create([
            'id' => 209,
            'firstname' => 'John',
            'surname' => 'DOE',
            'sex' => 'm',
            'dob' => '1960-01-01',

            'team_id' => $this->editors_team,
        ]);

        Person::create([
            'id' => 210,
            'firstname' => 'Fu',
            'surname' => 'BAR',
            'sex' => 'm',
            'dob' => '2000-01-01',

            'team_id' => $this->editors_team,
        ]);
    }
}
