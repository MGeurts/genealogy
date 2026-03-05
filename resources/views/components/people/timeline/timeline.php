<?php

declare(strict_types=1);

use App\Models\Person;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Person $person;

    /** @var Collection<int, array<string, mixed>> */
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

    };
