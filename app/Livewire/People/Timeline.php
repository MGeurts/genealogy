<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

final class Timeline extends Component
{
    #[Locked]
    public Person $person;

    public Collection $timeline;

    public function mount(Person $person): void
    {
        $this->person = $person;

        $this->loadTimeline();
    }

    #[On('event-saved')]
    public function loadTimeline(): void
    {
        $this->timeline = $this->person->timeline();
    }

    public function render(): View
    {
        return view('livewire.people.timeline');
    }
}
