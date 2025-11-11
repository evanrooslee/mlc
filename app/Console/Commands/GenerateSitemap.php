<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.xml file for SEO';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = \Spatie\Sitemap\Sitemap::create();

        // Add homepage
        $sitemap->add(
            \Spatie\Sitemap\Tags\Url::create(route('home'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        // Add packet detail pages (all public packets)
        \App\Models\Packet::all()->each(function ($packet) use ($sitemap) {
            $sitemap->add(
                \Spatie\Sitemap\Tags\Url::create(route('beli-paket.show', ['packet' => $packet->slug]))
                    ->setLastModificationDate($packet->updated_at)
                    ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        });

        // Add artikel listing page
        $sitemap->add(
            \Spatie\Sitemap\Tags\Url::create(route('artikel.index'))
                ->setLastModificationDate(now())
                ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.7)
        );

        // Add article detail pages
        \App\Models\Article::all()->each(function ($article) use ($sitemap) {
            $sitemap->add(
                \Spatie\Sitemap\Tags\Url::create(route('artikel.show', ['article' => $article->slug]))
                    ->setLastModificationDate($article->updated_at)
                    ->setChangeFrequency(\Spatie\Sitemap\Tags\Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.6)
            );
        });

        // Write sitemap to public directory
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully at: ' . public_path('sitemap.xml'));
        
        return Command::SUCCESS;
    }
}
