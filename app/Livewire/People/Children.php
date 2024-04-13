<?php

namespace App\Livewire\People;

use App\Models\Person;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Children extends Component
{
    use Interactions;

    // ------------------------------------------------------------------------------
    public $person;

    public $child_to_disconnect_id;

    public $child_to_disconnect_name;

    public $disconnectConfirmed = false;

    // ------------------------------------------------------------------------------
    public function confirmDisconnect(int $id, string $name): void
    {
        $this->disconnectConfirmed = true;

        $this->child_to_disconnect_id = $id;
        $this->child_to_disconnect_name = $name;
    }

    public function disconnectChild()
    {
        $child = Person::findOrFail($this->child_to_disconnect_id);

        if ($this->person->sex == 'm') {
            $child->update([
                'father_id' => null,
            ]);
        } else {
            $child->update([
                'mother_id' => null,
            ]);
        }

        $this->toast()->success(__('app.disconnect'), $child->name . ' ' . __('app.disconnected') . '.')->flash()->send();

        return $this->redirect('/people/' . $this->person->id);
    }

    // ------------------------------------------------------------------------------
    public function render()
    {
        $children = $this->person->childrenNaturalAll();

        return view('livewire.people.children')->with(compact('children'));
    }
}
