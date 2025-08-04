<?php

declare(strict_types=1);

namespace App\Queries;

use Illuminate\Support\Facades\DB;

class AncestorQuery
{
    public static function get(int $personId, int $countMax): string
    {
        return match (DB::getDriverName()) {
            'mysql' => self::getForMySQL($personId, $countMax),
            'pgsql' => self::getForPostgres($personId, $countMax),
        };
    }

    protected static function getForMySQL(int $personId, int $countMax): string
    {
        return "
        WITH RECURSIVE ancestors AS (
            SELECT
                id, firstname, surname, sex, father_id, mother_id, dod, yod, team_id, photo,
                0 AS degree,
                CAST(id AS CHAR(1024)) AS sequence
            FROM people
            WHERE deleted_at IS NULL AND id = $personId

            UNION ALL

            SELECT
                p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo,
                a.degree + 1 AS degree,
                CAST(CONCAT_WS(',', a.sequence, p.id) AS CHAR(1024)) AS sequence
            FROM people p
            JOIN ancestors a ON a.father_id = p.id
            WHERE p.deleted_at IS NULL AND a.degree < $countMax

            UNION ALL

            SELECT
                p.id, p.firstname, p.surname, p.sex, p.father_id, p.mother_id, p.dod, p.yod, p.team_id, p.photo,
                a.degree + 1 AS degree,
                CAST(CONCAT_WS(',', a.sequence, p.id) AS CHAR(1024)) AS sequence
            FROM people p
            JOIN ancestors a ON a.mother_id = p.id
            WHERE p.deleted_at IS NULL AND a.degree < $countMax
        )

        SELECT * FROM ancestors
        ORDER BY degree, sex DESC;
        ";
    }

    protected static function getForPostgres(int $personId, int $countMax): string
    {
        return "
        WITH RECURSIVE ancestors AS (
            -- Base case: starting person
            SELECT
                id,
                firstname,
                surname,
                sex,
                father_id,
                mother_id,
                dod,
                yod,
                team_id,
                photo,
                0 AS degree,
                id::TEXT AS sequence
        FROM people
        WHERE deleted_at IS NULL AND id = $personId
        
        UNION ALL
        
        -- Recursive case: walk up to parents (father OR mother)
        SELECT
            p.id,
            p.firstname,
            p.surname,
            p.sex,
            p.father_id,
            p.mother_id,
            p.dod,
            p.yod,
            p.team_id,
            p.photo,
            a.degree + 1 AS degree,
            a.sequence || ',' || p.id::TEXT AS sequence
        FROM people p
        JOIN ancestors a ON p.id = a.father_id OR p.id = a.mother_id
        WHERE p.deleted_at IS NULL AND a.degree < $countMax
        )
        
        SELECT *
        FROM ancestors
        ORDER BY degree, sex DESC;
        ";
    }
}
