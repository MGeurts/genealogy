<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Person;

/**
 * Defines the contract for all person photo handling implementations.
 *
 * Implementations encapsulate how photos are stored, retrieved, and cleaned up
 * so the rest of the application can remain agnostic of the underlying driver
 * (custom filesystem or media library). Callers should rely on this interface
 * for all person photo operations instead of talking to storage directly.
 */
interface PersonPhotoServiceInterface
{
    /**
     * Save multiple photos for the given person.
     *
     * @param  array<int, mixed>  $photos
     */
    public function save(Person $person, array $photos): ?int;

    /**
     * Get all photos with metadata for the given person.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAllPhotos(Person $person): array;

    public function getPhotoUrl(Person $person, string $photoId, string $variant = 'medium'): ?string;

    public function getPrimaryPhotoUrl(Person $person, string $variant = 'medium'): ?string;

    public function delete(Person $person, string $photoId): bool;

    public function deleteAll(Person $person): bool;

    public function photoExists(Person $person, string $photoId): bool;

    public function setPrimary(Person $person, string $photoId): void;

    public function setNewPrimaryPhoto(Person $person): void;

    /**
     * Get gallery images for the given person, including URLs for all variants.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getGalleryImages(Person $person): array;

    /**
     * Get original photo metadata for GEDCOM export.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getOriginalPhotosForExport(Person $person): array;

    public function getAbsolutePath(string $diskPath): string;

    public function cleanupOnDelete(Person $person): void;

    public function ensureTeamDirectoryExists(int $teamId): void;

    public function deleteTeamDirectory(int $teamId): void;

    /**
     * Get filenames for medium-sized images of the given person.
     *
     * @return array<int, string>
     */
    public function getMediumImageFilenames(Person $person): array;

    /**
     * Helper for tree-node Blade templates where only raw attributes are available.
     */
    public function getPhotoUrlFromAttributes(
        int $teamId,
        int $personId,
        string $photoIdentifier,
        string $variant = 'small',
    ): ?string;
}
