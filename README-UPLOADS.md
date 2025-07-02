# README-UPLOADS.md

## 📁 File & Image Upload Configuration

This application uses predefined settings for **image** and **file uploads**. These settings are defined in `/config/app.php`.<br/>
Below is a guide to help you understand how uploads are handled and what types of files or images are accepted.

---

### 📸 Image Uploads

#### Storage Folders

Uploaded photos by default are saved in the following folders:

-   `photos`: Original image
-   `photos-096`: Small version (typically 96 pixels wide)
-   `photos-384`: Medium version (typically 384 pixels wide)

These versions allow the application to serve optimized images depending on the context (e.g., thumbnails, previews).

```php
'photo_folders' => [
    'photos',
    'photos-096',
    'photos-384',
],
```

These folders should **not be modified** as they are used everywhere in the application.

---

#### Upload Settings

By default, uploaded images are processed using these settings:

```php
'upload_photo' => [
    'type'          => 'webp',
    'max_width'     => 1920,
    'max_height'    => 1080,
    'quality'       => 85,
    'add_watermark' => env('PHOTOS_ADD_WATERMARK', false),
],
```

-   **type**: All images are converted to `.webp` format for optimal web performance.
-   **max_width / max_height**: Images are resized to fit within these dimensions while maintaining aspect ratio.
-   **quality**: Compression quality (0–100). Higher = better quality but larger file size.
-   **add_watermark**: Adds a watermark automatically to uploaded images.

These values can be modified according to your preferences in `/config/app.php`.
Only the `add_Watermark` setting is imported from your `.env`.

---

#### Accepted Image Formats

By default, only the following image types are allowed for upload:

```php
'upload_photo_accept' => [
    'image/gif'     => 'GIF',
    'image/jpeg'    => 'JPEG',
    'image/png'     => 'PNG',
    'image/svg+xml' => 'SVG',
    'image/webp'    => 'WEBP',
],
```

Make sure your image format matches one of these types.
These values can be modified according to your preferences in `/config/app.php`.

---

### 📄 File Uploads

#### Accepted File Types

By default, the following document types are accepted for upload:

```php
'upload_file_accept' => [
    'text/plain'                                                              => 'TXT',
    'application/pdf'                                                         => 'PDF',
    'application/vnd.oasis.opendocument.text'                                 => 'ODT',
    'application/msword'                                                      => 'DOC',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'DOCX',
    'application/vnd.ms-excel'                                                => 'XLS',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'       => 'XLSX',
],
```

Uploads that don’t match these MIME types will be rejected.
These values can be modified according to your preferences in `/config/app.php`.

---

#### Upload Size Limit

```php
'upload_max_size' => 10240
```

The maximum file size for uploads is **10 MB (10,240 KB)**.

Ensure your server’s PHP configuration supports this limit by checking:

-   `upload_max_filesize`
-   `post_max_size`

The limit above should be changed to match the values in your `php.ini` file.

---

### ⚙️ Troubleshooting

-   Ensure the file format and size match the allowed settings.
-   If watermarking fails or images look distorted, verify the source image's quality.
