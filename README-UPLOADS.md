# README-UPLOADS.md

## 📁 File & Image Upload Configuration

This application uses predefined settings for **image** and **file uploads**. These settings are defined in `/config/app.php`.<br/>
Below is a guide to help you understand how uploads are handled and what types of files or images are accepted.

---

<div style="background-color: #ffebee; border: 2px solid #f44336; border-radius: 8px; padding: 16px; margin: 16px 0;">
<h2 style="color: #c62828; margin-top: 0;">⚠️ Important Migration Notice</h2>

**If you are upgrading from a version prior to 4.5.0**, the photo folder structure has been completely reorganized. Before using the application with version 4.5.0 or higher, you **MUST** run the photo migration command **ONCE**:

```bash
# First, preview what will happen (recommended)
php artisan photos:migrate --dry-run

# Then run the actual migration
php artisan photos:migrate
```

**What changed in version 4.5.0:**

-   **Before 4.5.0**: Photos were stored in separate folders (`photos/`, `photos-096/`, `photos-384/`) with a flat `teamId/filename` structure
-   **From 4.5.0**: Photos are stored in a unified `photos/` folder with nested `teamId/personId/` structure

**Migration features:**

-   ✅ Automatic backup creation before migration
-   ✅ Support for all image formats (not just WebP)
-   ✅ Run-once protection to prevent accidental re-execution
-   ✅ Dry-run mode to preview changes: `php artisan photos:migrate --dry-run`

**This migration is mandatory and safe** - your original photos will be backed up automatically before any changes are made.

📖 **For complete migration documentation, examples, and recovery procedures, see [README-PHOTO-MIGRATION.md](README-PHOTO-MIGRATION.md)**

</div>

---

### 📸 Image Uploads

#### Storage Folder

Uploaded photos are saved in the photos folder using the filename template:

`personId_sequence_timestamp[_size].webp`

    Example:
    552_001_20250815T073948.webp           (original)
    552_001_20250815T073948_medium.webp    (medium size, by default 384 pixels wide)
    552_001_20250815T073948_small.webp     (small size, by default 96 pixels wide)

These versions allow the application to serve optimized image sizes depending on the context (e.g., thumbnails, previews, full image).

---

#### Upload Settings

Uploaded images are **ALWAYS** stored untouched (needed for GEDCOM export), alongside 3 resized versions.
Uploaded images are also **ALWAYS** resized and converted to `.webp` format for optimal web performance inside the application and processed using these settings:

```php
    'upload_photo' => [
        'max_width'     => 1920,
        'max_height'    => 1080,
        'add_watermark' => env('PHOTOS_ADD_WATERMARK', false),
        'sizes'         => [
            'large' => [
                'width'   => 1920,
                'height'  => 1080,
                'quality' => 90,
            ],
            'medium' => [
                'width'   => 384,
                'height'  => null,
                'quality' => 85,
            ],
            'small' => [
                'width'   => 96,
                'height'  => null,
                'quality' => 80,
            ],
        ],
    ],
```

-   **max_width / max_height**: Images are resized to fit within these dimensions while maintaining aspect ratio.
-   **add_watermark**: Adds a watermark automatically to uploaded images.
-   **sizes**: You can customize the dimensions but the size names (large, medium, small) **MUST STAY** untouched as they are hardcoded in the application.
-   **quality**: Compression quality (0–100). Higher = better quality but larger file size.

These values can be modified according to your preferences in `/config/app.php`.
Only the `add_Watermark` setting is imported from your `.env`.

---

#### Accepted Image Formats

Only the following image types are allowed for upload:

```php
'upload_photo_accept' => [
    'image/bmp'     => 'BMP',
    'image/gif'     => 'GIF',
    'image/jpeg'    => 'JPEG',
    'image/png'     => 'PNG',
    'image/svg+xml' => 'SVG',
    'image/tiff'    => 'TIFF',
    'image/webp'    => 'WEBP',
],
```

Make sure your image format matches one of these types.
These values can be modified according to your preferences in `/config/app.php`.

---

### 📄 File Uploads

#### Accepted File Types

The following document types are accepted for upload:

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

Uploads that don't match these MIME types will be rejected.
These values can be modified according to your preferences in `/config/app.php`.

---

#### Upload Size Limit

```php
'upload_max_size' => 10240
```

The maximum file size for uploads is **10 MB (10,240 KB)**.

Ensure your server's PHP configuration supports this limit by checking:

-   `upload_max_filesize`
-   `post_max_size`

The limit above should be changed to match the values in your `php.ini` file.

---

### ⚙️ Troubleshooting

-   Ensure the file format and size match the allowed settings.
-   If watermarking fails or images look distorted, verify the source image's quality.
-   **For version upgrades from pre-4.5.0**: Make sure you've run the migration command **ONCE** before using the application.
