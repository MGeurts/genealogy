<?php

declare(strict_types=1);

namespace App\Livewire\People\Edit;

use App\Countries;
use App\Models\Person;
use App\Models\PersonEvent;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Events extends Component
{
    use Interactions;

    #[Locked]
    public Person $person;

    public bool $showModal = false;

    public ?int $editingEventId = null;

    public string $type = '';

    public ?string $description = null;

    public ?string $date = null;

    public ?int $year = null;

    public ?string $place = null;

    public ?string $street = null;

    public ?string $number = null;

    public ?string $postal_code = null;

    public ?string $city = null;

    public ?string $province = null;

    public ?string $state = null;

    public ?string $country = null;

    // -----------------------------------------------------------------------
    #[Computed(persist: true, seconds: 3600, cache: true)]
    public function countries(): Collection
    {
        return (new Countries(app()->getLocale()))->getAllCountries();
    }

    public function eventTypes(): Collection
    {
        return collect(PersonEvent::EVENT_TYPES)
            ->map(function ($type) {
                return [
                    'id'   => $type,
                    'name' => __('personevents.' . $type),
                ];
            })
            ->sortBy('name')
            ->values();
    }

    public function mount(Person $person): void
    {
        $this->person = $person;
    }

    public function openModal(?int $eventId = null): void
    {
        $this->resetValidation();

        if ($eventId) {
            $event = PersonEvent::findOrFail($eventId);

            $this->editingEventId = $event->id;
            $this->type           = $event->type;
            $this->description    = $event->description;
            $this->date           = $event->date?->format('Y-m-d');
            $this->year           = $event->year;
            $this->place          = $event->place;
            $this->street         = $event->street;
            $this->number         = $event->number;
            $this->postal_code    = $event->postal_code;
            $this->city           = $event->city;
            $this->province       = $event->province;
            $this->state          = $event->state;
            $this->country        = $event->country;
        } else {
            $this->resetForm();
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate([
            'type'        => 'required|in:' . implode(',', PersonEvent::EVENT_TYPES),
            'description' => 'nullable|string|max:1000',
            'date'        => 'nullable|date',
            'year'        => 'nullable|integer|min:1|max:' . (date('Y')),
            'place'       => 'nullable|string|max:255',
            'street'      => 'nullable|string|max:100',
            'number'      => 'nullable|string|max:20',
            'postal_code' => 'nullable|string|max:20',
            'city'        => 'nullable|string|max:100',
            'province'    => 'nullable|string|max:100',
            'state'       => 'nullable|string|max:100',
            'country'     => 'nullable|string|size:2',
        ]);

        $data = [
            'person_id'   => $this->person->id,
            'team_id'     => auth()->user()->currentTeam->id,
            'type'        => $this->type,
            'description' => $this->description,
            'date'        => $this->date,
            'year'        => $this->year,
            'place'       => $this->place,
            'street'      => $this->street,
            'number'      => $this->number,
            'postal_code' => $this->postal_code,
            'city'        => $this->city,
            'province'    => $this->province,
            'state'       => $this->state,
            'country'     => $this->country,
        ];

        if ($this->editingEventId) {
            PersonEvent::findOrFail($this->editingEventId)->update($data);
        } else {
            PersonEvent::create($data);
        }

        $this->closeModal();
        $this->dispatch('event-saved');
        $this->toast()->success(__('app.save'), __('personevents.event_saved'))->send();
    }

    public function confirm(string $id): void
    {
        $this->dialog()
            ->question(__('app.attention') . '!', __('app.are_you_sure'))
            ->confirm(__('app.delete_yes'))
            ->cancel(__('app.cancel'))
            ->hook([
                'ok' => [
                    'method' => 'delete',
                    'params' => $id,
                ],
            ])
            ->send();
    }

    public function delete(int $eventId): void
    {
        PersonEvent::findOrFail($eventId)->delete();

        $this->dispatch('event-saved');
        $this->toast()->success(__('app.delete'), __('personevents.event_deleted'))->send();
    }

    #[On('event-saved')]
    public function refreshEvents(): void
    {
        // This will refresh the component
    }

    public function render(): View
    {
        return view('livewire.people.edit.events', [
            'events' => $this->person->events()->orderBy('date', 'desc')->orderBy('year', 'desc')->get(),
        ]);
    }

    private function resetForm(): void
    {
        $this->editingEventId = null;
        $this->type           = '';
        $this->description    = null;
        $this->date           = null;
        $this->year           = null;
        $this->place          = null;
        $this->street         = null;
        $this->number         = null;
        $this->postal_code    = null;
        $this->city           = null;
        $this->province       = null;
        $this->state          = null;
        $this->country        = null;
    }
}
