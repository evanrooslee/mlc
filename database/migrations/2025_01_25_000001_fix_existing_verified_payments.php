<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run if both tables exist
        if (!Schema::hasTable('payments') || !Schema::hasTable('packet_user')) {
            return;
        }

        // Fix existing verified payments that don't have packet_user relationships
        $verifiedPayments = DB::table('payments')
            ->where('status', 'Sudah Bayar')
            ->get();

        foreach ($verifiedPayments as $payment) {
            // Check if packet_user relationship already exists
            $exists = DB::table('packet_user')
                ->where('user_id', $payment->user_id)
                ->where('packet_id', $payment->packet_id)
                ->exists();

            // If not, create it
            if (!$exists) {
                DB::table('packet_user')->insert([
                    'user_id' => $payment->user_id,
                    'packet_id' => $payment->packet_id
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is a data fix, so we don't reverse it
        // as it would remove legitimate packet_user relationships
    }
};
