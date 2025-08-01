<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create storage directory if it doesn't exist
        $storagePath = storage_path('app/public/banners');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Copy a sample image from public to storage
        $sourcePath = public_path('images/article-illustration.png');
        $destinationPath = 'banners/sample-banner.png';
        
        if (File::exists($sourcePath)) {
            Storage::disk('public')->put($destinationPath, File::get($sourcePath));
            
            // Create banner record
            Banner::create([
                'image' => $destinationPath
            ]);
        }
    }
}
