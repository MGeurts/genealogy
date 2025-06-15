<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Countries;
use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Contact extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    public $street = null;

    public $number = null;

    public $postal_code = null;

    public $city = null;

    public $province = null;

    public $state = null;

    public $country = null;

    public $phone = null;

    // -----------------------------------------------------------------------
    #[Computed(persist: true, seconds: 3600, cache: true)]
    public function countries(): Collection
    {
        return (new Countries(app()->getLocale()))->getAllCountries();
    }

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->loadData();
    }

    public function saveContact(): void
    {
        $validated = $this->validate();

        $this->person->update($validated);

        $this->dispatch('person_updated');

        $this->toast()->success(__('app.save'), __('app.saved'))->send();
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.edit.contact');
    }

    // -----------------------------------------------------------------------
    protected function rules(): array
    {
        return [
            'street'      => ['nullable', 'string', 'max:100'],
            'number'      => ['nullable', 'string', 'max:20'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'city'        => ['nullable', 'string', 'max:100'],
            'province'    => ['nullable', 'string', 'max:100'],
            'state'       => ['nullable', 'string', 'max:100'],
            'country'     => ['nullable', 'string', 'max:2'],
            'phone'       => ['nullable', 'string', 'max:50'],
        ];
    }

    protected function messages(): array
    {
        return [];
    }

    protected function validationAttributes(): array
    {
        return [
            'street'      => __('person.street'),
            'number'      => __('person.number'),
            'postal_code' => __('person.postal_code'),
            'city'        => __('person.city'),
            'province'    => __('person.province'),
            'state'       => __('person.state'),
            'country'     => __('person.country'),
            'phone'       => __('person.phone'),
        ];
    }

    // ------------------------------------------------------------------------------
    private function loadData(): void
    {
        $this->street      = $this->person->street;
        $this->number      = $this->person->number;
        $this->postal_code = $this->person->postal_code;
        $this->city        = $this->person->city;
        $this->province    = $this->person->province;
        $this->state       = $this->person->state;
        $this->country     = $this->person->country;
        $this->phone       = $this->person->phone;
    }
}
