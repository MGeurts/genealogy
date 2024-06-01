<?php

namespace App;

use App\Models\Person;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PersonPhotos
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    // -----------------------------------------------------------------------
    // save all photos and create avatars
    // -----------------------------------------------------------------------
    public static function save(?Person $person = null, $photos = []): void
    {
        if ($person and $photos) {
            // if needed, create folders
            if (! storage::disk('photos')->exists($person->team_id)) {
                Storage::disk('photos')->makeDirectory($person->team_id);
            }

            if (! storage::disk('avatars')->exists($person->team_id)) {
                Storage::disk('avatars')->makeDirectory($person->team_id);
            }

            // set image parameters
            $image_width   = config('app.image_upload_max_width');
            $image_height  = config('app.image_upload_max_height');
            $image_quality = config('app.image_upload_quality');
            $image_type    = config('app.image_upload_type');

            // set image manager
            $manager = new ImageManager(new Driver());

            // determine last index
            $files      = File::glob(public_path() . '/storage/photos/' . $person->team_id . '/' . $person->id . '_*.webp');
            $last_index = $files ? intval(substr(last($files), strpos(last($files), '_') + 1, strrpos(last($files), '_') - strpos(last($files), '_') - 1)) : 0;

            foreach ($photos as $current_photo) {
                // image name
                $next_index = str_pad(++$last_index, 3, '0', STR_PAD_LEFT);
                $image_name = $person->id . '_' . $next_index . '_' . now()->format('YmdHis') . '.' . $image_type;

                // image: resize, add watermark and save
                $manager->read($current_photo)
                    ->scaleDown(width: $image_width, height: $image_height)
                    ->place(public_path('img/watermark.png'), 'bottom-left', 5, 5)
                    ->toWebp(quality: $image_quality)
                    ->save(storage_path('app/public/photos/' . $person->team_id . '/' . $image_name));

                // avatar: resize and save
                $manager->read($current_photo)
                    ->scaleDown(width: 80, height: 80)
                    ->toWebp(quality: $image_quality)
                    ->save(storage_path('app/public/avatars/' . $person->team_id . '/' . $image_name));

                // update person: photo
                if (! isset($person->photo)) {
                    $person->update(['photo' => $image_name]);
                }
            }
        }
    }
    // -----------------------------------------------------------------------
}
