<?php

declare(strict_types=1);

use App\Models\Person;
use App\PersonPhotos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('can upload photos', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();
    $photos = new PersonPhotos($person);

    $file = UploadedFile::fake()->image('photo.jpg');

    $result = $photos->save([$file]);

    expect($result)->toBe(1);
    expect($person->fresh()->photo)->not->toBeNull();
});

it('clears photo attribute when deleting the last photo', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();
    $photos = new PersonPhotos($person);

    $file = UploadedFile::fake()->image('photo.jpg');
    $photos->save([$file]);

    expect($person->fresh()->photo)->not->toBeNull();

    $photos->delete(1);

    expect($person->fresh()->photo)->toBeNull();
});

it('selects a new primary when deleting the current primary photo', function () {
    Storage::fake('photos');

    $person = Person::factory()->create();
    $photos = new PersonPhotos($person);

    $files = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
    ];

    $photos->save($files);

    $firstPhoto = $person->fresh()->photo;
    expect($firstPhoto)->not->toBeNull();

    $photos->delete(1); // Delete first photo

    $newPrimary = $person->fresh()->photo;

    expect($newPrimary)->not->toBeNull();
    expect($newPrimary)->not->toBe($firstPhoto);
});
