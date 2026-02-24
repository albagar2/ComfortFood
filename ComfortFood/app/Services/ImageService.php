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
    public function processAndStore(UploadedFile $file, string $directory, ?int $unused_width = null, ?int $unused_height = null, int $unused_quality = 80, ?string $customName = null): string
    {
        // Generate a clean name (custom or random)
        $extension = $file->getClientOriginalExtension() ?: 'png';
        $filename = ($customName ?: Str::random(40)) . '.' . $extension;

        // Store the file using Laravel's native storage
        $path = $file->storeAs($directory, $filename, 'public');

        return $path;
    }
    /**
     * Delete an image from storage.
     *
     * @param string|null $path The relative path to the image.
     */
    public function delete(?string $path): void
    {
        // Don't delete if empty
        if (!$path) {
            return;
        }

        // If it's a full URL, extract the path component
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $path = parse_url($path, PHP_URL_PATH);
        }

        // Normalize path: Remove /storage/ or storage/ prefix if present
        // ltrim prevents issues with leading slashes from parse_url
        $normalizedPath = preg_replace('/^\/?storage\//', '', ltrim($path, '/'));

        if (Storage::disk('public')->exists($normalizedPath)) {
            Storage::disk('public')->delete($normalizedPath);
        }
    }
}
