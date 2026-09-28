<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Kompresi dan simpan file gambar yang diunggah.
     * Mengatur resolusi maksimum secara proporsional dan mengompres ukuran file
     * tanpa menurunkan ketajaman dan estetika visual gambar.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality
     * @param string $disk
     * @return string
     */
    public static function compressAndStore(
        UploadedFile $file,
        string $directory = 'images',
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $quality = 82,
        string $disk = 'public'
    ): string {
        $mime = strtolower($file->getMimeType() ?: '');
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');

        // File vektor SVG tidak perlu dikompres raster
        if (in_array($extension, ['svg', 'svgz']) || str_contains($mime, 'svg')) {
            return $file->store($directory, $disk);
        }

        // Jika ekstensi GD tidak aktif, simpan langsung
        if (!extension_loaded('gd')) {
            return $file->store($directory, $disk);
        }

        try {
            $filePath = $file->getRealPath();
            if (!$filePath || !file_exists($filePath)) {
                return $file->store($directory, $disk);
            }

            // Buat resource gambar GD
            $image = self::createImageFromFile($filePath, $mime, $extension);
            if (!$image) {
                return $file->store($directory, $disk);
            }

            // Perbaiki orientasi EXIF (misal foto kamera smartphone miring)
            if (in_array($mime, ['image/jpeg', 'image/pjpeg', 'image/jpg']) && function_exists('exif_read_data')) {
                $image = self::autoRotateImage($image, $filePath);
            }

            // Resize proporsional jika melebihi batas resolusi maksimum
            $origWidth = imagesx($image);
            $origHeight = imagesy($image);

            if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
                $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
                $newWidth = max(1, (int) round($origWidth * $ratio));
                $newHeight = max(1, (int) round($origHeight * $ratio));

                $resized = imagecreatetruecolor($newWidth, $newHeight);

                // Pertahankan transparansi PNG / WebP / GIF
                if (in_array($mime, ['image/png', 'image/x-png', 'image/webp', 'image/gif'])) {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                    imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
                }

                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagedestroy($image);
                $image = $resized;
            }

            // Buat nama file unik
            $normalizedExt = ($extension === 'jpeg') ? 'jpg' : $extension;
            $filename = Str::random(40) . '.' . $normalizedExt;
            $targetPath = trim($directory, '/') . '/' . $filename;

            // Output ke buffer memori dengan kompresi berkualitas tinggi
            ob_start();
            switch ($mime) {
                case 'image/png':
                case 'image/x-png':
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    imagepng($image, null, 8); // Tingkat kompresi PNG 8 (0-9)
                    break;

                case 'image/webp':
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    imagewebp($image, null, $quality); // Kualitas WebP 82
                    break;

                case 'image/gif':
                    imagegif($image);
                    break;

                case 'image/jpeg':
                case 'image/pjpeg':
                default:
                    imagejpeg($image, null, $quality); // Kualitas JPEG 82
                    break;
            }
            $binaryData = ob_get_clean();
            imagedestroy($image);

            if (!empty($binaryData)) {
                Storage::disk($disk)->put($targetPath, $binaryData);
                return $targetPath;
            }

            return $file->store($directory, $disk);
        } catch (\Throwable $e) {
            Log::warning('ImageService compression fallback: ' . $e->getMessage());
            return $file->store($directory, $disk);
        }
    }

    protected static function createImageFromFile(string $path, string $mime, string $extension)
    {
        switch ($mime) {
            case 'image/jpeg':
            case 'image/pjpeg':
                return @imagecreatefromjpeg($path);
            case 'image/png':
            case 'image/x-png':
                return @imagecreatefrompng($path);
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    return @imagecreatefromwebp($path);
                }
                break;
            case 'image/gif':
                return @imagecreatefromgif($path);
        }

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return @imagecreatefromjpeg($path);
            case 'png':
                return @imagecreatefrompng($path);
            case 'webp':
                return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null;
            case 'gif':
                return @imagecreatefromgif($path);
        }

        $content = @file_get_contents($path);
        if ($content !== false) {
            return @imagecreatefromstring($content);
        }

        return null;
    }

    protected static function autoRotateImage($image, string $filePath)
    {
        $exif = @exif_read_data($filePath);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $rotated = imagerotate($image, 180, 0);
                    imagedestroy($image);
                    return $rotated;
                case 6:
                    $rotated = imagerotate($image, -90, 0);
                    imagedestroy($image);
                    return $rotated;
                case 8:
                    $rotated = imagerotate($image, 90, 0);
                    imagedestroy($image);
                    return $rotated;
            }
        }
        return $image;
    }
}
