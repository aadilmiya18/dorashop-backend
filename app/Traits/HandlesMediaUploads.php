<?php

namespace App\Traits;

use App\Services\CloudinaryService;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

trait HandlesMediaUploads
{
    /**
     * Upload a single media file to Cloudinary.
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
     * Replace existing media (for single image update)
     */
    public function replaceMedia(UploadedFile|string|null $file, string $type = 'image', string $folder = 'dorashop'): ?Media
    {
        if (!$file) return null;

        $cloudinary = new CloudinaryService();
        $existingMedia = $this->media()->where('type', $type)->first();

        if ($existingMedia) {
            $cloudinary->deleteByUrl($existingMedia->url);
            $existingMedia->delete();
        }

        return $this->uploadMedia($file, $type, $folder);
    }

    /**
     * Upload multiple media files (gallery)
     */
    public function uploadMultipleMedia(array|Collection $files, string $type = 'gallery', string $folder = 'dorashop'): Collection
    {
        $uploadedMedia = collect();
        $cloudinary = new CloudinaryService();

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploadedUrl = $cloudinary->upload($file, $folder);

                if ($uploadedUrl) {
                    $uploadedMedia->push(
                        $this->media()->create([
                            'url' => $uploadedUrl,
                            'type' => $type,
                        ])
                    );
                }
            }
        }

        return $uploadedMedia;
    }

    /**
     * Delete all media for this model
     */
    public function deleteMedia(?string $type = null): void
    {
        $cloudinary = new CloudinaryService();
        $mediaItems = $type ? $this->media()->where('type', $type)->get() : $this->media;

        foreach ($mediaItems as $media) {
            $cloudinary->deleteByUrl($media->url);
            $media->delete();
        }
    }
}
