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
     */
    protected int $managers_team = 5;

    protected int $level_max = 500;

    /**
     * Run the database seeds.
     */
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

        echo "level = 0, id = {$ancestor->id}, sex = {$ancestor->sex}, role for next = {$initialParentType}\n";

        // Start creating descendants.
        $this->createChildren($ancestor, $initialParentType, 1);
    }

    protected function createChildren(Person $parent, string $parentType, int $level): void
    {
        // How many children at this level? Random 1–3
        $numChildren = rand(1, 3);

        // Which child will continue the chain deeper?
        $chainChildIndex = rand(1, $numChildren);

        for ($i = 1; $i <= $numChildren; $i++) {
            $child = Person::create([
                'firstname' => "Child {$i} of {$parent->id}",
                'surname'   => "Level {$level}",
                'sex'       => ['m', 'f'][rand(0, 1)], // random sex for now
                'father_id' => $parentType === 'father' ? $parent->id : null,
                'mother_id' => $parentType === 'mother' ? $parent->id : null,
                'team_id'   => $this->managers_team,
            ]);

            echo "level = {$level}, id = {$child->id}, parent = {$parent->id} as {$parentType}, child sex = {$child->sex}\n";

            // If this is the chain child, decide their parent role for their children
            if ($i === $chainChildIndex && $level < $this->level_max) {
                // Choose what role this child will have for their child: father or mother
                $nextParentType = rand(0, 1) === 0 ? 'father' : 'mother';

                // Make sure their sex matches that role!
                $child->sex = $nextParentType === 'father' ? 'm' : 'f';
                $child->save();

                // Continue the chain deeper.
                $this->createChildren($child, $nextParentType, $level + 1);
            }
        }
    }
}
