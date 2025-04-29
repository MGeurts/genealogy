<?php

declare(strict_types=1);

namespace App\Php\Gedcom;

use App\Models\Person;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

final class Export
{
    public readonly string $filename;

    private readonly string $basename;

    private readonly string $extension;

    public function __construct(
        string $basename,
        public readonly string $format = 'gedcom',
        public readonly string $encoding = 'utf8',
        public readonly string $line_endings = 'windows'
    ) {
        $this->basename = $basename;

        $this->extension = match ($this->format) {
            'gedcom'   => '.ged',
            'zip'      => '.zip',
            'zipmedia' => '.zip',
            'gedzip'   => '.gdz',
            default    => '.ged',
        };

        $this->filename = $this->basename . $this->extension;
    }

    public function export($individuals, $families): string
    {
        // Build GEDCOM content
        return $this->buildGedcom($individuals, $families);
    }

    public function downloadGedcom(string $gedcom): StreamedResponse
    {
        $encodedGedcom = $this->applyEncoding($gedcom);

        return Response::streamDownload(fn () => print ($encodedGedcom), $this->filename, [
            'Content-Type' => 'text/plain; charset=' . $this->encoding,
        ]);
    }

    public function downloadZip(string $gedcom): StreamedResponse
    {
        $tempDir = storage_path('app/temp');
        if (! file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $gedcomFile = $this->basename . '.ged';
        $gedcomPath = $tempDir . '/' . $gedcomFile;
        file_put_contents($gedcomPath, $gedcom);

        $zipPath = $tempDir . '/' . $this->basename . '.zip';
        $zip     = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);
        $zip->addFile($gedcomPath, $gedcomFile); // Include .ged in zip
        $zip->close();

        // Cleanup after download
        register_shutdown_function(function () use ($gedcomPath, $zipPath): void {
            @unlink($gedcomPath);
            @unlink($zipPath);
        });

        return response()->streamDownload(function () use ($zipPath): void {
            echo file_get_contents($zipPath);
        }, $this->filename);
    }

    // --------------------------------------------------------------------------------------
    private function buildGedcom($individuals, $families): string
    {
        // GEDCOM header - will fill SUBM later
        $gedcom = '';

        // First individual will be used as the submitter
        $submitter   = $individuals->first();
        $submitterId = $submitter ? "@I{$submitter->id}@" : '@SUB1@'; // fallback if no individuals

        $gedcom .= $this->buildHeader($submitterId);

        // Build a mapping of person_id => [family_ids where they are a spouse]
        $famsMapping = [];

        foreach ($families as $couple) {
            if ($couple->person1_id) {
                $famsMapping[$couple->person1_id][] = $couple->id;
            }
            if ($couple->person2_id) {
                $famsMapping[$couple->person2_id][] = $couple->id;
            }
        }

        // Individuals
        foreach ($individuals as $person) {
            $id = "@I{$person->id}@";
            $gedcom .= "0 $id INDI\n";
            $gedcom .= "1 NAME {$person->firstname} /{$person->surname}/\n";
            $gedcom .= '1 SEX ' . mb_strtoupper($person->sex) . "\n";

            if ($person->dob) {
                $gedcom .= "1 BIRT\n2 DATE " . mb_strtoupper($person->dob->format('j M Y')) . "\n";
            }
            if ($person->pob) {
                $gedcom .= "1 BIRT\n2 PLAC " . $person->pob . "\n";
            }
            if ($person->dod) {
                $gedcom .= "1 DEAT\n2 DATE " . mb_strtoupper($person->dod->format('j M Y')) . "\n";
            }
            if ($person->pod) {
                $gedcom .= "1 DEAT\n2 PLAC " . $person->pod . "\n";
            }

            // Child in family
            if ($person->parents_id) {
                $gedcom .= "1 FAMC @F{$person->parents_id}@\n";
            }

            // Spouse in family
            if (! empty($famsMapping[$person->id])) {
                foreach ($famsMapping[$person->id] as $familyId) {
                    $gedcom .= "1 FAMS @F{$familyId}@\n";
                }
            }
        }

        // Families
        foreach ($families as $couple) {
            $fid = "@F{$couple->id}@";
            $gedcom .= "0 $fid FAM\n";
            $gedcom .= "1 HUSB @I{$couple->person1_id}@\n";
            $gedcom .= "1 WIFE @I{$couple->person2_id}@\n";

            if ($couple->date_start) {
                $gedcom .= "1 MARR\n2 DATE " . mb_strtoupper($couple->date_start->format('j M Y')) . "\n";
            }

            $children = Person::where('parents_id', $couple->id)->get();
            foreach ($children as $child) {
                $gedcom .= "1 CHIL @I{$child->id}@\n";
            }
        }

        // GEDCOM footer
        $gedcom .= $this->buildFooter();

        return $gedcom;
    }

    private function applyEncoding(string $gedcom): string
    {
        return match ($this->encoding) {
            'utf8'    => $gedcom,
            'unicode' => mb_convert_encoding($gedcom, 'UTF-16BE', 'UTF-8'),
            'ansel'   => mb_convert_encoding($gedcom, 'ASCII', 'UTF-8'), // ANSEL approximation
            'ascii'   => mb_convert_encoding($gedcom, 'ASCII', 'UTF-8'),
            'ansi'    => mb_convert_encoding($gedcom, 'CP1252', 'UTF-8'),
            default   => $gedcom,
        };
    }

    private function buildHeader(string $submitterId): string
    {
        return implode($this->eol(), array: [
            '0 HEAD',
            '1 SOUR ' . config('app.name'),
            '2 VERS 1.0',
            '1 DEST ANY',
            '1 DATE ' . mb_strtoupper(now()->format('j M Y')),
            '2 TIME ' . now()->format('H:i:s'),
            "1 SUBM {$submitterId}", // <-- use dynamic submitter
            //            '1 FILE ' . $this->filename,
            '1 GEDC',
            '2 VERS 7.0',
            //            '2 FORM LINEAGE-LINKED',
            //            '1 CHAR ' . $this->encodingLabel(),
            '1 LANG ' . app()->getLocale(),
            '',
        ]);
    }

    private function buildFooter(): string
    {
        return implode($this->eol(), [
            '0 TRLR',
            '',
        ]);
    }

    private function encodingLabel(): string
    {
        return match ($this->encoding) {
            'utf8'    => 'UTF-8',
            'unicode' => 'UNICODE',
            'ansel'   => 'ANSEL',
            'ascii'   => 'ASCII',
            'ansi'    => 'ANSI',
            default   => mb_strtoupper($this->encoding),
        };
    }

    private function eol(): string
    {
        return $this->line_endings === 'windows' ? "\r\n" : "\n";
    }
    // --------------------------------------------------------------------------------------
}
