<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Prompts\Table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packets', function (Blueprint $table){
            $table->string('tingkatan')->nullable()->after('code');
            $table->string('kurikulum')->nullable()->after('tingkatan');
            $table->integer('sesi')->nullable()->after('benefit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packets', function (Blueprint $table) {
            $table->dropColumn('tingkatan');
            $table->dropColumn('kurikulum');
            $table->dropColumn('sesi');
        });
    }
};
