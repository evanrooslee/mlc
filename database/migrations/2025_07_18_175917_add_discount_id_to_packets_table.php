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
            $table->unsignedBigInteger('discount_id')->nullable()->after('price');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('set null');
            $table->dropColumn('discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packets', function (Blueprint $table) {
            $table->dropForeign(['discount_id']);
            $table->dropColumn('discount_id');
            $table->integer('discount')->default(0)->after('price');
        });
    }
};
