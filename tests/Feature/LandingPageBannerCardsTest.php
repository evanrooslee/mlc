<?php

namespace Tests\Feature;

use App\Models\BannerCard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageBannerCardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_displays_banner_cards()
    {
        // Create some test banner cards
        $card1 = BannerCard::factory()->create([
            'title' => 'Test Card 1',
            'description' => 'Test description 1',
            'display_order' => 1,
            'is_active' => true
        ]);

        $card2 = BannerCard::factory()->create([
            'title' => 'Test Card 2',
            'description' => 'Test description 2',
            'display_order' => 2,
            'is_active' => true
        ]);

        // Visit the landing page
        $response = $this->get('/');

        $response->assertStatus(200);

        // Check that banner cards are displayed
        $response->assertSee('Test Card 1');
        $response->assertSee('Test Card 2');
        $response->assertSee('Test description 1');
        $response->assertSee('Test description 2');

        // Check for banner cards section
        $response->assertSee('Banner Cards Section');
    }

    public function test_landing_page_displays_fallback_when_no_banner_cards()
    {
        // Don't create any banner cards

        // Visit the landing page
        $response = $this->get('/');

        $response->assertStatus(200);

        // Check that fallback content is displayed
        $response->assertSee('Segera Hadir!');
        $response->assertSee('Banner cards akan segera tersedia');
    }

    public function test_landing_page_displays_emojis_for_banner_cards()
    {
        // Create banner cards with different display orders
        BannerCard::factory()->create([
            'title' => 'Regular Class',
            'display_order' => 1,
            'is_active' => true
        ]);

        BannerCard::factory()->create([
            'title' => 'Try Out Center',
            'display_order' => 2,
            'is_active' => true
        ]);

        // Visit the landing page
        $response = $this->get('/');

        $response->assertStatus(200);

        // Check that emojis are present (they should be in the HTML)
        $response->assertSee('📚'); // Regular Class emoji
        $response->assertSee('📝'); // Try Out Center emoji
    }

    public function test_landing_page_only_displays_active_banner_cards()
    {
        // Create active and inactive banner cards
        $activeCard = BannerCard::factory()->create([
            'title' => 'Active Card',
            'display_order' => 1,
            'is_active' => true
        ]);

        $inactiveCard = BannerCard::factory()->create([
            'title' => 'Inactive Card',
            'display_order' => 2,
            'is_active' => false
        ]);

        // Visit the landing page
        $response = $this->get('/');

        $response->assertStatus(200);

        // Check that only active card is displayed
        $response->assertSee('Active Card');
        $response->assertDontSee('Inactive Card');
    }

    public function test_banner_cards_are_ordered_by_display_order()
    {
        // Create banner cards with different display orders
        $card3 = BannerCard::factory()->create([
            'title' => 'Third Card',
            'display_order' => 3,
            'is_active' => true
        ]);

        $card1 = BannerCard::factory()->create([
            'title' => 'First Card',
            'display_order' => 1,
            'is_active' => true
        ]);

        $card2 = BannerCard::factory()->create([
            'title' => 'Second Card',
            'display_order' => 2,
            'is_active' => true
        ]);

        // Visit the landing page
        $response = $this->get('/');

        $response->assertStatus(200);

        // Get the response content
        $content = $response->getContent();

        // Check that cards appear in the correct order
        $firstCardPos = strpos($content, 'First Card');
        $secondCardPos = strpos($content, 'Second Card');
        $thirdCardPos = strpos($content, 'Third Card');

        $this->assertLessThan($secondCardPos, $firstCardPos);
        $this->assertLessThan($thirdCardPos, $secondCardPos);
    }
}
