<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;

use Intervention\Image\ImageManagerStatic as Image;

// use Intervention\Image\Encoders\AutoEncoder;


class FileStorageLocal
{
    public static function storeFile($photo, $path, $document)
    {
        $sanitizedDocument = preg_replace('/[^a-zA-Z0-9]/', '', $document);
        $sanitizedPath = preg_replace('/[^a-zA-Z0-9]/', '', $path);

        $subdirectory = $sanitizedPath;

        // Use a proper date format
        $fileName = $sanitizedDocument . '_' . $sanitizedPath . '_' . date('His') . '_' . date('Ymd') . '.' . $photo->extension();

        $storagePath = 'public/' . $subdirectory;

        $save = Storage::disk('local')->putFileAs($storagePath, $photo, $fileName);

        $localUrl = env('APP_URL') . '/storage/' . $subdirectory . '/' . $fileName;

        return $localUrl;
    }


    public static function deleteFile($fileUrl)
    {
        // Implement the logic to delete the file using the provided URL
        // For example, if you're using Laravel's Storage facade:
        Storage::delete($fileUrl);
    }

    public static function updateFile($currentUrl, $newPhoto, $path)
    {
        // First, delete the current file using the URL
        self::deleteFile($currentUrl);

        // Then, store the new file and return its URL
        return self::storeFile($newPhoto, $path);
    }
}
