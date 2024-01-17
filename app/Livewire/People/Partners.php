<?php

namespace App\Livewire\People;

use App\Models\Couple;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Partners extends Component
{
    use WireToast;

    public $person;

    public $couple_to_delete_id;

    public $couple_to_delete_name;

    public $deleteConfirmed = false;

    protected $listeners = [
        'couple_deleted' => 'render',
    ];

    public function confirmDeletion(int $id, string $name)
    {
        $this->deleteConfirmed = true;

        $this->couple_to_delete_id = $id;
        $this->couple_to_delete_name = $name;
    }

    public function deleteCouple()
    {
        Couple::findOrFail($this->couple_to_delete_id)->delete();

        $this->deleteConfirmed = false;

        toast()->success($this->couple_to_delete_name . '<br/>' . __('app.deleted') . '.', __('app.delete'))->doNotSanitize()->pushOnNextPage();
        $this->redirect('/people/' . $this->person->id);
    }

    public function render()
    {
        return view('livewire.people.partners');
    }
}
