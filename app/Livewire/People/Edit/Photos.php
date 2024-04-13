<?php

namespace App\Livewire\People\Edit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Photos extends Component
{
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $person;

    public $photos = [];

    public $backup = [];

    public $images = [];

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        // if needed, create team photo folder
        $path = storage_path('app/public/photos/' . $this->person->team_id);

        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $images_person = Finder::create()->in(public_path('storage/photos/' . $this->person->team_id))->name($this->person->id . '_*.webp');

        $this->images = collect($images_person)->map(fn (SplFileInfo $file) => [
            'name' => $file->getFilename(),
            'name_download' => $this->person->name . ' - ' . $file->getFilename(),
            'extension' => $file->getExtension(),
            'size' => $this->size_as_kb($file->getSize()),
            'path' => $file->getPath(),
            'url' => Storage::url('photos/' . $this->person->team_id . '/' . $file->getFilename()),
        ]);
    }

    public function deleteUpload(array $content): void
    {
        /*
        the $content contains:
        [
            'temporary_name',
            'real_name',
            'extension',
            'size',
            'path',
            'url',
        ]
        */

        if (! $this->photos) {
            return;
        }

        $files = Arr::wrap($this->photos);

        /** @var UploadedFile $file */
        $file = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() === $content['temporary_name'])->first();

        // here we delete the file.
        // even if we have a error here, we simply ignore it because as long as the file is not persisted, it is temporary and will be deleted at some point if there is a failure here
        rescue(fn () => $file->delete(), report: false);

        $collect = collect($files)->filter(fn (UploadedFile $item) => $item->getFilename() !== $content['temporary_name']);

        // we guarantee restore of remaining files regardless of upload type, whether you are dealing with multiple or single uploads
        $this->photos = is_array($this->photos) ? $collect->toArray() : $collect->first();
    }

    public function updatingPhotos(): void
    {
        // we store the uploaded files in the temporary property
        $this->backup = $this->photos;
    }

    public function updatedPhotos(): void
    {
        if (! $this->photos) {
            return;
        }

        // we merge the newly uploaded files with the saved ones
        $file = Arr::flatten(array_merge($this->backup, [$this->photos]));

        // we finishing by removing the duplicates
        $this->photos = collect($file)->unique(fn (UploadedFile $item) => $item->getClientOriginalName())->toArray();
    }

    public function savePhotos()
    {
        // determine last index
        $files = File::glob(public_path() . '/storage/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp');
        $last_index = $files ? intval(substr(last($files), strpos(last($files), '_') + 1, strrpos(last($files), '_') - strpos(last($files), '_') - 1)) : 0;

        // set image parameters
        $image_width = intval(env('IMAGE_UPLOAD_MAX_WIDTH', 600));
        $image_height = intval(env('IMAGE_UPLOAD_MAX_HEIGHT', 800));
        $image_quality = intval(env('IMAGE_UPLOAD_QUALITY', 80));
        $image_type = env('IMAGE_UPLOAD_TYPE', 'webp');

        // set image manager
        $manager = new ImageManager(new Driver());

        foreach ($this->photos as $current_photo) {
            // name
            $next_index = str_pad(++$last_index, 3, '0', STR_PAD_LEFT);
            $image_name = $this->person->id . '_' . $next_index . '_' . now()->format('YmdHis') . '.' . $image_type;

            // resize, add watermark
            $new_image = $manager->read($current_photo)
                ->scaleDown(width: $image_width, height: $image_height)
                ->place(public_path('img/watermark.png'), 'bottom-left', 5, 5)
                ->toWebp(quality: $image_quality);

            // save
            if ($new_image) {
                $new_image->save(storage_path('app/public/photos/' . $this->person->team_id . '/' . $image_name));

                if (! isset($this->person->photo)) {
                    $this->person->update(['photo' => $image_name]);
                }
            }
        }

        return $this->redirect('/people/' . $this->person->id . '/edit-photos');
    }

    public function deletePhoto($photo)
    {
        Storage::disk('photos')->delete($this->person->team_id . '/' . $photo);

        // set new primary
        if ($photo == $this->person->photo) {
            $files = File::glob(public_path() . '/storage/photos/' . $this->person->team_id . '/' . $this->person->id . '_*.webp');

            $this->person->update([
                'photo' => $files ? substr($files[0], strrpos($files[0], '/') + 1) : null,
            ]);
        }

        return $this->redirect('/people/' . $this->person->id . '/edit-photos');
    }

    public function setPrimary($photo)
    {
        $this->person->update([
            'photo' => $photo,
        ]);

        return $this->redirect('/people/' . $this->person->id . '/edit-photos');
    }

    public function render()
    {
        return view('livewire.people.edit.photos');
    }

    // -----------------------------------------------------------------------
    private function size_as_kb($size): string
    {
        if ($size < 1024) {
            return $size . ' bytes';
        } elseif ($size < 1048576) {
            return round($size / 1024) . ' KB';
        } else {
            return round($size / 1048576, 1) . ' MB';
        }
    }
    // -----------------------------------------------------------------------
}
