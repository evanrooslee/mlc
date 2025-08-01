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
            // Drop the featured index first
            $table->dropIndex(['featured']);
            $table->dropIndex(['featured', 'subject']);

            // Remove the featured column
            $table->dropColumn('featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // Add back the featured column
            $table->boolean('featured')->default(false);

            // Re-add the indexes
            $table->index('featured');
            $table->index(['featured', 'subject']);
        });
    }
};
