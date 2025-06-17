<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

final class TreeSeeder extends Seeder
{
    /**
     * Generates a tree with random siblings at each level,
     * but only one child continues the chain to max depth.
     * TEAM : Manager
     *
     * RECURSIVE SEEDER !!
     */
    protected int $managers_team = 5;

    protected int $level_max = 50; // Maximum depth of the tree

    protected int $totalPersonsCreated = 0;

    public function run(): void
    {
        // Randomly pick whether the root ancestor will be father or mother for their child.
        $initialParentType = rand(0, 1) === 0 ? 'father' : 'mother';

        // Create the first ancestor with sex matching role.
        $ancestor = Person::create([
            'firstname' => 'Ancestor',
            'surname'   => 'Level 0',
            'sex'       => $initialParentType === 'father' ? 'm' : 'f',
            'team_id'   => $this->managers_team,
        ]);

        $this->totalPersonsCreated++;
        echo "level = 0, id = {$ancestor->id}, sex = {$ancestor->sex}, role for next = {$initialParentType}\n";

        // Start creating descendants.
        $this->createChildren($ancestor, $initialParentType, 1);

        echo "Total persons created: {$this->totalPersonsCreated}\n";
    }

    protected function createChildren(Person $parent, string $parentType, int $level): void
    {
        if ($level > $this->level_max) {
            return;
        }

        $numChildren       = rand(1, 2);
        $mustContinueIndex = rand(1, $numChildren);

        for ($i = 1; $i <= $numChildren; $i++) {
            $nextParentType = rand(0, 1) === 0 ? 'father' : 'mother';
            $childSex       = $nextParentType === 'father' ? 'm' : 'f';

            $child = Person::create([
                'firstname' => "Child {$i} of {$parent->id}",
                'surname'   => "Level {$level}",
                'sex'       => $childSex,
                'father_id' => $parentType === 'father' ? $parent->id : null,
                'mother_id' => $parentType === 'mother' ? $parent->id : null,
                'team_id'   => $this->managers_team,
            ]);

            $this->totalPersonsCreated++;
            echo "level = {$level}, id = {$child->id}, parent = {$parent->id} as {$parentType}, child sex = {$childSex}\n";

            $continueBranch = ($i === $mustContinueIndex) || (rand(1, 100) <= 20);

            if ($continueBranch) {
                $this->createChildren($child, $nextParentType, $level + 1);
            } else {
                echo "Branch stops at level {$level} for child {$child->id}\n";
            }
        }
    }
}
