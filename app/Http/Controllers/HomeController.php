<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Banner;
use App\Models\BannerCard;
use App\Models\Material;
use App\Models\Packet;

class HomeController extends Controller
{
    //
    public function index()
    {
        return view('landing', [
            'packets' => $this->getPacket(),
            'banner' => $this->getBanner(), // Maintain backward compatibility
            'bannerCards' => $this->getBannerCards(),
            'materials' => $this->getFeaturedMaterials(),
            'articles' => $this->getFeaturedArticles()
        ]);
    }

    /**
     * Get Packet data
     * 
     * @return \Illuminate\Database\Eloquent\Collection Returns all Packet records
     */
    public function getPacket()
    {
        $packets = Packet::all();

        // Process benefits to handle line breaks
        $packets->transform(function ($packet) {
            // Split benefits by line breaks and remove empty lines
            $benefits = preg_split('/\r\n|\r|\n/', $packet->benefit);
            $benefits = array_filter($benefits, function ($benefit) {
                return !empty(trim($benefit));
            });

            // Convert to array and store back
            $packet->benefits = array_values($benefits);

            return $packet;
        });

        return $packets;
    }

    public function getBanner()
    {
        $banner = Banner::first();
        return $banner;
    }

    /**
     * Get active banner cards ordered by display_order
     * 
     * @return \Illuminate\Database\Eloquent\Collection Returns active BannerCard records ordered by display_order
     */
    public function getBannerCards()
    {
        $bannerCards = BannerCard::active()->ordered()->get();
        return $bannerCards;
    }

    /**
     * Get materials for display on landing page
     * 
     * @return \Illuminate\Database\Eloquent\Collection Returns Material records
     */
    public function getFeaturedMaterials()
    {
        // Get up to 4 latest materials (since featured column was removed)
        $materials = Material::latest()->take(4)->get();
        return $materials;
    }

    /**
     * Get featured articles for display on landing page
     * 
     * @return \Illuminate\Database\Eloquent\Collection Returns featured Article records
     */
    public function getFeaturedArticles()
    {
        // Get exactly 3 articles with proper ordering - starred article first, then by ID
        $articles = Article::orderBy('is_starred', 'desc')
            ->orderBy('id', 'asc')
            ->take(3)
            ->get();

        // Ensure we always have exactly 3 articles for the landing page
        $articlesCount = $articles->count();
        if ($articlesCount < 3) {
            // Fill with placeholder articles if fewer than 3 exist
            for ($i = $articlesCount; $i < 3; $i++) {
                $articles->push(new Article([
                    'title' => 'Placeholder Article',
                    'source' => 'MLC',
                    'url' => '#',
                    'image' => 'placeholder.jpg',
                    'is_starred' => false
                ]));
            }
        }

        return $articles;
    }
}
