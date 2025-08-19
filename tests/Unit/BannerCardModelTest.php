<?php

namespace Tests\Unit;

use App\Models\BannerCard;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class BannerCardModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_banner_card_can_be_created_with_valid_data()
    {
        $bannerCard = BannerCard::create([
            'title' => 'Test Banner Card',
            'description' => 'This is a test banner card description',
            'background_image' => 'test-image.jpg',
            'display_order' => 1,
            'is_active' => true
        ]);

        $this->assertInstanceOf(BannerCard::class, $bannerCard);
        $this->assertEquals('Test Banner Card', $bannerCard->title);
        $this->assertEquals('This is a test banner card description', $bannerCard->description);
        $this->assertEquals('test-image.jpg', $bannerCard->background_image);
        $this->assertEquals(1, $bannerCard->display_order);
        $this->assertTrue($bannerCard->is_active);
    }

    public function test_is_active_is_cast_to_boolean()
    {
        $bannerCard = BannerCard::create([
            'title' => 'Test Banner Card',
            'description' => 'Test description',
            'background_image' => 'test-image.jpg',
            'display_order' => 1,
            'is_active' => 1
        ]);

        $this->assertIsBool($bannerCard->is_active);
        $this->assertTrue($bannerCard->is_active);
    }

    public function test_active_scope_returns_only_active_cards()
    {
        // Create active and inactive cards
        BannerCard::create([
            'title' => 'Active Card',
            'description' => 'Active description',
            'background_image' => 'active.jpg',
            'display_order' => 1,
            'is_active' => true
        ]);

        BannerCard::create([
            'title' => 'Inactive Card',
            'description' => 'Inactive description',
            'background_image' => 'inactive.jpg',
            'display_order' => 2,
            'is_active' => false
        ]);

        $activeCards = BannerCard::active()->get();

        $this->assertCount(1, $activeCards);
        $this->assertEquals('Active Card', $activeCards->first()->title);
    }

    public function test_ordered_scope_returns_cards_in_display_order()
    {
        // Create cards with different display orders
        BannerCard::create([
            'title' => 'Third Card',
            'description' => 'Third description',
            'background_image' => 'third.jpg',
            'display_order' => 3,
            'is_active' => true
        ]);

        BannerCard::create([
            'title' => 'First Card',
            'description' => 'First description',
            'background_image' => 'first.jpg',
            'display_order' => 1,
            'is_active' => true
        ]);

        BannerCard::create([
            'title' => 'Second Card',
            'description' => 'Second description',
            'background_image' => 'second.jpg',
            'display_order' => 2,
            'is_active' => true
        ]);

        $orderedCards = BannerCard::ordered()->get();

        $this->assertEquals('First Card', $orderedCards[0]->title);
        $this->assertEquals('Second Card', $orderedCards[1]->title);
        $this->assertEquals('Third Card', $orderedCards[2]->title);
    }

    public function test_active_and_ordered_scopes_can_be_combined()
    {
        // Create mixed active/inactive cards with different orders
        BannerCard::create([
            'title' => 'Active Third',
            'description' => 'Description',
            'background_image' => 'image.jpg',
            'display_order' => 3,
            'is_active' => true
        ]);

        BannerCard::create([
            'title' => 'Inactive First',
            'description' => 'Description',
            'background_image' => 'image.jpg',
            'display_order' => 1,
            'is_active' => false
        ]);

        BannerCard::create([
            'title' => 'Active Second',
            'description' => 'Description',
            'background_image' => 'image.jpg',
            'display_order' => 2,
            'is_active' => true
        ]);

        $cards = BannerCard::active()->ordered()->get();

        $this->assertCount(2, $cards);
        $this->assertEquals('Active Second', $cards[0]->title);
        $this->assertEquals('Active Third', $cards[1]->title);
    }

    public function test_validation_rules_require_all_fields()
    {
        $rules = BannerCard::validationRules();

        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('background_image', $rules);
        $this->assertArrayHasKey('display_order', $rules);
        $this->assertArrayHasKey('is_active', $rules);

        // Test that title is required
        $this->assertStringContainsString('required', $rules['title']);

        // Test that description is required
        $this->assertStringContainsString('required', $rules['description']);

        // Test that background_image is required
        $this->assertStringContainsString('required', $rules['background_image']);
    }

    public function test_image_validation_rules_require_valid_image()
    {
        $rules = BannerCard::imageValidationRules();

        $this->assertArrayHasKey('background_image', $rules);
        $this->assertStringContainsString('image', $rules['background_image']);
        $this->assertStringContainsString('mimes:jpg,jpeg,png', $rules['background_image']);
        $this->assertStringContainsString('max:2048', $rules['background_image']);
    }

    public function test_maximum_six_active_cards_business_rule_on_create()
    {
        // Create 6 active cards
        for ($i = 1; $i <= 6; $i++) {
            BannerCard::create([
                'title' => "Card $i",
                'description' => "Description $i",
                'background_image' => "image$i.jpg",
                'display_order' => $i,
                'is_active' => true
            ]);
        }

        // Try to create a 7th active card
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Maximum 6 active banner cards allowed');

        BannerCard::create([
            'title' => 'Card 7',
            'description' => 'Description 7',
            'background_image' => 'image7.jpg',
            'display_order' => 7,
            'is_active' => true
        ]);
    }

    public function test_can_create_inactive_card_when_six_active_exist()
    {
        // Create 6 active cards
        for ($i = 1; $i <= 6; $i++) {
            BannerCard::create([
                'title' => "Card $i",
                'description' => "Description $i",
                'background_image' => "image$i.jpg",
                'display_order' => $i,
                'is_active' => true
            ]);
        }

        // Should be able to create an inactive card
        $inactiveCard = BannerCard::create([
            'title' => 'Inactive Card',
            'description' => 'Inactive Description',
            'background_image' => 'inactive.jpg',
            'display_order' => 7,
            'is_active' => false
        ]);

        $this->assertInstanceOf(BannerCard::class, $inactiveCard);
        $this->assertFalse($inactiveCard->is_active);
    }

    public function test_maximum_six_active_cards_business_rule_on_update()
    {
        // Create 6 active cards
        for ($i = 1; $i <= 6; $i++) {
            BannerCard::create([
                'title' => "Card $i",
                'description' => "Description $i",
                'background_image' => "image$i.jpg",
                'display_order' => $i,
                'is_active' => true
            ]);
        }

        // Create an inactive card
        $inactiveCard = BannerCard::create([
            'title' => 'Inactive Card',
            'description' => 'Inactive Description',
            'background_image' => 'inactive.jpg',
            'display_order' => 7,
            'is_active' => false
        ]);

        // Try to activate the inactive card (should fail)
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Maximum 6 active banner cards allowed');

        $inactiveCard->update(['is_active' => true]);
    }

    public function test_can_update_active_card_without_changing_status()
    {
        $card = BannerCard::create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'background_image' => 'original.jpg',
            'display_order' => 1,
            'is_active' => true
        ]);

        // Should be able to update other fields without issue
        $card->update([
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ]);

        $this->assertEquals('Updated Title', $card->fresh()->title);
        $this->assertEquals('Updated Description', $card->fresh()->description);
    }
}
