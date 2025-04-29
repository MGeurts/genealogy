<?php

declare(strict_types=1);

namespace App\Php\Gedcom;

use App\Models\Couple;
use App\Models\Person;
use Illuminate\Support\Facades\DB;

final readonly class Import
{
    public function __construct(string $name, string $description, string $file)
    {
        //
    }

    public function import(string $gedcom): void
    {
        $lines    = explode("\n", $gedcom);
        $people   = [];
        $families = [];
        $current  = null;
        $type     = null;

        foreach ($lines as $line) {
            $parts                = preg_split('/\s+/', mb_trim($line), 3);
            [$level, $tag, $data] = $parts + [null, null, null];

            if ($level === '0' && str_starts_with($tag, '@I')) {
                $current          = $tag;
                $type             = 'INDI';
                $people[$current] = [];
            } elseif ($level === '0' && str_starts_with($tag, '@F')) {
                $current            = $tag;
                $type               = 'FAM';
                $families[$current] = [];
            } elseif ($type === 'INDI') {
                $people[$current][$tag] = $data ?? '';
            } elseif ($type === 'FAM') {
                $families[$current][$tag][] = $data ?? '';
            }
        }

        DB::transaction(function () use ($people, $families): void {
            $idMap = [];

            foreach ($people as $xref => $data) {
                $person            = new Person();
                $name              = explode('/', $data['NAME'] ?? ' / ', 2);
                $person->firstname = mb_trim($name[0]);
                $person->surname   = mb_trim($name[1] ?? '');
                $person->sex       = mb_strtolower($data['SEX'] ?? 'm');
                $person->dob       = isset($data['DATE']) ? date('Y-m-d', strtotime($data['DATE'])) : null;
                $person->save();

                $idMap[$xref] = $person->id;
            }

            foreach ($families as $xref => $data) {
                $couple             = new Couple();
                $couple->person1_id = $idMap[$data['HUSB'][0] ?? ''] ?? null;
                $couple->person2_id = $idMap[$data['WIFE'][0] ?? ''] ?? null;

                if (! empty($data['MARR'][0])) {
                    $couple->date_start = date('Y-m-d', strtotime($data['MARR'][0]));
                    $couple->is_married = true;
                }

                $couple->save();

                foreach ($data['CHIL'] ?? [] as $childXref) {
                    if (isset($idMap[$childXref])) {
                        $child             = Person::find($idMap[$childXref]);
                        $child->parents_id = $couple->id;
                        $child->save();
                    }
                }
            }
        });
    }
}
