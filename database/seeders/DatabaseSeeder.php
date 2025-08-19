<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DiscountSeeder::class,
            PacketSeeder::class,
            MaterialSeeder::class,
            ArticleSeeder::class,
            BannerSeeder::class,
            BannerCardSeeder::class,
            // PaymentSeeder::class
        ]);
    }
}
