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
            $this->deletePersonPhotos();

            $this->person->delete();

            $this->toast()->success(__('app.delete'), $this->person->name . ' ' . __('app.deleted') . '.')->flash()->send();

            $this->redirect('/search');
        }
    }

    private function deletePersonPhotos(): void
    {
        $directories = ['photos', 'photos-096', 'photos-384'];

        foreach ($directories as $directory) {
            File::delete(File::glob(storage_path("app/public/{$directory}/" . $this->person->team_id . '/' . $this->person->id . '_*.webp')));
        }
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.profile');
    }
}
