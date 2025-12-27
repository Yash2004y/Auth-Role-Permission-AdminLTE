<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('uploadFile')) {
    /**
     * Upload a file to the given disk and folder, with unique filename if needed.
     *
     * @param UploadedFile $file
     * @param string|null $fileName Without extension (optional).
     * @param string $folder
     * @param string $disk
     * @return array{
     *     filename: string,
     *     filepath: string,
     *     mime: string,
     *     extension: string
     * }
     */
    function getFileFullPath($fileName, $folder)
    {
        $disk = config("filesystems.default");
        if (empty($fileName)) {
            return null; // Optional: handle missing filename
        }

        $path = config('constants.filehelper_config.upload_folder', 'uploads') . "/" . $folder . '/' . $fileName;

        // Returns full accessible URL
        return Storage::disk($disk)->url($path);
    }

    function deleteFile($fileName, $folder)
    {
        $disk = config("filesystems.default");

        if (!empty($fileName)) {
            $path = config('constants.filehelper_config.upload_folder', 'uploads') . "/" . $folder . '/' . $fileName;

            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->delete($path);
            }
        }

        return false;
    }
    function uploadFile(UploadedFile $file, string|null $fileName, string $folder = 'common'): array
    {
        $disk = config("filesystems.default");

        $extension = $file->getClientOriginalExtension();
        $mime = $file->getMimeType();


        $folder = config('constants.filehelper_config.upload_folder', 'uploads') . "/" . $folder;
        // If no filename provided, use current timestamp + random string
        $baseName = $fileName ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $baseName = Str::slug($baseName); // sanitize filename

        $finalName = $baseName;
        $i = 1;
        $storage = Storage::disk($disk);

        // Ensure unique filename
        while ($storage->exists($folder . '/' . $finalName . '.' . $extension)) {
            $finalName = $baseName . '-' . $i++;
        }

        $filePath = $folder . '/' . $finalName . '.' . $extension;

        // Save file
        $storage->putFileAs($folder, $file, $finalName . '.' . $extension);

        return [
            'filename'  => $finalName . '.' . $extension,
            'filepath'  => $filePath,
            'mime'      => $mime,
            'extension' => $extension,
        ];
    }
}
