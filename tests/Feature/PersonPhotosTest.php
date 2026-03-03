<?php

declare(strict_types=1);

use App\Contracts\PersonPhotoServiceInterface;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('can upload photos', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();
    /** @var PersonPhotoServiceInterface $photoService */
    $photoService = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');

    $result = $photoService->save($person, [$file]);

    expect($result)->toBe(1);
    expect($person->fresh()->photo)->not->toBeNull();
});

it('clears photo attribute when deleting the last photo', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();
    /** @var PersonPhotoServiceInterface $photoService */
    $photoService = app(PersonPhotoServiceInterface::class);

    $file = UploadedFile::fake()->image('photo.jpg');
    $photoService->save($person, [$file]);

    expect($person->fresh()->photo)->not->toBeNull();

    $photoService->delete($person, $person->fresh()->photo);

    expect($person->fresh()->photo)->toBeNull();
});

it('selects a new primary when deleting the current primary photo', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();
    /** @var PersonPhotoServiceInterface $photoService */
    $photoService = app(PersonPhotoServiceInterface::class);

    $files = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
    ];

    $photoService->save($person, $files);

    $firstPhoto = $person->fresh()->photo;
    expect($firstPhoto)->not->toBeNull();

    $photoService->delete($person, $firstPhoto); // Delete first photo

    $newPrimary = $person->fresh()->photo;

    expect($newPrimary)->not->toBeNull();
    expect($newPrimary)->not->toBe($firstPhoto);
});
