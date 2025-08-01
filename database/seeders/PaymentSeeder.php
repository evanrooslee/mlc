<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Packet;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $packets = Packet::all();

        if ($students->isEmpty() || $packets->isEmpty()) {
            $this->command->info('No students or packets to seed payments for.');
            return;
        }

        foreach ($students as $student) {
            $packet = $packets->random();
            $status = ['Belum Bayar', 'Sudah Bayar'][array_rand(['Belum Bayar', 'Sudah Bayar'])];

            DB::table('payments')->insert([
                'user_id' => $student->id,
                'packet_id' => $packet->id,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($status === 'Sudah Bayar') {
                DB::table('packet_user')->insert([
                    'user_id' => $student->id,
                    'packet_id' => $packet->id,
                ]);
            }
        }
    }
}
