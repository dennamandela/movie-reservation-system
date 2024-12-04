<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Base64Decode
{
    public static function decode($base64_string, $path, $document)
    {
        $inputType = '';
        if (strpos($base64_string, 'data:image') === 0) {
            $inputType = 'image';
        } elseif (strpos($base64_string, 'data:application/pdf') === 0) {
            $inputType = 'pdf';
        }

        if ($inputType === 'image') {
            $base64Image = preg_replace('/^data:image\/(jpeg|png|jpg);base64,/', '', $base64_string);
            // $imageData = base64_decode($base64Image);

            $tempFilePath = tempnam(sys_get_temp_dir(), 'image_');
            file_put_contents($tempFilePath, base64_decode($base64Image));
            try {
                $image = Image::make($tempFilePath);
            } catch (\Exception $e) {
                unlink($tempFilePath);
                return null;
            }
            // atur ukuran gambar
            // $image->resize(300, 200);

            // atur kualitas gambar 
            $compressionQuality = 20;
            $encodedImage = $image->encode('jpg', $compressionQuality);

            $sanitizedDocument = preg_replace('/[^a-zA-Z0-9]+/', '', $document);
            $sanitizedPath = preg_replace('/[^a-zA-Z0-9]+/', '', $path);
            $subdirectory = $sanitizedPath;
            $storagePath = 'storage/' . $subdirectory;

            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            // Generate a unique filename
            $fileName = $sanitizedDocument . '_' . $sanitizedPath . '_' . date('His') . '_' . date('Ymd') . '.jpeg';
            $filePath = $storagePath . '/' . $fileName;

            file_put_contents($filePath, $encodedImage);

            unlink($tempFilePath);

            // Save the image to storage
            // $save = Storage::disk('local')->put($storagePath . '/' . $fileName, $imageData);
        } elseif ($inputType === 'pdf') {
            // For PDF data
            $base64Pdf = preg_replace('/^data:application\/pdf;base64,/', '', $base64_string);
            $pdfData = base64_decode($base64Pdf);

            // Handling PDF data...
            // Sanitize document and path by removing special characters and spaces
            $sanitizedDocument = preg_replace('/[^a-zA-Z0-9]+/', '', $document);
            $sanitizedPath = preg_replace('/[^a-zA-Z0-9]+/', '', $path);

            // Generate a unique filename
            $fileName = $sanitizedDocument . '_' . $sanitizedPath . '_' . date('His') . '_' . date('Ymd') . '.pdf';

            $subdirectory = $sanitizedPath;

            $storagePath = 'public/' . $subdirectory;

            // Save the PDF to storage
            $save = Storage::disk('local')->put($storagePath . '/' . $fileName, $pdfData);
        }

        $localUrl = env('APP_URL') . '/storage/' . $subdirectory . '/' . $fileName;

        return $localUrl;
    }
}
