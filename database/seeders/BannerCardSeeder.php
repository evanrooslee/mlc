<?php

namespace Database\Seeders;

use App\Models\BannerCard;
use Illuminate\Database\Seeder;

class BannerCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bannerCards = [
            [
                'title' => 'MLC Regular Class',
                'description' => 'Kelas online rutin dengan jadwal terstruktur.',
                'background_image' => 'placeholder-regular-class.jpg',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'MLC Try Out Center',
                'description' => 'Try Out mingguan untuk evaluasi kesiapan ujian.',
                'background_image' => 'placeholder-tryout-center.jpg',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'MLC Forum Diskusi',
                'description' => 'Forum tanya-jawab soal dengan tutor dan teman.',
                'background_image' => 'placeholder-forum-diskusi.jpg',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'MLC Counseling',
                'description' => 'Konsultasi gratis tentang jurusan dan strategi belajar.',
                'background_image' => 'placeholder-counseling.jpg',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'MLC Learning Management System',
                'description' => 'Akses materi, rekaman kelas, dan progress belajar.',
                'background_image' => 'placeholder-lms.jpg',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'MLC Free Trial Class',
                'description' => 'Kelas percobaan gratis untuk semua siswa baru.',
                'background_image' => 'placeholder-free-trial.jpg',
                'display_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($bannerCards as $cardData) {
            BannerCard::create($cardData);
        }
    }
}
