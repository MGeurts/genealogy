<?php

namespace App\Livewire\People;

use Livewire\Component;

class Profile extends Component
{
    public $person;

    public $deleteConfirmed = false;

    protected $listeners = [
        'person_updated' => 'render',
        'couple_deleted' => 'render',
    ];

    public function confirmDeletion()
    {
        $this->deleteConfirmed = true;
    }

    public function deletePerson()
    {
        if ($this->person->isDeletable()) {
            toast()->success($this->person->name . '<br/>' . __('app.deleted') . '.', __('app.delete'))->doNotSanitize()->pushOnNextPage();

            $this->person->delete();

            $this->redirect('/search');
        }
    }

    public function render()
    {
        return view('livewire.people.profile');
    }
}
