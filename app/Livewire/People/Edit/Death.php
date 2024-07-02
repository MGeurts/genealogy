<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\DeathForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Carbon\Carbon;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Death extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    public DeathForm $deathForm;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->deathForm->person = $this->person;

        $this->deathForm->yod = $this->person->yod;
        $this->deathForm->dod = $this->person->dod ? Carbon::parse($this->person->dod)->format('Y-m-d') : null;
        $this->deathForm->pod = $this->person->pod;

        $this->deathForm->cemetery_location_name      = $this->person->getMetadataValue('cemetery_location_name');
        $this->deathForm->cemetery_location_address   = $this->person->getMetadataValue('cemetery_location_address');
        $this->deathForm->cemetery_location_latitude  = $this->person->getMetadataValue('cemetery_location_latitude');
        $this->deathForm->cemetery_location_longitude = $this->person->getMetadataValue('cemetery_location_longitude');
    }

    public function saveDeath(): void
    {
        if ($this->isDirty()) {
            $validated = $this->deathForm->validate();

            $this->person->update([
                'yod' => $this->deathForm->yod ?? null,
                'dod' => $this->deathForm->dod ?? null,
                'pod' => $this->deathForm->pod ?? null,
            ]);
            // ------------------------------------------------------
            // update or create metadata
            // ------------------------------------------------------
            $this->person->updateMetadata(
                collect($validated)
                    ->forget(['yod', 'dod', 'pod'])
                    ->filter(function ($value, $key) {
                        return $value != $this->person->getMetadataValue($key);
                    })
            );
            // ------------------------------------------------------
            $this->dispatch('person_updated');

            $this->toast()->success(__('app.save'), __('app.saved'))->send();
        }
    }

    public function resetDeath(): void
    {
        $this->mount();
    }

    public function isDirty(): bool
    {
        return
        $this->deathForm->yod != $this->person->yod or
        $this->deathForm->dod != ($this->person->dod ? Carbon::parse($this->person->dod)->format('Y-m-d') : null) or
        $this->deathForm->pod != $this->person->pod or

        $this->deathForm->cemetery_location_name != $this->person->getMetadataValue('cemetery_location_name') or
        $this->deathForm->cemetery_location_address != $this->person->getMetadataValue('cemetery_location_address') or
        $this->deathForm->cemetery_location_latitude != $this->person->getMetadataValue('cemetery_location_latitude') or
        $this->deathForm->cemetery_location_longitude != $this->person->getMetadataValue('cemetery_location_longitude');
    }
}
