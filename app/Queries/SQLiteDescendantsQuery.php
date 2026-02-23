<?php

declare(strict_types=1);

namespace App\Queries;

use App\Contracts\DescendantsQueryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class SQLiteDescendantsQuery implements DescendantsQueryInterface
{
    /**
     * Get descendants for a person up to a maximum depth.
     *
     * @return Collection<int, object{
     *     id: int,
     *     firstname: string|null,
     *     surname: string|null,
     *     sex: string|null,
     *     father_id: int|null,
     *     mother_id: int|null,
     *     dod: string|null,
     *     yod: int|null,
     *     team_id: int|null,
     *     photo: string|null,
     *     dob: string|null,
     *     yob: int|null,
     *     degree: int,
     *     sequence: string
     * }>
     */
    public function getDescendants(int $personId, int $maxDepth): Collection
    {
        return collect(DB::select($this->getRecursiveQuery($personId, $maxDepth)));
    }

    /**
     * Build the recursive query for descendants.
     *
     * Two separate UNION ALL branches are used (one for father, one for mother)
     * rather than a single branch with OR. This allows MySQL to use indexes on
     * both father_id and mother_id independently, which is significantly faster
     * on large tables.
     *
     * The sequence column doubles as a cycle guard via FIND_IN_SET: if a person's
     * id already appears in the descendant chain, the join condition excludes them.
     * This prevents infinite loops caused by circular references in the data.
     *
     * REMARK: The maximum length of the comma separated sequence of all id's in the tree can NOT succeed 1024 characters!
     *         So, when largest id is 3 digits (max        999), the maximum level depth is 1024 / (3 + 1) = 256 levels
     *             when largest id is 4 digits (max      9.999), the maximum level depth is 1024 / (4 + 1) = 204 levels
     *             when largest id is 5 digits (max     99.999), the maximum level depth is 1024 / (5 + 1) = 170 levels
     *             when largest id is 6 digits (max    999.999), the maximum level depth is 1024 / (6 + 1) = 146 levels
     *             when largest id is 7 digits (max  9.999.999), the maximum level depth is 1024 / (7 + 1) = 128 levels
     *             when largest id is 8 digits (max 99.999.999), the maximum level depth is 1024 / (8 + 1) = 113 levels
     *             ...
     */
    private function getRecursiveQuery(int $personId, int $maxDepth): string
    {
        return "
            WITH RECURSIVE descendants AS (
                SELECT
                    id, firstname, surname, sex, father_id, mother_id, dod, yod, team_id, photo, dob, yob,
                    0 AS degree,
                    CAST(id AS TEXT) AS sequence
                FROM people
                WHERE deleted_at IS NULL AND id = $personId

                UNION ALL

                SELECT
                    p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo, p.dob, p.yob,
                    d.degree + 1 AS degree,
                    d.sequence || ',' || p.id AS sequence
                FROM people p
                JOIN descendants d ON p.father_id = d.id
                WHERE p.deleted_at IS NULL AND d.degree < $maxDepth AND NOT FIND_IN_SET(p.id, a.sequence)

                UNION ALL

                SELECT
                    p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo, p.dob, p.yob,
                    d.degree + 1 AS degree,
                    d.sequence || ',' || p.id AS sequence
                FROM people p
                JOIN descendants d ON p.mother_id = d.id
                WHERE p.deleted_at IS NULL AND d.degree < $maxDepth AND NOT FIND_IN_SET(p.id, a.sequence)
            )

            SELECT * FROM descendants
            ORDER BY degree, dob IS NULL, dob, yob IS NULL, yob;
        ";
    }
}
