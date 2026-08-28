<?php

namespace App\Traits;

trait DeletesUploadFiles
{
    /**
     * Unlink file safely from local public_path if it exists.
     *
     * @param string|array|null $filePath
     * @return void
     */
    public static function deleteUploadFile($filePath)
    {
        if (empty($filePath)) {
            return;
        }

        // Handle array or JSON string if gallery photos
        if (is_string($filePath) && (str_starts_with($filePath, '[') || str_starts_with($filePath, '{'))) {
            $decoded = json_decode($filePath, true);
            if (is_array($decoded)) {
                foreach ($decoded as $p) {
                    self::deleteUploadFile($p);
                }
                return;
            }
        }

        if (is_array($filePath)) {
            foreach ($filePath as $p) {
                self::deleteUploadFile($p);
            }
            return;
        }

        // Do not delete external HTTP(S) URLs unless pointing to /uploads/
        if (preg_match('/^https?:\/\//i', $filePath)) {
            $path = parse_url($filePath, PHP_URL_PATH) ?? '';
            if (str_contains($path, '/uploads/')) {
                $filePath = $path;
            } else {
                return;
            }
        }

        $cleanPath = ltrim($filePath, '/');
        $fullPath = public_path($cleanPath);

        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
