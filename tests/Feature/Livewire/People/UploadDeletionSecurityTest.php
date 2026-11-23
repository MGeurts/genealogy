<?php

declare(strict_types=1);

use App\Models\Person;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create(['user_id' => $this->user->id]);
    $this->user->currentTeam()->associate($this->team);
    $this->user->save();

    $this->person = Person::factory()->create(['team_id' => $this->team->id]);

    $this->actingAs($this->user);
    Storage::fake('tmp-for-tests');
});

function createTemporaryUpload(): TemporaryUploadedFile
{
    $filename = Str::uuid() . '.upload';

    Storage::disk('tmp-for-tests')->put('livewire-tmp/' . $filename, 'temporary upload');

    return new TemporaryUploadedFile($filename, 'tmp-for-tests');
}

function assertUploadDeletionStaysInTemporaryStorage(object $component, callable $uploads): void
{
    $upload     = createTemporaryUpload();
    $markerName = 'upload-delete-marker-' . Str::uuid();
    $markerPath = storage_path('app/' . $markerName);

    File::put($markerPath, 'must not be deleted');

    try {
        $uploads($component, [$upload]);

        $component->deleteUpload(['temporary_name' => '../' . $markerName]);

        expect(File::exists($markerPath))->toBeTrue();

        $component->deleteUpload(['temporary_name' => $upload->getFilename()]);

        expect(Storage::disk('tmp-for-tests')->missing('livewire-tmp/' . $upload->getFilename()))->toBeTrue();
    } finally {
        File::delete($markerPath);
    }
}

test('edit files deletes only a tracked temporary upload', function (): void {
    $component = Livewire::test('people::edit.files', ['person' => $this->person])->instance();

    assertUploadDeletionStaysInTemporaryStorage(
        $component,
        function (object $component, array $uploads): void {
            $component->uploads = $uploads;
        },
    );
});

test('edit photos deletes only a tracked temporary upload', function (): void {
    $component = Livewire::test('people::edit.photos', ['person' => $this->person])->instance();

    assertUploadDeletionStaysInTemporaryStorage(
        $component,
        function (object $component, array $uploads): void {
            $component->uploads = $uploads;
        },
    );
});

test('person creation deletes only a tracked temporary upload', function (): void {
    $component = Livewire::test('people::add.person')->instance();

    assertUploadDeletionStaysInTemporaryStorage(
        $component,
        function (object $component, array $uploads): void {
            $component->form->uploads = $uploads;
        },
    );
});
