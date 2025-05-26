<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Livewire\Forms\People\DeathForm;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Death extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    public DeathForm $form;

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();
    }

    public function saveDeath(): void
    {
        $validated = $this->form->validate();

        $this->person->update([
            'yod' => $this->form->yod ?? null,
            'dod' => $this->form->dod ?? null,
            'pod' => $this->form->pod ?? null,
        ]);
        // ------------------------------------------------------
        // update or create metadata
        // ------------------------------------------------------
        $this->person->updateMetadata(
            collect($validated)
                ->forget(['yod', 'dod', 'pod'])
                ->filter(fn ($value, $key): bool => $value !== $this->person->getMetadataValue($key))
        );
        // ------------------------------------------------------
        $this->dispatch('person_updated');

        $this->toast()->success(__('app.save'), __('app.saved'))->send();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.death');
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->form->yod                         = $this->person->yod;
        $this->form->dod                         = $this->person->dod ? Carbon::parse($this->person->dod)->format('Y-m-d') : null;
        $this->form->pod                         = $this->person->pod;
        $this->form->cemetery_location_name      = $this->person->getMetadataValue('cemetery_location_name');
        $this->form->cemetery_location_address   = $this->person->getMetadataValue('cemetery_location_address');
        $this->form->cemetery_location_latitude  = $this->person->getMetadataValue('cemetery_location_latitude');
        $this->form->cemetery_location_longitude = $this->person->getMetadataValue('cemetery_location_longitude');
    }
}
