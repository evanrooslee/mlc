<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing records
        Material::truncate();

        // Create storage directory if it doesn't exist
        $storagePath = storage_path('app/public/videos');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Copy sample thumbnail images to storage
        $this->copySampleThumbnails();

        // Create exactly 4 materials
        $this->createFourMaterials();
    }

    /**
     * Copy sample thumbnail images to storage
     */
    private function copySampleThumbnails(): void
    {
        $thumbnails = [
            'video-thumbnail1.jpg',
            'video-thumbnail2.jpg',
            'video-thumbnail3.jpg',
            'video-thumbnail4.jpg',
        ];

        foreach ($thumbnails as $index => $thumbnail) {
            $sourcePath = public_path('images/' . $thumbnail);
            $destinationPath = 'videos/thumbnail' . ($index + 1) . '.jpg';

            if (File::exists($sourcePath)) {
                Storage::disk('public')->put($destinationPath, File::get($sourcePath));
            }
        }
    }

    /**
     * Create exactly 4 materials (2 Matematika, 2 Fisika)
     */
    private function createFourMaterials(): void
    {
        $materials = [
            [
                'title' => '[DUMMY VIDEO] Logaritma Dasar',
                'subject' => 'Matematika',
                'publisher' => 'Mr. Chris (Dummy)',
                'video' => 'videos/thumbnail1.jpg',
            ],
            [
                'title' => '[DUMMY VIDEO] Integral dan Turunan',
                'subject' => 'Matematika',
                'publisher' => 'Ms. Sarah (Dummy)',
                'video' => 'videos/thumbnail2.jpg',
            ],
            [
                'title' => '[DUMMY VIDEO] Hukum Newton dan Aplikasinya',
                'subject' => 'Fisika',
                'publisher' => 'Mr. Robert (Dummy)',
                'video' => 'videos/thumbnail3.jpg',
            ],
            [
                'title' => '[DUMMY VIDEO] Gelombang Elektromagnetik',
                'subject' => 'Fisika',
                'publisher' => 'Ms. Linda (Dummy)',
                'video' => 'videos/thumbnail4.jpg',
            ],
        ];

        foreach ($materials as $material) {
            Material::create([
                'title' => $material['title'],
                'subject' => $material['subject'],
                'publisher' => $material['publisher'],
                'video' => $material['video'],
            ]);
        }
    }
}
