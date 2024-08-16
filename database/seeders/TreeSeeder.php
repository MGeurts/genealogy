<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

class TreeSeeder extends Seeder
{
    // -----------------------------------------------------------------------
    // this generates a father-son tree nested as deep as $level_max
    // and can be used to test the routines to display ancestors/descendants
    // -----------------------------------------------------------------------
    protected $managers_team = 5;

    protected $level_max = 500;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createPerson(null, 0);
    }

    protected function createPerson($father, $level)
    {
        $person = Person::create([
            'firstname' => 'Child of ' . ($father ? $father->id : 'Nobody'),
            'surname'   => 'Level ' . $level,
            'sex'       => 'm',
            'father_id' => ($father ? $father->id : null),
            'team_id'   => $this->managers_team,
        ]);

        echo 'level = ' . $level . ', id = ' . $person->id . ', father = ' . ($father ? $father->id : 'Nobody') . "\n";

        $level++;

        if ($level <= $this->level_max) {
            $this->createPerson($person, $level);
        }
    }
   // -----------------------------------------------------------------------
}
