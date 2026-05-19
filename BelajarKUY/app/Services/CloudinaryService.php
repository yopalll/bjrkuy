<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
    }

    /**
     * Upload an image to Cloudinary.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return array Contains 'url' and 'public_id'
     */
    public function uploadImage(UploadedFile $file, string $folder = 'lms_assets'): array
    {
        try {
            $response = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => $folder,
            ]);

            return [
                'url' => $response['secure_url'],
                'public_id' => $response['public_id'],
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary Upload Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Replace an existing image in Cloudinary with a new one.
     *
     * @param UploadedFile $file
     * @param string|null $oldPublicId
     * @param string $folder
     * @return array Contains new 'url' and 'public_id'
     */
    public function replaceImage(UploadedFile $file, ?string $oldPublicId, string $folder = 'lms_assets'): array
    {
        if ($oldPublicId) {
            $this->deleteImage($oldPublicId);
        }

        return $this->uploadImage($file, $folder);
    }

    /**
     * Delete an image from Cloudinary by its public ID.
     *
     * @param string $publicId
     * @return void
     */
    public function deleteImage(string $publicId): void
    {
        if (!empty($publicId)) {
            try {
                $this->cloudinary->uploadApi()->destroy($publicId);
            } catch (\Exception $e) {
                Log::error('Cloudinary Delete Error: ' . $e->getMessage());
            }
        }
    }
}
