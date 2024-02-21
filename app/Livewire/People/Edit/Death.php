<?php

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\DeathForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Death extends Component
{
    use TrimStringsAndConvertEmptyStringsToNull;
    use WireToast;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    public DeathForm $deathForm;

    // -----------------------------------------------------------------------
    public function mount()
    {
        $this->deathForm->person = $this->person;

        $this->deathForm->yod = $this->person->yod;
        $this->deathForm->dod = $this->person->dod?->format('Y-m-d');
        $this->deathForm->pod = $this->person->pod;

        $this->deathForm->cemetery_location_name = $this->person->getMetadataValue('cemetery_location_name');
        $this->deathForm->cemetery_location_address = $this->person->getMetadataValue('cemetery_location_address');
        $this->deathForm->cemetery_location_latitude = $this->person->getMetadataValue('cemetery_location_latitude');
        $this->deathForm->cemetery_location_longitude = $this->person->getMetadataValue('cemetery_location_longitude');
    }

    public function saveDeath()
    {
        if ($this->isDirty()) {
            $validated = $this->deathForm->validate();

            $this->person->update([
                'yod' => $this->deathForm->yod ? $this->deathForm->yod : null,
                'dod' => $this->deathForm->dod ? $this->deathForm->dod : null,
                'pod' => $this->deathForm->pod ? $this->deathForm->pod : null,
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
            toast()->success(__('app.saved') . '.', __('app.save'))->push();
        }
    }

    public function resetDeath()
    {
        $this->mount();
    }

    public function isDirty()
    {
        return
        $this->deathForm->yod != $this->person->yod or
        $this->deathForm->dod != ($this->person->dod ? $this->person->dod->format('Y-m-d') : null) or
        $this->deathForm->pod != $this->person->pod or

        $this->deathForm->cemetery_location_name != $this->person->getMetadataValue('cemetery_location_name') or
        $this->deathForm->cemetery_location_address != $this->person->getMetadataValue('cemetery_location_address') or
        $this->deathForm->cemetery_location_latitude != $this->person->getMetadataValue('cemetery_location_latitude') or
        $this->deathForm->cemetery_location_longitude != $this->person->getMetadataValue('cemetery_location_longitude');
    }
}
