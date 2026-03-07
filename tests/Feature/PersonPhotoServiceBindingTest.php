<?php

declare(strict_types=1);

use App\Contracts\PersonPhotoServiceInterface;
use App\Services\Photos\CustomPersonPhotoService;
use App\Services\Photos\MediaLibraryPersonPhotoService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('resolves the custom photo service by default', function () {
    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    expect($service)->toBeInstanceOf(CustomPersonPhotoService::class);
});

it('resolves the medialibrary photo service when configured', function () {
    config()->set('app.photo_driver', 'medialibrary');

    app()->forgetInstance(PersonPhotoServiceInterface::class);

    /** @var PersonPhotoServiceInterface $service */
    $service = app(PersonPhotoServiceInterface::class);

    expect($service)->toBeInstanceOf(MediaLibraryPersonPhotoService::class);
});

it('throws for unsupported photo driver', function () {
    config()->set('app.photo_driver', 'unsupported-driver');

    app()->forgetInstance(PersonPhotoServiceInterface::class);

    $resolve = fn () => app(PersonPhotoServiceInterface::class);

    expect($resolve)->toThrow(RuntimeException::class);
});
