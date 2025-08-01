<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only add indexes if tables exist (for testing compatibility)
        if (Schema::hasTable('packet_user')) {
            try {
                Schema::table('packet_user', function (Blueprint $table) {
                    // Add index for packet_id to optimize counting purchases per packet
                    $table->index('packet_id', 'idx_packet_user_packet_id');

                    // Add index for user_id to optimize user's packet queries
                    $table->index('user_id', 'idx_packet_user_user_id');

                    // Add composite index for packet_id, user_id for faster lookups
                    // Note: This might be redundant with the primary key, but ensures optimal performance
                    $table->index(['packet_id', 'user_id'], 'idx_packet_user_composite');
                });
            } catch (\Exception $e) {
                // Silently fail if indexes already exist or other issues
                Log::info('Could not add packet_user indexes: ' . $e->getMessage());
            }
        }

        // Add index to packets table for created_at to optimize fallback ordering
        if (Schema::hasTable('packets')) {
            try {
                Schema::table('packets', function (Blueprint $table) {
                    $table->index('created_at', 'idx_packets_created_at');
                });
            } catch (\Exception $e) {
                // Silently fail if index already exists or other issues
                Log::info('Could not add packets indexes: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('packet_user')) {
            try {
                Schema::table('packet_user', function (Blueprint $table) {
                    $table->dropIndex('idx_packet_user_packet_id');
                    $table->dropIndex('idx_packet_user_user_id');
                    $table->dropIndex('idx_packet_user_composite');
                });
            } catch (\Exception $e) {
                // Silently fail if indexes don't exist
            }
        }

        if (Schema::hasTable('packets')) {
            try {
                Schema::table('packets', function (Blueprint $table) {
                    $table->dropIndex('idx_packets_created_at');
                });
            } catch (\Exception $e) {
                // Silently fail if index doesn't exist
            }
        }
    }
};
