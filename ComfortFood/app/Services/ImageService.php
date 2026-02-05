<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Process and store an uploaded image using Laravel's native storage.
     * Note: Since we use client-side compression, the image is already optimized.
     *
     * @param UploadedFile $file The uploaded file.
     * @param string $directory The target directory in public disk.
     * @param int|null $unused_width Not needed as client-side handles this.
     * @param int|null $unused_height Not needed as client-side handles this.
     * @param int $unused_quality Not needed as client-side handles this.
     * @return string The public URL of the stored image.
     */
    public function processAndStore(UploadedFile $file, string $directory, ?int $unused_width = null, ?int $unused_height = null, int $unused_quality = 80): string
    {
        // Generate a clean, unique name
        $extension = $file->getClientOriginalExtension() ?: 'png';
        $filename = Str::random(40) . '.' . $extension;

        // Store the file using Laravel's native storage (No GD required)
        $path = $file->storeAs($directory, $filename, 'public');

        // Return the relative path
        return $path;
    }
    /**
     * Delete an image from storage.
     *
     * @param string|null $path The relative path to the image.
     */
    public function delete(?string $path): void
    {
        // Don't delete if it's a URL (placeholder), empty, or null
        if (!$path || filter_var($path, FILTER_VALIDATE_URL)) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
