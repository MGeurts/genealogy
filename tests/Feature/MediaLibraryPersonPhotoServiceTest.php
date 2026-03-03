<?php

declare(strict_types=1);

use App\Contracts\PersonPhotoServiceInterface;
use App\Models\Person;
use App\Services\Photos\MediaLibraryPersonPhotoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('app.photo_driver', 'medialibrary');
    app()->forgetInstance(PersonPhotoServiceInterface::class);
});

it('resolves the medialibrary photo service when configured', function () {
    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    expect($service)->toBeInstanceOf(MediaLibraryPersonPhotoService::class);
});

it('can save photos through the medialibrary service', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $files = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
    ];

    $count = $service->save($person, $files);

    expect($count)->toBe(2);
    expect($person->fresh()->photo)->not->toBeNull();
    expect($person->getMedia('photos'))->toHaveCount(2);
});

it('sets primary photo on first upload', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');

    $service->save($person, [$file]);

    $person = $person->fresh();

    expect($person->photo)->not->toBeNull();
    $media = $person->getMedia('photos')->first();
    expect($media)->not->toBeNull();
    expect($person->photo)->toBe((string) $media->id);
});

it('can retrieve all photos', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $files = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
    ];

    $service->save($person, $files);

    $all = $service->getAllPhotos($person);

    expect($all)->toHaveCount(2);
    expect($all[0])->toHaveKeys([
        'id',
        'name',
        'extension',
        'is_primary',
        'url_original',
        'url_large',
        'url_medium',
        'url_small',
    ]);
});

it('can get primary photo url', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');

    $service->save($person, [$file]);

    $url = $service->getPrimaryPhotoUrl($person->fresh(), 'medium');

    expect($url)->not->toBeNull();
});

it('can delete a specific photo', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $files = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
    ];

    $service->save($person, $files);

    $firstMedia = $person->fresh()->getMedia('photos')->first();
    expect($firstMedia)->not->toBeNull();

    $deleted = $service->delete($person, (string) $firstMedia->id);

    expect($deleted)->toBeTrue();
    expect($person->fresh()->getMedia('photos'))->toHaveCount(1);
});

it('can delete all photos', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $files = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
    ];

    $service->save($person, $files);

    $deleted = $service->deleteAll($person);

    expect($deleted)->toBeTrue();
    expect($person->fresh()->getMedia('photos'))->toHaveCount(0);
    expect($person->fresh()->photo)->toBeNull();
});

it('sets new primary when deleting current primary', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $files = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
    ];

    $service->save($person, $files);

    $person       = $person->fresh();
    $firstPrimary = $person->photo;

    $deleted = $service->delete($person, (string) $firstPrimary);

    expect($deleted)->toBeTrue();

    $person = $person->fresh();

    expect($person->photo)->not->toBeNull();
    expect($person->photo)->not->toBe($firstPrimary);
});

it('clears photo attribute when deleting the last photo', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');

    $service->save($person, [$file]);

    $person  = $person->fresh();
    $primary = $person->photo;
    expect($primary)->not->toBeNull();

    $service->delete($person, (string) $primary);

    $person = $person->fresh();

    expect($person->photo)->toBeNull();
    expect($person->getMedia('photos'))->toHaveCount(0);
});

it('returns gallery images with all variants', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');

    $service->save($person, [$file]);

    $images = $service->getGalleryImages($person->fresh());

    expect($images)->toHaveCount(1);
    expect($images[0])->toHaveKeys([
        'id',
        'filename',
        'small',
        'medium',
        'large',
        'original',
    ]);
});

it('returns export data for GEDCOM', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');

    $service->save($person, [$file]);

    $data = $service->getOriginalPhotosForExport($person->fresh());

    expect($data)->toHaveCount(1);
    expect($data[0])->toHaveKeys([
        'filename',
        'file_reference',
        'mime_type',
        'disk_path',
        'url',
    ]);
});

it('cleans up on person delete', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');

    $service->save($person, [$file]);

    $personId = $person->id;
    $teamId   = $person->team_id;

    $person->forceDelete();

    expect(Person::withTrashed()->find($personId))->toBeNull();
    expect(Storage::disk('photos')->allFiles())->toBeEmpty();
});

it('handles team directory operations gracefully', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');

    $service->save($person, [$file]);

    $service->ensureTeamDirectoryExists($person->team_id);
    $service->deleteTeamDirectory($person->team_id);

    expect($person->fresh()->getMedia('photos'))->toHaveCount(0);
});

it('can get photo url from raw attributes', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');

    $service->save($person, [$file]);

    $media = $person->fresh()->getMedia('photos')->first();
    expect($media)->not->toBeNull();

    $url = $service->getPhotoUrlFromAttributes(
        $person->team_id,
        $person->id,
        (string) $media->id,
        'small',
    );

    expect($url)->not->toBeNull();
});
