<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Person;
use App\PersonPhotos;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PersonPhotosTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_photos(): void
    {
        Storage::fake('photos');

        $person = Person::factory()->create();
        $photos = new PersonPhotos($person);

        $file = UploadedFile::fake()->image('photo.jpg');

        $result = $photos->save([$file]);

        $this->assertEquals(1, $result);
        $this->assertNotNull($person->fresh()->photo);
    }

    public function test_deleting_last_photo_clears_attribute(): void
    {
        Storage::fake('photos');

        $person = Person::factory()->create();
        $photos = new PersonPhotos($person);

        $file = UploadedFile::fake()->image('photo.jpg');
        $photos->save([$file]);

        $this->assertNotNull($person->fresh()->photo);

        $photos->delete(1);

        $this->assertNull($person->fresh()->photo);
    }

    public function test_deleting_primary_selects_new_primary(): void
    {
        Storage::fake('photos');

        $person = Person::factory()->create();
        $photos = new PersonPhotos($person);

        $files = [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg'),
        ];

        $photos->save($files);

        $firstPhoto = $person->fresh()->photo;
        $this->assertNotNull($firstPhoto);

        $photos->delete(1); // Delete first photo

        $newPrimary = $person->fresh()->photo;
        $this->assertNotNull($newPrimary);
        $this->assertNotEquals($firstPhoto, $newPrimary);
    }
}
