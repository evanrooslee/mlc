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
        Schema::table('packets', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->unique('slug');
        });

        // Generate slugs for existing packets
        $packets = \App\Models\Packet::all();
        foreach ($packets as $packet) {
            $packet->slug = \Illuminate\Support\Str::slug($packet->title);
            // Handle duplicate slugs by appending the ID
            $originalSlug = $packet->slug;
            $count = 1;
            while (\App\Models\Packet::where('slug', $packet->slug)->where('id', '!=', $packet->id)->exists()) {
                $packet->slug = $originalSlug . '-' . $count;
                $count++;
            }
            $packet->save();
        }

        // Make slug non-nullable after generating for all records
        Schema::table('packets', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packets', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
