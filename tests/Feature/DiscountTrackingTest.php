<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_track_discount_click_for_guest_user()
    {
        $response = $this->postJson('/track-discount-click', [
            'phone_number' => '081234567890'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Click tracked successfully'
            ]);

        $this->assertDatabaseHas('discount_clicks', [
            'phone_number' => '081234567890',
            'user_id' => null,
            'user_name' => null
        ]);
    }

    public function test_can_track_discount_click_for_authenticated_user()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        $response = $this->actingAs($user)->postJson('/track-discount-click', [
            'phone_number' => '081234567890'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Click tracked successfully'
            ]);

        $this->assertDatabaseHas('discount_clicks', [
            'phone_number' => '081234567890',
            'user_id' => $user->id,
            'user_name' => 'Test User'
        ]);
    }

    public function test_validates_phone_number_required()
    {
        $response = $this->postJson('/track-discount-click', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone_number']);
    }

    public function test_validates_phone_number_format()
    {
        $response = $this->postJson('/track-discount-click', [
            'phone_number' => 'invalid-phone'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone_number']);
    }

    public function test_accepts_valid_indonesian_phone_formats()
    {
        $validPhones = [
            '081234567890',
            '6281234567890',
            '+6281234567890',
            '021234567890'
        ];

        foreach ($validPhones as $phone) {
            $response = $this->postJson('/track-discount-click', [
                'phone_number' => $phone
            ]);

            $response->assertStatus(200);
        }
    }
}
