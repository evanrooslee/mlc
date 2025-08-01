<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // Add index for featured column for faster featured video queries
            $table->index('featured');

            // Add index for subject column for filtering by subject
            $table->index('subject');

            // Add composite index for featured and subject for optimized queries
            $table->index(['featured', 'subject']);

            // Add index for publisher for filtering by publisher
            $table->index('publisher');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // Drop indexes in reverse order
            $table->dropIndex(['publisher']);
            $table->dropIndex(['featured', 'subject']);
            $table->dropIndex(['subject']);
            $table->dropIndex(['featured']);
        });
    }
};
