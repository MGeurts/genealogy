# Photo Migration Command Documentation

## Overview

This Laravel console command migrates photos from an old folder structure to a new unified structure. The command reorganizes photo files from three separate folders (`photos`, `photos-096`, `photos-384`) into a single unified `photos` folder with a new directory structure. The command includes comprehensive safety features including automatic backups, run-once protection, and support for all common image formats.

## Command Usage

```bash
php artisan photos:migrate [--dry-run]
```

### Options

-   `--dry-run` : Only show actions without moving, deleting files, or creating backups

## Migration Process

### From (Old Structure)

```
storage/app/public/
├── photos/          (original size)
    └── {teamId}/
        └── {personId}_{index}_{timestamp}.{ext}
├── photos-096/      (small size)
    └── {teamId}/
        └── {personId}_{index}_{timestamp}.{ext}
└── photos-384/      (medium size)
    └── {teamId}/
        └── {personId}_{index}_{timestamp}.{ext}
```

### To (New Structure)

```
storage/app/public/photos/
└── {teamId}/
    └── {personId}/
        ├── {personId}_{index}_{timestamp}.{ext}         (original size)
        ├── {personId}_{index}_{timestamp}_large.{ext}   (large size)
        ├── {personId}_{index}_{timestamp}_medium.{ext}  (medium size)
        └── {personId}_{index}_{timestamp}_small.{ext}   (small size)
```

### Backup Structure (Created Automatically)

```
storage/app/public/photo-backups/
└── {YYYY-MM-DD_HH-mm-ss}/
    ├── photos/          (complete backup of original)
    ├── photos-096/      (complete backup of small)
    └── photos-384/      (complete backup of medium)
```

## Supported File Formats

The command supports all common image file formats:

-   **BMP** - Bitmap images
-   **GIF** - Graphics Interchange Format
-   **JPG/JPEG** - Standard JPEG images
-   **PNG** - Portable Network Graphics
-   **SVG** - Scalable Vector Graphics
-   **TIFF** - Tagged Image File Format
-   **WebP** - Modern web image format

## Key Operations

1. **Creates timestamped backups** of all existing photo folders before migration begins
2. **Checks for previous migration** - prevents running if `photos-096` and `photos-384` folders don't exist
3. **Scans each old folder** and processes files within the `teamId/filename` structure
4. **Validates file formats** - only processes actual image files, skips other file types
5. **Extracts metadata** from the file path:
    - Team ID from the first directory level
    - Person ID from the filename (everything before the first underscore)
6. **Renames files** by adding size suffixes (`_large`, `_medium`, `_small`) for non-original images while preserving file extensions
7. **Creates the new directory structure** (`photos/{teamId}/{personId}/`)
8. **Copies files** to their new locations
9. **Deletes original files** after successful copy
10. **Cleans up empty folders** (except the main `photos` folder)

## Safety Features

-   **Automatic backups** - Creates timestamped backups before any changes are made
-   **Run-once protection** - Prevents accidental re-execution by checking if old folder structure exists
-   **Dry-run mode** (`--dry-run` flag) shows what would happen without actually moving files or creating backups
-   **Format validation** - Only processes actual image files, ignores non-image files
-   **Skips system files** - Automatically skips `.gitignore` files during processing
-   **Error handling** - Graceful handling of unexpected file structures
-   **Directory creation** - Ensures target folders exist before copying
-   **Clear status reporting** - Detailed output showing progress and results

## Example Migrations

### JPG Files

**Before:**

```
storage/app/public/photos-096/123/456_001_20250816113838.jpg
```

**After:**

```
storage/app/public/photos/123/456/456_001_20250816113838_small.jpg
```

### PNG Files

**Before:**

```
storage/app/public/photos-384/456/789_002_20250816113838.png
```

**After:**

```
storage/app/public/photos/456/789/789_002_20250816113838_medium.png
```

### WebP Files

**Before:**

```
storage/app/public/photos/123/456_003_20250816113838.webp
```

**After:**

```
storage/app/public/photos/123/456/456_003_20250816113838.webp
```

## Command Output Examples

### Normal Execution

```bash
$ php artisan photos:migrate

Starting photo migration...
📦 Created backup: photos → photo-backups/2025-08-17_14-30-25/photos
📦 Created backup: photos-096 → photo-backups/2025-08-17_14-30-25/photos-096
📦 Created backup: photos-384 → photo-backups/2025-08-17_14-30-25/photos-384
✅ Backup completed. Files saved to: photo-backups/2025-08-17_14-30-25/

Scanning photos for original photos...
✔ /path/to/photos/1/560_001_20250816113838.jpg → /path/to/photos/1/560/560_001_20250816113838.jpg

Scanning photos-096 for small photos...
✔ /path/to/photos-096/1/560_001_20250816113838.jpg → /path/to/photos/1/560/560_001_20250816113838_small.jpg
🧹 Deleted folder: /path/to/photos-096

Scanning photos-384 for medium photos...
✔ /path/to/photos-384/1/560_001_20250816113838.jpg → /path/to/photos/1/560/560_001_20250816113838_medium.jpg
🧹 Deleted folder: /path/to/photos-384

✅ Photo migration completed successfully.
```

### Dry-Run Mode

```bash
$ php artisan photos:migrate --dry-run

Starting photo migration (DRY RUN)...
🔍 DRY RUN MODE: No files will be moved or deleted. Showing what would happen:

[DRY] Would create backup: /path/to/photos → /path/to/photo-backups/2025-08-17_14-30-25/photos
[DRY] Would create backup: /path/to/photos-096 → /path/to/photo-backups/2025-08-17_14-30-25/photos-096
[DRY] Would create backup: /path/to/photos-384 → /path/to/photo-backups/2025-08-17_14-30-25/photos-384
[DRY] ✅ Backup completed. Files saved to: photo-backups/2025-08-17_14-30-25/

Scanning photos for original photos...
[DRY] /path/to/photos/1/560_001_20250816113838.jpg → /path/to/photos/1/560/560_001_20250816113838.jpg

[DRY] Would delete folder and contents: /path/to/photos-096
[DRY] Would delete folder and contents: /path/to/photos-384

✅ Photo migration DRY RUN completed successfully.
```

### Already Migrated

```bash
$ php artisan photos:migrate

Starting photo migration...
❌ Migration has already been completed!
The old folder structure (photos-096 and photos-384) no longer exists.
This command can only be run once. If you need to re-run it, restore the old folder structure first.
```

## Recovery Process

If you need to restore the original folder structure after migration:

1. **Locate your backup** in the `photo-backups` directory
2. **Remove the migrated photos folder** to avoid conflicts:
    ```bash
    rm -rf photos
    ```
3. **Restore folders from backup** to their original locations:
    ```bash
    cp -r photo-backups/2025-08-17_14-30-25/photos ./
    cp -r photo-backups/2025-08-17_14-30-25/photos-096 ./
    cp -r photo-backups/2025-08-17_14-30-25/photos-384 ./
    ```
4. **Verify restoration** - Check that all three original folders are back with their content

## Purpose

This migration is part of refactoring a photo storage system to better organize files by team and person while maintaining different image sizes in a more structured way. The new structure supports all common image formats and makes it easier to:

-   **Locate all photos for a specific person** - All sizes are grouped under one person directory
-   **Manage different image sizes consistently** - Clear naming convention with size suffixes
-   **Maintain better organization by team and individual** - Hierarchical structure by team → person
-   **Support multiple image formats** - Works with BMP, GIF, JPG, PNG, SVG, TIFF, WebP
-   **Simplify backup and maintenance operations** - Cleaner directory structure
-   **Improve performance** - Better organization for file system operations
-   **Enable easier migration to other storage solutions** - Standardized structure

## Important Notes

-   ⚠️ **This command is designed to run only once** - It includes built-in protection against re-execution
-   📦 **Always creates backups** - Your original files are safely backed up before any changes
-   🔍 **Test first** - Use `--dry-run` to preview changes before executing
-   🔒 **File format validation** - Only processes actual image files, other files are ignored
-   📁 **Preserves permissions** - Directory permissions are maintained during migration
