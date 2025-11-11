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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->text('content')->nullable()->after('url');
            $table->unique('slug');
        });

        // Generate slugs for existing articles
        $articles = \App\Models\Article::all();
        foreach ($articles as $article) {
            $article->slug = \Illuminate\Support\Str::slug($article->title);
            // Handle duplicate slugs by appending a number
            $originalSlug = $article->slug;
            $count = 1;
            while (\App\Models\Article::where('slug', $article->slug)->where('id', '!=', $article->id)->exists()) {
                $article->slug = $originalSlug . '-' . $count;
                $count++;
            }
            $article->save();
        }

        // Make slug non-nullable after generating for all records
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'content']);
        });
    }
};
