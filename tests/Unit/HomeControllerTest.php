<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\HomeController;
use App\Models\BannerCard;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_banner_cards_returns_active_ordered_cards()
    {
        // Create test banner cards
        BannerCard::create([
            'title' => 'Test Card 1',
            'description' => 'Test description 1',
            'background_image' => 'test1.jpg',
            'display_order' => 2,
            'is_active' => true
        ]);

        BannerCard::create([
            'title' => 'Test Card 2',
            'description' => 'Test description 2',
            'background_image' => 'test2.jpg',
            'display_order' => 1,
            'is_active' => true
        ]);

        BannerCard::create([
            'title' => 'Inactive Card',
            'description' => 'Inactive description',
            'background_image' => 'inactive.jpg',
            'display_order' => 3,
            'is_active' => false
        ]);

        $controller = new HomeController();
        $bannerCards = $controller->getBannerCards();

        // Should return only active cards, ordered by display_order
        $this->assertCount(2, $bannerCards);
        $this->assertEquals('Test Card 2', $bannerCards->first()->title);
        $this->assertEquals('Test Card 1', $bannerCards->last()->title);
    }

    public function test_index_method_includes_banner_cards()
    {
        // Create a test banner card
        BannerCard::create([
            'title' => 'Test Card',
            'description' => 'Test description',
            'background_image' => 'test.jpg',
            'display_order' => 1,
            'is_active' => true
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('bannerCards');
    }
}
