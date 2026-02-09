<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

use App\Models\Person;
use App\PersonPhotos;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Trait for saving person photos with validation.
 *
 * @requires HandlesPhotoUploads This trait depends on HandlesPhotoUploads for isValidImageUpload() method
 */
trait SavesPersonPhotos
{
    /**
     * Save photos for a person and show appropriate toast messages.
     * Validates uploads before saving and provides user feedback.
     *
     * @param  Person  $person  The person to save photos for
     * @param  string|null  $context  Context for logging (e.g., 'child', 'father', 'mother', 'partner')
     * @return int|null Number of photos saved, null if none
     */
    protected function savePersonPhotos(Person $person, ?string $context = null): ?int
    {
        if (empty($this->form->uploads)) {
            return null;
        }

        // Filter out any invalid uploads before attempting to save
        $validUploads = collect($this->form->uploads)
            ->filter(fn ($upload) => $this->isValidImageUpload($upload))
            ->values()
            ->toArray();

        if (empty($validUploads)) {
            $this->toast()->warning(
                __('app.warning'),
                __('person.no_valid_photos_to_save')
            )->send();

            return null;
        }

        try {
            $photos     = new PersonPhotos($person);
            $savedCount = $photos->save($validUploads);

            if ($savedCount > 0) {
                $this->toast()->success(
                    __('app.save'),
                    trans_choice('person.photos_saved', $savedCount)
                )->send();

                // Show warning if some uploads were invalid
                $invalidCount = count($this->form->uploads) - count($validUploads);
                if ($invalidCount > 0) {
                    $this->toast()->warning(
                        __('app.warning'),
                        trans_choice('person.photos_invalid', $invalidCount)
                    )->send();
                }

                return $savedCount;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Failed to save photos for person', [
                'person_id' => $person->id,
                'team_id'   => $person->team_id,
                'context'   => $context,
                'uploads'   => count($this->form->uploads),
                'valid'     => count($validUploads),
                'error'     => $e->getMessage(),
            ]);

            $this->toast()->error(
                __('app.error'),
                __('person.photos_save_failed')
            )->send();

            return null;
        }
    }
}
