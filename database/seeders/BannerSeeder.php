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

        // Clear existing records to ensure only one banner
        Banner::truncate();

        // Create exactly one banner record with placeholder
        Banner::create([
            'image' => 'https://via.placeholder.com/800x400/4F46E5/FFFFFF?text=DUMMY+BANNER'
        ]);
    }
}
