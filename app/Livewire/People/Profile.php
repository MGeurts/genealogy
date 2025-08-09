<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

final class Profile extends Component
{
    use Interactions;

    // -----------------------------------------------------------------------
    public Person $person;

    // -----------------------------------------------------------------------
    protected $listeners = [
        'person_updated' => 'render',
        'couple_deleted' => 'render',
    ];

    // -----------------------------------------------------------------------
    public function confirm(): void
    {
        $this->dialog()
            ->question(__('app.attention') . '!', __('app.are_you_sure'))
            ->confirm(__('app.delete_yes'))
            ->cancel(__('app.cancel'))
            ->hook([
                'ok' => [
                    'method' => 'delete',
                ],
            ])
            ->send();
    }

    public function delete(): void
    {
        if ($this->person->isDeletable()) {
            $this->deletePersonPhotos();

            $this->person->delete();

            $this->toast()->success(__('app.delete'), e($this->person->name) . ' ' . __('app.deleted') . '.')->flash()->send();

            $this->redirect('/search');
        }
    }

    // ------------------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.people.profile');
    }

    private function deletePersonPhotos(): void
    {
        defer(function (): void {
            foreach (config('app.photo_folders') as $folder) {
                File::delete(File::glob(storage_path("app/public/{$folder}/" . $this->person->team_id . '/' . $this->person->id . '_*.webp')));
            }
        });
    }
}
