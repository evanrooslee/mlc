<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discounts = [
            ['code' => 'DISKON10', 'percentage' => 10, 'is_valid' => true],
            ['code' => 'DISKON20', 'percentage' => 20, 'is_valid' => true],
            ['code' => 'DISKON30', 'percentage' => 30, 'is_valid' => true],
            ['code' => 'HEMAT40', 'percentage' => 40, 'is_valid' => true],
            ['code' => 'PROMO50', 'percentage' => 50, 'is_valid' => true],
            ['code' => 'EXPIRED25', 'percentage' => 25, 'is_valid' => false],
        ];

        foreach ($discounts as $discount) {
            Discount::create($discount);
        }
    }
}
