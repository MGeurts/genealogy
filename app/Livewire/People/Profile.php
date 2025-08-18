<?php

declare(strict_types=1);

namespace App\Livewire\People;

use App\Models\Person;
use Illuminate\Support\Facades\Storage;
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
            $disk       = Storage::disk('photos');
            $personPath = $this->person->team_id . '/' . $this->person->id;

            // Check if the person's directory exists
            if (! $disk->exists($personPath)) {
                return;
            }

            // Get all files in the person's directory
            $files = $disk->files($personPath);

            // Filter to only image files belonging to this person
            $personFiles = collect($files)->filter(function ($file): bool {
                $filename = basename($file);
                $personId = $this->person->id;

                // Check if filename starts with personId_ and has valid image extension
                if (! str_starts_with($filename, $personId . '_')) {
                    return false;
                }

                // Get valid extensions from config
                $acceptedFormats = config('app.upload_photo_accept', []);
                $validExtensions = collect($acceptedFormats)->map(function ($label, $mimeType) {
                    // Convert MIME types to file extensions
                    return match ($mimeType) {
                        'image/bmp'     => 'bmp',
                        'image/gif'     => 'gif',
                        'image/jpeg'    => ['jpg', 'jpeg'],
                        'image/png'     => 'png',
                        'image/svg+xml' => 'svg',
                        'image/tiff'    => ['tiff', 'tif'],
                        'image/webp'    => 'webp',
                        default         => null,
                    };
                })->filter()->flatten()->toArray();

                $extension = mb_strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                return in_array($extension, $validExtensions);
            });

            // Delete the files
            if ($personFiles->isNotEmpty()) {
                $disk->delete($personFiles->toArray());
            }

            // Remove the person's directory if it's now empty
            $remainingFiles = $disk->files($personPath);
            if (empty($remainingFiles)) {
                $disk->deleteDirectory($personPath);
            }
        });
    }
}
