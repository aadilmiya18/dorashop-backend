<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
       $this->cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
    }

    public function upload($file, $folder = 'dorashop')
    {
        $uploaded = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder'=> $folder,
                'resource_type' => 'auto'

            ]);
        return $uploaded['secure_url'] ?? null;
    }

    public function delete($publicId)
    {
        return $this->cloudinary->uploadApi()->destroy($publicId);
    }

    public function deleteByUrl($url)
    {
        $publicId = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
        return $this->delete($publicId);
    }

}

