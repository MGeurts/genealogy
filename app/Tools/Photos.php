<?php

namespace App\Tools;

use App\Models\Person;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class Photos
{
    // -----------------------------------------------------------------------
    // save all photos and create avatars
    // -----------------------------------------------------------------------
    public static function save(Person $person, $photos)
    {
        if ($person and $photos) {
            // if needed, create folders
            if (! File::isDirectory(storage_path('app/public/photos/' . $person->team_id))) {
                File::makeDirectory(storage_path('app/public/photos/' . $person->team_id), 0777, true, true);
            }

            if (! File::isDirectory(storage_path('app/public/avatars/' . $person->team_id))) {
                File::makeDirectory(storage_path('app/public/avatars/' . $person->team_id), 0777, true, true);
            }

            // set image parameters
            $image_width   = config('app.image_upload_max_width');
            $image_height  = config('app.image_upload_max_height');
            $image_quality = config('app.image_upload_quality');
            $image_type    = config('app.image_upload_type');

            // set image manager
            $manager = new ImageManager(new Driver());

            $last_index = 0;

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

                // update person: photo
                if (! isset($person->photo)) {
                    $person->update(['photo' => $image_name]);
                }

                // avatar: resize and save
                $manager->read($current_photo)
                    ->scaleDown(width: 80, height: 80)
                    ->toWebp(quality: $image_quality)
                    ->save(storage_path('app/public/avatars/' . $person->team_id . '/' . $image_name));
            }
        }
    }
    // -----------------------------------------------------------------------
}
