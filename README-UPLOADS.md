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

- **Before 4.5.0**: Photos were stored in separate folders (`photos/`, `photos-096/`, `photos-384/`) with a flat `teamId/filename` structure
- **From 4.5.0**: Photos are stored in a unified `photos/` folder with a nested `teamId/personId/` structure

**Migration features:**

- ✅ Automatic backup creation before migration
- ✅ Support for all image formats (not just WebP)
- ✅ Run-once protection to prevent accidental re-execution
- ✅ Dry-run mode to preview changes: `php artisan photos:migrate --dry-run`

**This migration is mandatory and safe** - your original photos will be backed up automatically before any changes are made.

📖 **For complete migration documentation, examples, and recovery procedures, see [README-PHOTO-MIGRATION.md](README-PHOTO-MIGRATION.md)**

</div>

---

## 📸 Image Uploads

### Storage Folder

Uploaded photos are saved in the `storage/app/public/photos/` folder using the filename template:

`teamId/personId/personId_sequence_timestamp[_size].extension`

**Example:**

```
storage/app/public/photos/1/552/552_001_1723709988.jpg          (original, untouched)
storage/app/public/photos/1/552/552_001_1723709988_large.webp   (large size, 1920px wide by default)
storage/app/public/photos/1/552/552_001_1723709988_medium.webp  (medium size, 384px wide by default)
storage/app/public/photos/1/552/552_001_1723709988_small.webp   (small size, 96px wide by default)
```

These versions allow the application to serve optimized image sizes depending on the context (e.g., thumbnails, previews, full image).

---

### Upload Settings

Uploaded images are processed as follows:

1. **Original File**: Always stored untouched in its original format (needed for GEDCOM export)
2. **Resized Versions**: Three WebP versions are created (large, medium, small) for optimal web performance
3. **Security Validation**: All uploads undergo multiple security checks before being saved

**Configuration in `/config/app.php`:**

```php
'upload_photo' => [
    'max_width'     => 1920,
    'max_height'    => 1080,
    'add_watermark' => env('PHOTOS_ADD_WATERMARK', false),
    'sizes'         => [
        'large' => [
            'width'   => 1920,
            'height'  => 1080,
            'quality' => 90,  // 90 is sweet spot for WebP
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

- **max_width / max_height**: Images are resized to fit within these dimensions while maintaining aspect ratio.
- **add_watermark**: Adds a watermark automatically to uploaded images (set in `.env` file).
- **sizes**: You can customize the dimensions but the size names (large, medium, small) **MUST STAY** untouched as they are hardcoded in the application.
- **quality**: Compression quality (0–100). Higher = better quality but larger file size.

---

### Accepted Image Formats

Only the following image types are allowed for upload:

```php
'upload_photo_accept' => [
    'image/bmp'  => 'BMP',
    'image/gif'  => 'GIF',
    'image/jpeg' => 'JPEG',
    'image/png'  => 'PNG',
    'image/webp' => 'WEBP',
],
```

**Note:** SVG and TIFF formats have been removed for security reasons. SVG files can contain executable JavaScript, and TIFF files can be extremely large.

These values can be modified in `/config/app.php`.

---

### Image Validation Settings

**Configuration in `/config/app.php`:**

```php
'upload_photo_validation' => [
    // File extensions allowed
    'extensions' => ['bmp', 'gif', 'jpeg', 'jpg', 'png', 'webp'],

    // MIME types for Laravel validation
    'mimes_rule' => 'bmp,gif,jpeg,jpg,png,webp',

    // Image types validated by getimagesize()
    'image_types' => [
        IMAGETYPE_BMP,
        IMAGETYPE_GIF,
        IMAGETYPE_JPEG,
        IMAGETYPE_PNG,
        IMAGETYPE_WEBP,
    ],

    // Dimension constraints
    'dimensions' => [
        'min_width'  => 100,
        'min_height' => 100,
        'max_width'  => 8000,
        'max_height' => 8000,
    ],
],
```

---

### 🔒 Image Security Measures

The application implements multiple layers of security to prevent malicious image uploads:

#### 1. **Laravel Validation**

- File type validation using `image` and `mimes` rules
- File size validation
- Image dimension validation

#### 2. **Server-Side Image Verification**

- MIME type verification from actual file content (not just extension)
- Image structure validation using `getimagesize()`
- Image type constant verification (IMAGETYPE\_\*)
- Extension whitelist enforcement

#### 3. **Image Re-encoding**

- All images are re-encoded to WebP format using Intervention Image
- This strips any potentially malicious code embedded in images
- Original files are preserved separately for GEDCOM export

#### 4. **Server Protection**

- Photos are stored in `storage/app/public/` (not directly in `public/`)
- Access is controlled through Laravel's storage system
- For Apache servers, an `.htaccess` file in the photos directory blocks PHP execution

**Recommended `.htaccess` for `storage/app/public/photos/`:**

```apache
# Deny access to any scripts
<FilesMatch "\.(php.*|phtml|phar|pl|py|cgi|sh|exe|bat)$">
    Require all denied
</FilesMatch>

# Only allow image files
<FilesMatch "\.(bmp|gif|jpe?g|png|webp)$">
    Require all granted
</FilesMatch>

# Deny everything else by default
Require all denied
```

**For nginx servers**, add to your configuration:

```nginx
location ~* ^/storage/photos/.*\.(php.*|phtml|phar|pl|py|cgi|sh)$ {
    deny all;
}
```

---

## 📄 File Uploads (Documents)

### Storage Folder

Uploaded files are saved in the `storage/app/files/` folder managed by Spatie Media Library.

---

### Accepted File Types

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
These values can be modified in `/config/app.php`.

---

### File Validation Settings

**Configuration in `/config/app.php`:**

```php
'upload_file_validation' => [
    // File extensions allowed
    'extensions' => ['txt', 'pdf', 'odt', 'doc', 'docx', 'xls', 'xlsx'],

    // MIME types for Laravel validation
    'mimes_rule' => 'txt,pdf,odt,doc,docx,xls,xlsx',
],
```

---

### 🔒 Document Security Measures

The application implements comprehensive security measures to prevent malicious document uploads:

#### 1. **Laravel Validation**

- File type validation using `file` and `mimes` rules
- File size validation
- Extension whitelist enforcement

#### 2. **Server-Side File Verification**

- MIME type verification from actual file content (not just extension)
- Extension whitelist enforcement
- Dangerous extension blocking (php, exe, sh, bat, etc.)
- File signature verification (magic bytes)

#### 3. **Content-Specific Validation**

**PDF Files:**

- Validates PDF header (`%PDF-`)
- Scans for potentially dangerous JavaScript or actions
- Logs warnings for suspicious content

**Office Documents (DOCX, XLSX, ODT):**

- Validates ZIP structure (these formats are ZIP-based)
- Scans archive contents for hidden executables
- Blocks documents containing .exe, .dll, .sh, .bat, .php files

**Text Files:**

- Basic MIME type validation
- Extension verification

#### 4. **Server Protection**

- Files are stored in `storage/app/files/` (not directly in `public/`)
- Access is controlled through Laravel's storage system and Spatie Media Library
- For Apache servers, an `.htaccess` file in the files directory blocks script execution

**Recommended `.htaccess` for `storage/app/files/`:**

```apache
# Deny access to any scripts
<FilesMatch "\.(php.*|phtml|phar|pl|py|cgi|sh|exe|bat|cmd|com|scr|vbs|js|jar)$">
    Require all denied
</FilesMatch>

# Only allow document files
<FilesMatch "\.(txt|pdf|odt|doc|docx|xls|xlsx)$">
    Require all granted
</FilesMatch>

# Deny everything else by default
Require all denied
```

**For nginx servers**, add to your configuration:

```nginx
location ~* ^/storage/files/.*\.(php.*|phtml|phar|pl|py|cgi|sh|exe|bat|cmd|com|scr|vbs|js|jar)$ {
    deny all;
}
```

---

### File Signature Verification (Magic Bytes)

The application verifies file signatures to ensure files match their claimed type:

| File Type     | Magic Bytes (Hex)                      | Description           |
| ------------- | -------------------------------------- | --------------------- |
| PDF           | `25504446`                             | `%PDF`                |
| DOC/XLS       | `d0cf11e0a1b11ae1`                     | OLE format            |
| DOCX/XLSX/ODT | `504b0304` or `504b0506` or `504b0708` | ZIP format            |
| TXT           | (none)                                 | No specific signature |

This prevents attackers from simply renaming malicious files (e.g., `virus.exe` → `document.pdf`).

---

## Upload Size Limit

```php
'upload_max_size' => 10240  // 10 MB in kilobytes
```

The maximum file size for uploads is **10 MB (10,240 KB)**.

**Important:** Ensure your server's PHP configuration supports this limit by checking your `php.ini` file:

- `upload_max_filesize = 10M` (or higher)
- `post_max_size = 10M` (or higher)
- `memory_limit = 128M` (recommended for image processing)

The `upload_max_size` value in `/config/app.php` should match or be lower than your PHP settings.

---

## ⚙️ Troubleshooting

### Upload Issues

- **File Rejected**: Ensure the file format matches the allowed MIME types
- **File Too Large**: Check that your file is under 10 MB and your `php.ini` settings allow it
- **Invalid Image**: The file may be corrupted or contain invalid data
- **Security Validation Failed**: The file failed security checks and was rejected
- **File Signature Mismatch**: The file's actual type doesn't match its extension

### Document-Specific Issues

- **PDF Rejected**: May contain JavaScript or other active content (logged as warning)
- **Office Document Rejected**: May contain hidden executables in the ZIP archive
- **Suspicious Content Detected**: Check application logs for details

### Version Upgrade Issues

- **For upgrades from pre-4.5.0**: Make sure you've run `php artisan photos:migrate` **ONCE** before using the application
- **Missing Photos**: Check that the symbolic link exists: `php artisan storage:link`
- **Permission Errors**: Ensure `storage/app/public/photos/` and `storage/app/files/` are writable by your web server

### Image Processing Issues

- **Watermarking Fails**: Verify that `public/img/watermark.png` exists if watermarking is enabled
- **Poor Quality**: Adjust the `quality` settings in the `upload_photo.sizes` configuration
- **Slow Processing**: Large images take time to process; consider reducing `max_width` and `max_height`

### Checking Your Configuration

Run these commands to verify your setup:

```bash
# Check PHP upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Verify storage link exists
ls -la public/storage

# Check photos directory permissions
ls -la storage/app/public/photos/

# Check files directory permissions
ls -la storage/app/files/

# View recent upload errors
tail -f storage/logs/laravel.log | grep -i upload
```

---

## 🔍 Security Best Practices

### For Administrators

1. **Regularly review logs** for suspicious upload attempts:

    ```bash
    grep -i "Invalid\|Dangerous\|Suspicious" storage/logs/laravel.log
    ```

2. **Keep upload limits reasonable** - Don't increase beyond what's necessary

3. **Verify `.htaccess` files** are in place:
    - `storage/app/public/photos/.htaccess`
    - `storage/app/files/.htaccess`

4. **Monitor storage usage** - Malicious users might attempt to fill disk space

5. **Keep Laravel and dependencies updated** for latest security patches

### For Users

1. **Only upload files from trusted sources**
2. **Scan files with antivirus** before uploading
3. **Don't rename executables** to bypass validation - they will be detected
4. **Report suspicious behavior** if uploads fail unexpectedly

---

## 📝 Summary

### Image Uploads

- ✅ **Original files preserved** in uploaded format
- ✅ **Three WebP versions** created for web performance
- ✅ **Four-layer security validation** (Laravel, server-side, re-encoding, server config)
- ✅ **Magic bytes verification** prevents file type spoofing
- ✅ **Image structure validation** using getimagesize()

### Document Uploads

- ✅ **Multiple MIME type support** (TXT, PDF, DOC, DOCX, XLS, XLSX, ODT)
- ✅ **Five-layer security validation** (Laravel, MIME check, extension check, signature verification, content scanning)
- ✅ **PDF content scanning** for JavaScript and dangerous actions
- ✅ **ZIP archive inspection** for DOCX/XLSX/ODT documents
- ✅ **Comprehensive logging** of all security events

### General Security

- ✅ **Centralized configuration** in `/config/app.php`
- ✅ **Server-level protection** via `.htaccess` or nginx config
- ✅ **Dangerous file blocking** (executables, scripts, etc.)
- ✅ **User feedback** for invalid uploads

For questions or issues, please consult the application logs at `storage/logs/laravel.log`.

---

## 📚 Additional Resources

- Laravel File Uploads: https://laravel.com/docs/11.x/filesystem
- Spatie Media Library: https://spatie.be/docs/laravel-medialibrary
- Intervention Image: https://image.intervention.io/
- File Signatures Database: https://en.wikipedia.org/wiki/List_of_file_signatures
