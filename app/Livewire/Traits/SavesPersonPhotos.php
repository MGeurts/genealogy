<?php

declare(strict_types=1);

namespace App\Livewire\Traits;

use App\Models\Person;
use App\PersonPhotos;
use Exception;
use Illuminate\Support\Facades\Log;

trait SavesPersonPhotos
{
    /**
     * Save photos for a person and show appropriate toast messages.
     *
     * @param  Person  $person  The person to save photos for
     * @param  string|null  $context  Context for logging (e.g., 'child', 'father', 'partner')
     * @return int|null Number of photos saved, null if none
     */
    protected function savePersonPhotos(Person $person, ?string $context = null): ?int
    {
        if (empty($this->form->uploads)) {
            return null;
        }

        try {
            $photos     = new PersonPhotos($person);
            $savedCount = $photos->save($this->form->uploads);

            if ($savedCount > 0) {
                $this->toast()->success(__('app.save'), trans_choice('person.photos_saved', $savedCount))->send();

                return $savedCount;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Failed to save photos for person', [
                'person_id' => $person->id,
                'team_id'   => $person->team_id,
                'context'   => $context,
                'error'     => $e->getMessage(),
            ]);

            return null;
        }
    }
}
