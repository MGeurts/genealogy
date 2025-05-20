# README-UPLOADS.md

## 📁 File & Image Upload Configuration

This application uses predefined settings for image and file uploads. These settings are defined in `/config/app.php`. Below is a guide to help you understand how uploads are handled and what types of files are accepted.

---

### 📸 Image Uploads

#### Storage Folders

Uploaded photos by default are saved in the following folders:

-   `photos`: Original image
-   `photos-096`: Small version (typically 96 pixels wide)
-   `photos-384`: Medium version (typically 384 pixels wide)

These versions allow the app to serve optimized images depending on the context (e.g., thumbnails, previews).

```php
'photo_folders' => [
    'photos',
    'photos-096',
    'photos-384',
],
```

These folders should not be modified as they are used everywhere in the application.

---

#### Upload Settings

By default, uploaded images are processed using these settings:

```php
'upload_photo' => [
    'max_width'     => 600,
    'max_height'    => 800,
    'quality'       => 80,
    'type'          => 'webp',
    'add_watermark' => true,
],
```

-   **max_width / max_height**: Images are resized to fit within these dimensions while maintaining aspect ratio.
-   **quality**: Compression quality (0–100). Higher = better quality but larger file size.
-   **type**: All images are converted to `.webp` format for optimal web performance.
-   **add_watermark**: Adds a watermark automatically to uploaded images.

These values can be modified according to your preferences.

---

#### Accepted Image Formats

By defaylt, only these image types are allowed for upload:

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
These values can be modified according to your preferences.

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
These values can be modified according to your preferences.

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
-   Resize images if they exceed the maximum dimensions.
-   If watermarking fails or images look distorted, verify the source image's quality.
