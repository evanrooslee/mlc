<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            throw new \Exception('GD extension is required for image processing. Please enable GD extension in your PHP configuration.');
        }

        // Create ImageManager with GD driver
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Resize and save uploaded image
     *
     * @param UploadedFile $file
     * @param string $path
     * @param int $width
     * @param int $height
     * @param string $disk
     * @return string
     */
    public function resizeAndSave(UploadedFile $file, string $path, int $width = 800, int $height = 600, string $disk = 'public'): string
    {
        // Read and resize the image
        $image = $this->manager->read($file->getPathname());

        // Resize the image while maintaining aspect ratio
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize(); // Prevent upsizing
        });

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $fullPath = $path . '/' . $filename;

        // Save the resized image
        Storage::disk($disk)->put($fullPath, $image->encode());

        return $fullPath;
    }

    /**
     * Resize image to exact dimensions (may crop)
     *
     * @param UploadedFile $file
     * @param string $path
     * @param int $width
     * @param int $height
     * @param string $disk
     * @return string
     */
    public function resizeAndCrop(UploadedFile $file, string $path, int $width = 800, int $height = 600, string $disk = 'public'): string
    {
        // Read and resize the image
        $image = $this->manager->read($file->getPathname());

        // Resize and crop to exact dimensions
        $image->cover($width, $height);

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $fullPath = $path . '/' . $filename;

        // Save the resized image
        Storage::disk($disk)->put($fullPath, $image->encode());

        return $fullPath;
    }

    /**
     * Create multiple sizes of an image
     *
     * @param UploadedFile $file
     * @param string $path
     * @param array $sizes
     * @param string $disk
     * @return array
     */
    public function createMultipleSizes(UploadedFile $file, string $path, array $sizes = [], string $disk = 'public'): array
    {
        $defaultSizes = [
            'thumbnail' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 400, 'height' => 300],
            'large' => ['width' => 800, 'height' => 600],
        ];

        $sizes = array_merge($defaultSizes, $sizes);
        $paths = [];

        foreach ($sizes as $sizeName => $dimensions) {
            $image = $this->manager->read($file->getPathname());

            $image->resize($dimensions['width'], $dimensions['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $filename = $sizeName . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $fullPath = $path . '/' . $filename;

            Storage::disk($disk)->put($fullPath, $image->encode());
            $paths[$sizeName] = $fullPath;
        }

        return $paths;
    }

    /**
     * Resize image for specific type using config dimensions
     *
     * @param UploadedFile $file
     * @param string $type (banner, package, thumbnail, etc.)
     * @param string $path
     * @param string $disk
     * @return string
     */
    public function resizeForType(UploadedFile $file, string $type, string $path, string $disk = 'public'): string
    {
        $dimensions = config("image.dimensions.{$type}");

        if (!$dimensions) {
            throw new \InvalidArgumentException("Image dimensions for type '{$type}' not found in config.");
        }

        return $this->resizeAndCrop($file, $path, $dimensions['width'], $dimensions['height'], $disk);
    }
}
