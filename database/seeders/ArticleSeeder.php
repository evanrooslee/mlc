<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing records
        Article::truncate();

        // Create storage directory if it doesn't exist
        $storagePath = storage_path('app/public/articles');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Copy sample thumbnail
        $sourcePath = public_path('images/article1.jpg');
        $destinationPath = 'articles/sample-article.jpg';

        if (File::exists($sourcePath)) {
            Storage::disk('public')->put($destinationPath, File::get($sourcePath));
        }

        $articles = [
            [
                'title' => 'Kecerdasan Buatan: Teman atau Ancaman di Era Digital?',
                'source' => 'kompas.com',
                'url' => 'https://www.kompas.com/skola/read/2023/04/13/130000169/kecerdasan-buatan--teman-atau-ancaman-di-era-digital-',
                'image' => $destinationPath,
                'is_starred' => true,
            ],
            [
                'title' => '5 Manfaat Bimbel Bagi Anak, Mulai dari Meningkatkan Minat Belajar hingga Mengasah Kecerdasan',
                'source' => 'kompas.com',
                'url' => 'https://edukasi.kompas.com/read/2022/07/22/104251271/5-manfaat-bimbel-bagi-anak-mulai-dari-meningkatkan-minat-belajar?page=all',
                'image' => $destinationPath,
                'is_starred' => true,
            ],
            [
                'title' => 'Strategi Belajar Efektif: Kunci Sukses Meraih Prestasi di Era Digital',
                'source' => 'kompas.com',
                'url' => 'https://www.kompas.id/baca/humaniora/2023/08/11/strategi-belajar-efektif-kunci-sukses-meraih-prestasi-di-era-digital',
                'image' => $destinationPath,
                'is_starred' => true,
            ],
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
