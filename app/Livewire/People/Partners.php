<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Couple;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Partners extends Component
{
    use Interactions;

    // ------------------------------------------------------------------------------
    public $person;

    // ------------------------------------------------------------------------------
    public ?int $couple_to_delete_id = null;

    public ?string $couple_to_delete_name = null;

    public bool $deleteConfirmed = false;

    // ------------------------------------------------------------------------------
    protected $listeners = [
        'couple_deleted' => 'render',
    ];

    // ------------------------------------------------------------------------------
    public function confirmDeletion(int $id, string $name): void
    {
        $this->deleteConfirmed = true;

        $this->couple_to_delete_id   = $id;
        $this->couple_to_delete_name = $name;
    }

    public function deleteCouple(): void
    {
        Couple::findOrFail($this->couple_to_delete_id)->delete();

        $this->deleteConfirmed = false;

        $this->toast()->success(__('app.delete'), $this->couple_to_delete_name . ' ' . __('app.deleted') . '.')->flash()->send();

        $this->redirect('/people/' . $this->person->id);
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.partners');
    }
}
