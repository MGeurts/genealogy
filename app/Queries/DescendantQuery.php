<?php

declare(strict_types=1);

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class DescendantQuery
{
    public static function get(int $personId, int $countMax): string
    {
        return match (DB::getDriverName()) {
            'mysql' => self::getForMySQL($personId, $countMax),
        };
    }

    protected static function getForMySQL(int $personId, int $countMax): string
    {
        return "
        WITH RECURSIVE descendants AS (
        SELECT
            id, firstname, surname, sex, father_id, mother_id, dod, yod, team_id, photo, dob, yob,
            0 AS degree,
            CAST(id AS CHAR(1024)) AS sequence
        FROM people
        WHERE deleted_at IS NULL AND id = $personId

        UNION ALL

        SELECT
            p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo, p.dob, p.yob,
            d.degree + 1 AS degree,
            CONCAT_WS(',', d.sequence, p.id) AS sequence
        FROM people p
        JOIN descendants d ON p.father_id = d.id
        WHERE p.deleted_at IS NULL AND d.degree < $countMax

        UNION ALL

        SELECT
            p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo, p.dob, p.yob,
            d.degree + 1 AS degree,
            CONCAT_WS(',', d.sequence, p.id) AS sequence
        FROM people p
        JOIN descendants d ON p.mother_id = d.id
        WHERE p.deleted_at IS NULL AND d.degree < $countMax
        )
        SELECT * FROM descendants
        ORDER BY degree, dob IS NULL, dob, yob IS NULL, yob;
        ";
    }
}
