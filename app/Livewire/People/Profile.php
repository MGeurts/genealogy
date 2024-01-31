<?php

namespace App\Livewire\People;

use Illuminate\Support\Facades\File;
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
            // delete photos
            File::delete(File::glob(storage_path('app/public/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp')));

            $this->person->delete();

            toast()->success($this->person->name . '<br/>' . __('app.deleted') . '.', __('app.delete'))->doNotSanitize()->pushOnNextPage();

            $this->redirect('/search');
        }
    }

    public function render()
    {
        return view('livewire.people.profile');
    }
}
