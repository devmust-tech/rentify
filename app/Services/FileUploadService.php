<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Store a single uploaded file and return its storage path.
     */
    public function upload(UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        return $file->store($folder, $disk);
    }

    /**
     * Store multiple uploaded files and return an array of storage paths.
     *
     * @param  UploadedFile[]  $files
     */
    public function uploadMany(array $files, string $folder, string $disk = 'public'): array
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $this->upload($file, $folder, $disk);
        }
        return $paths;
    }

    /**
     * Delete a stored file by its path.
     */
    public function delete(string $path, string $disk = 'public'): bool
    {
        if (!$path) {
            return false;
        }
        return Storage::disk($disk)->delete($path);
    }

    /**
     * Delete multiple stored files by their paths.
     *
     * @param  string[]  $paths
     */
    public function deleteMany(array $paths, string $disk = 'public'): void
    {
        foreach ($paths as $path) {
            $this->delete($path, $disk);
        }
    }

    /**
     * Replace an existing file with a new upload.
     * Deletes the old file and stores the new one.
     */
    public function replace(UploadedFile $file, string $oldPath, string $folder, string $disk = 'public'): string
    {
        $this->delete($oldPath, $disk);
        return $this->upload($file, $folder, $disk);
    }

    /**
     * Get the full public URL for a stored file path.
     */
    public function url(string $path, string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($path);
    }
}
