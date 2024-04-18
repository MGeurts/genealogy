<?php

namespace App\Livewire\People;

use App\Models\Couple;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Partners extends Component
{
    use Interactions;

    // ------------------------------------------------------------------------------
    public $person;

    public $couple_to_delete_id;

    public $couple_to_delete_name;

    public $deleteConfirmed = false;

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

    public function deleteCouple()
    {
        Couple::findOrFail($this->couple_to_delete_id)->delete();

        $this->deleteConfirmed = false;

        $this->toast()->success(__('app.delete'), $this->couple_to_delete_name . ' ' . __('app.deleted') . '.')->flash()->send();

        return $this->redirect('/people/' . $this->person->id);
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.people.partners');
    }
}
