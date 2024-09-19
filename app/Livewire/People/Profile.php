<?php

declare(strict_types=1);

namespace App\Livewire\People;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Profile extends Component
{
    use Interactions;

    // -----------------------------------------------------------------------
    public $person;

    // -----------------------------------------------------------------------
    public bool $deleteConfirmed = false;

    // -----------------------------------------------------------------------
    protected $listeners = [
        'person_updated' => 'render',
        'couple_deleted' => 'render',
    ];

    // -----------------------------------------------------------------------
    public function confirmDeletion(): void
    {
        $this->deleteConfirmed = true;
    }

    public function deletePerson()
    {
        if ($this->person->isDeletable()) {
            // delete all this person photos
            File::delete(File::glob(storage_path('app/public/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp')));
            File::delete(File::glob(storage_path('app/public/photos-096/' . $this->person->team_id . '/' . $this->person->id . '_*.webp')));
            File::delete(File::glob(storage_path('app/public/photos-384/' . $this->person->team_id . '/' . $this->person->id . '_*.webp')));

            $this->person->delete();

            $this->toast()->success(__('app.delete'), $this->person->name . ' ' . __('app.deleted') . '.')->flash()->send();

            return $this->redirect('/search');
        }
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.profile');
    }
}
