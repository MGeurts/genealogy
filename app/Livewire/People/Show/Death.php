<?php

namespace App\Livewire\People\Show;

use App\Livewire\Forms\People\DeathForm;
use App\Models\Person;
use Livewire\Component;

class Death extends Component
{
    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    public DeathForm $deathForm;

    // -----------------------------------------------------------------------
    public function mount()
    {
        $this->deathForm->yod = $this->person->yod;
        $this->deathForm->dod = $this->person->dod?->format('Y-m-d');
        $this->deathForm->pod = $this->person->pod;

        $this->deathForm->cemetery_location_name = $this->person->getMetadataValue('cemetery_location_name');
        $this->deathForm->cemetery_location_address = $this->person->getMetadataValue('cemetery_location_address');
        $this->deathForm->cemetery_location_latitude = $this->person->getMetadataValue('cemetery_location_latitude');
        $this->deathForm->cemetery_location_longitude = $this->person->getMetadataValue('cemetery_location_longitude');
    }
}
