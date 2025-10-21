<?php

namespace App\Traits;

use App\Services\CloudinaryService;
use App\Models\Media;
use Illuminate\Http\UploadedFile;

trait HandlesMediaUploads
{
    /**
     * Upload a media file to Cloudinary and attach it to the model.
     */
    public function uploadMedia(UploadedFile|string|null $file, string $type = 'image', string $folder = 'dorashop'): ?Media
    {
        if (!$file) {
            return null;
        }

        $cloudinary = new CloudinaryService();
        $uploadedUrl = $cloudinary->upload($file, $folder);

        if ($uploadedUrl) {
            return $this->media()->create([
                'url' => $uploadedUrl,
                'type' => $type,
            ]);
        }

        return null;
    }

    /**
     * Replace the existing media file with a new one.
     */
    public function replaceMedia(UploadedFile|string|null $file, string $type = 'image', string $folder = 'dorashop'): ?Media
    {
        if (!$file) {
            return null;
        }

        $cloudinary = new CloudinaryService();
        $existingMedia = $this->media()->first();

        if ($existingMedia) {
            $cloudinary->deleteByUrl($existingMedia->url);
            $existingMedia->delete();
        }

        return $this->uploadMedia($file, $type, $folder);
    }

    /**
     * Delete all media associated with this model.
     */
    public function deleteMedia(): void
    {
        $cloudinary = new CloudinaryService();

        foreach ($this->media as $media) {
            $cloudinary->deleteByUrl($media->url);
            $media->delete();
        }
    }
}
