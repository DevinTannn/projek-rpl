<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class ImageService
{
    /**
     * Convert an uploaded image to WebP if supported, otherwise store it as is.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return array [path, name, type, extension]
     */
    public static function convertAndStore($file, $folder)
    {
        $mime = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());
        $filenameWithoutExt = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/heic', 'image/heif', 'image/webp'];
        
        if (in_array($mime, $imageTypes) || in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'heic', 'heif', 'webp'])) {
            // Try Imagick first if available (especially for HEIC / HEIF / GIF)
            if (class_exists('Imagick')) {
                try {
                    $imagick = new \Imagick($file->getRealPath());
                    $imagick->setImageFormat('webp');
                    
                    $tempPath = tempnam(sys_get_temp_dir(), 'webp');
                    $imagick->writeImage($tempPath);
                    $imagick->clear();
                    $imagick->destroy();
                    
                    $newFilename = $filenameWithoutExt . '_' . time() . '_' . uniqid() . '.webp';
                    $path = Storage::disk('public')->putFileAs($folder, new File($tempPath), $newFilename);
                    @unlink($tempPath);
                    
                    return [
                        'path' => $path,
                        'name' => $filenameWithoutExt . '.webp',
                        'type' => 'image',
                        'extension' => 'webp'
                    ];
                } catch (\Exception $e) {
                    // Fail over to GD
                }
            }
            
            // Try GD for standard types (JPEG, PNG, GIF, WebP)
            if (in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']) ||
                in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                try {
                    $image = null;
                    if ($mime === 'image/png' || $extension === 'png') {
                        $image = @imagecreatefrompng($file->getRealPath());
                    } elseif ($mime === 'image/gif' || $extension === 'gif') {
                        $image = @imagecreatefromgif($file->getRealPath());
                    } elseif ($mime === 'image/webp' || $extension === 'webp') {
                        $image = @imagecreatefromwebp($file->getRealPath());
                    } else {
                        $image = @imagecreatefromjpeg($file->getRealPath());
                    }
                    
                    if ($image) {
                        imagepalettetotruecolor($image);
                        imagealphablending($image, false);
                        imagesavealpha($image, true);
                        
                        $tempPath = tempnam(sys_get_temp_dir(), 'webp');
                        if (imagewebp($image, $tempPath, 85)) {
                            imagedestroy($image);
                            
                            $newFilename = $filenameWithoutExt . '_' . time() . '_' . uniqid() . '.webp';
                            $path = Storage::disk('public')->putFileAs($folder, new File($tempPath), $newFilename);
                            @unlink($tempPath);
                            
                            return [
                                'path' => $path,
                                'name' => $filenameWithoutExt . '.webp',
                                'type' => 'image',
                                'extension' => 'webp'
                            ];
                        }
                        imagedestroy($image);
                    }
                } catch (\Exception $e) {
                    // Fail over to storing original
                }
            }
        }
        
        // Default / fallback storing
        $type = 'image';
        if (str_starts_with($mime, 'video')) {
            $type = 'video';
        } elseif (in_array($mime, [
            'application/pdf', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip'
        ]) || in_array($extension, ['pdf', 'docx', 'xlsx', 'zip'])) {
            $type = 'document';
        }
        
        $newFilename = $filenameWithoutExt . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $newFilename, 'public');
        
        return [
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'type' => $type,
            'extension' => $file->getClientOriginalExtension()
        ];
    }
}
