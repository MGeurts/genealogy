<?php

declare(strict_types=1);

use App\Gedcom\Import\Import;
use App\Gedcom\Import\ZipImporter;
use App\Models\Team;
use App\Models\User;
use Exception;
use ZipArchive;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('a failed ZIP import does not create a team or change the current team', function (): void {
    $user = User::factory()->withPersonalTeam()->create();
    $this->actingAs($user);

    $zipPath = tempnam(sys_get_temp_dir(), 'invalid-gedcom-zip');

    if ($zipPath === false) {
        throw new Exception('Could not create the temporary ZIP file.');
    }

    file_put_contents($zipPath, 'not a zip archive');

    $teamCount = Team::count();
    $teamId    = $user->current_team_id;

    try {
        $result = (new Import('Broken import', null))->importFromZip($zipPath);

        expect($result['success'])->toBeFalse()
            ->and(Team::count())->toBe($teamCount)
            ->and($user->fresh()->current_team_id)->toBe($teamId);
    } finally {
        unlink($zipPath);
    }
});

test('ZIP extraction rejects archives with too many entries before writing files', function (): void {
    config()->set('app.gedcom_import.max_archive_entries', 1);

    $zipPath = tempnam(sys_get_temp_dir(), 'gedcom-archive-limit');

    if ($zipPath === false) {
        throw new Exception('Could not create the temporary ZIP file.');
    }

    $zip = new ZipArchive();
    $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addFromString('tree.ged', "0 HEAD\n0 TRLR\n");
    $zip->addFromString('photo.jpg', 'image');
    $zip->close();

    try {
        expect(fn () => (new ZipImporter())->extract($zipPath))
            ->toThrow(Exception::class, 'more than 1 entries');
    } finally {
        unlink($zipPath);
    }
});

test('ZIP extraction preserves GEDCOM and media bytes', function (): void {
    $zipPath = tempnam(sys_get_temp_dir(), 'gedcom-binary-content');

    if ($zipPath === false) {
        throw new Exception('Could not create the temporary ZIP file.');
    }

    $gedcomContent = "0 HEAD\n1 CHAR UTF-8\n1 NOTE 😀\n0 TRLR\n";
    $mediaContent  = "\x00\xFF\xD8\xFFbinary\x00data";

    $zip = new ZipArchive();

    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new Exception('Could not create the ZIP archive.');
    }

    $zip->addFromString('tree.ged', $gedcomContent);
    $zip->addFromString('portrait.jpg', $mediaContent);
    $zip->close();

    $zipImporter = new ZipImporter();

    try {
        $zipImporter->extract($zipPath);

        $mediaPath = $zipImporter->getMediaFiles()['portrait.jpg'];

        expect($zipImporter->getGedcomContent())->toBe($gedcomContent)
            ->and(file_get_contents($mediaPath))->toBe($mediaContent);
    } finally {
        $zipImporter->cleanup();
        unlink($zipPath);
    }
});
