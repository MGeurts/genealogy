<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

final class Gallery extends Component
{
    use WithPagination;

    // ------------------------------------------------------------------------------
    public Person $person;

    // ------------------------------------------------------------------------------
    public array $images = [];

    public ?int $selected = null;

    // ------------------------------------------------------------------------------
    protected $listeners = [
        'photos_updated' => 'mount',
        'person_updated' => 'render',
    ];

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.gallery', [
            'photos' => $this->person->media()->oldest('order_column')->paginate(1),
        ]);
    }
}
