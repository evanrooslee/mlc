<?php

namespace Database\Seeders;

use App\Models\Packet;
use App\Models\Discount;
use Illuminate\Database\Seeder;

class PacketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = ['Matematika', 'Fisika', 'Kimia'];
        $types = ['premium', 'standard'];
        $grades = [7, 8, 9, 10, 11, 12];

        // Get available discount IDs (some packets will have no discount)
        $discountIds = Discount::pluck('id')->toArray();
        $discountIds[] = null; // Add null for packets without discount

        // Create 20 packets with constrained data
        for ($i = 0; $i < 20; $i++) {
            $subject = fake()->randomElement($subjects);
            $grade = fake()->randomElement($grades);
            $type = fake()->randomElement($types);
            $basePrice = fake()->numberBetween(50000, 500000);
            $discountId = fake()->randomElement($discountIds);

            // Generate unique packet code
            $code = strtoupper(substr($subject, 0, 3) . $grade . fake()->randomNumber(3, true));

            // Generate 3-5 benefit sentences, each on a new line
            $benefitSentences = [];
            $numBenefits = fake()->numberBetween(3, 5);
            for ($j = 0; $j < $numBenefits; $j++) {
                $benefitSentences[] = fake()->sentence(fake()->numberBetween(5, 10));
            }
            $benefit = implode("\n", $benefitSentences);

            Packet::create([
                'title' => '[DUMMY PACKET] Paket ' . fake()->randomNumber(1) . ' - ' . $subject . ' Kelas ' . $grade,
                'code' => $code,
                'grade' => $grade,
                'subject' => $subject,
                'type' => $type,
                'benefit' => $benefit,
                'price' => $basePrice,
                'discount_id' => $discountId,
                'image' => 'packet1.jpg',
            ]);
        }

        // Create some specific packets for better testing
        Packet::create([
            'title' => '[DUMMY PACKET] Paket Lengkap Matematika - Kelas 12',
            'code' => 'MAT12001',
            'grade' => 12,
            'subject' => 'Matematika',
            'type' => 'premium',
            'benefit' => "Kurikulum matematika lengkap untuk kelas 12 termasuk kalkulus, statistik, dan aljabar lanjutan.\nDilengkapi dengan tutorial video, latihan soal, dan simulasi ujian.\nAkses ke forum diskusi untuk tanya jawab dengan tutor.\nMateri dapat diakses offline.",
            'price' => 299000,
            'discount_id' => 2, // DISKON20 (20%)
            'image' => 'packet1.jpg',
        ]);

        Packet::create([
            'title' => '[DUMMY PACKET] Dasar-dasar Fisika - Kelas 11',
            'code' => 'FIS11001',
            'grade' => 11,
            'subject' => 'Fisika',
            'type' => 'standard',
            'benefit' => "Kuasai dasar-dasar fisika termasuk mekanika, termodinamika, dan teori gelombang.\nPerfect untuk membangun fondasi yang kuat dalam fisika.\nLatihan soal dengan pembahasan detail.\nVideo eksperimen fisika yang menarik.",
            'price' => 199000,
            'discount_id' => null, // No discount
            'image' => 'packet1.jpg',
        ]);

        Packet::create([
            'title' => '[DUMMY PACKET] Fisika Kuantum - Kelas 12',
            'code' => 'FIS12001',
            'grade' => 12,
            'subject' => 'Fisika',
            'type' => 'premium',
            'benefit' => "Materi fisika kuantum untuk persiapan kuliah.\nPembahasan soal-soal olimpiade fisika.\nKonsultasi langsung dengan dosen fisika terkemuka.\nAkses seumur hidup ke materi yang selalu diperbarui.",
            'price' => 349000,
            'discount_id' => 1, // DISKON10 (10%)
            'image' => 'packet1.jpg',
        ]);

        Packet::create([
            'title' => '[DUMMY PACKET] Matematika Dasar - Kelas 7',
            'code' => 'MAT07001',
            'grade' => 7,
            'subject' => 'Matematika',
            'type' => 'standard',
            'benefit' => "Pengenalan konsep dasar matematika untuk kelas 7.\nLatihan soal interaktif yang menyenangkan.\nPenjelasan dengan bahasa yang mudah dipahami.\nTips dan trik mengerjakan soal dengan cepat.",
            'price' => 149000,
            'discount_id' => 3, // DISKON30 (30%)
            'image' => 'packet1.jpg',
        ]);
    }
}
