<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_view_profile_with_popular_packets()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create some packets with different popularity
        $users = User::factory()->count(3)->create();
        $popularPacket = Packet::factory()->create(['title' => 'Popular Packet']);
        $lessPopularPacket = Packet::factory()->create(['title' => 'Less Popular Packet']);

        // Make the first packet more popular
        $popularPacket->users()->attach($users);
        $lessPopularPacket->users()->attach($users->take(1));

        // Make request to profile page
        $response = $this->get(route('user.profile'));

        // Assert successful response
        $response->assertStatus(200);
        $response->assertViewIs('user.profile');
        $response->assertViewHas('popularPackets');

        // Assert popular packets are passed to view
        $popularPackets = $response->viewData('popularPackets');
        $this->assertNotEmpty($popularPackets);

        // Assert most popular packet comes first
        $this->assertEquals('Popular Packet', $popularPackets->first()->title);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_profile()
    {
        // Make request without authentication
        $response = $this->get(route('user.profile'));

        // Should return 403 or redirect to login depending on middleware configuration
        $this->assertTrue(
            $response->status() === 403 || $response->isRedirect(),
            'Expected 403 status or redirect, got ' . $response->status()
        );
    }

    /** @test */
    public function profile_page_displays_empty_state_when_no_packets_exist()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Ensure no packets exist
        Packet::query()->delete();

        // Make request to profile page
        $response = $this->get(route('user.profile'));

        // Assert successful response
        $response->assertStatus(200);
        $response->assertViewIs('user.profile');

        // Assert empty state message is displayed
        $response->assertSee('Belum ada kelas populer tersedia');
        $response->assertSee('Kelas populer akan muncul setelah ada pembelian paket');
    }

    /** @test */
    public function profile_page_handles_packets_with_missing_images()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create packet with empty image (since null is not allowed)
        $packet = Packet::factory()->create([
            'title' => 'Packet Without Image',
            'image' => ''
        ]);

        // Make request to profile page
        $response = $this->get(route('user.profile'));

        // Assert successful response
        $response->assertStatus(200);
        $response->assertSee('Packet Without Image');

        // Assert default image fallback is used in JavaScript
        $response->assertSee('hero-illustration.png');
    }

    /** @test */
    public function profile_page_includes_noscript_fallback()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create multiple packets
        $packets = Packet::factory()->count(3)->create();

        // Make request to profile page
        $response = $this->get(route('user.profile'));

        // Assert successful response
        $response->assertStatus(200);

        // Assert noscript fallback is present
        $response->assertSee('<noscript>', false);
        $response->assertSee('JavaScript dinonaktifkan');
        $response->assertSee('Menampilkan semua kelas populer');
    }

    /** @test */
    public function profile_page_includes_error_handling_javascript()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create packets
        Packet::factory()->count(2)->create();

        // Make request to profile page
        $response = $this->get(route('user.profile'));

        // Assert successful response
        $response->assertStatus(200);

        // Assert error handling functions are present
        $response->assertSee('handleImageError');
        $response->assertSee('handleImageLoad');
        $response->assertSee('showError');
    }

    /** @test */
    public function profile_page_handles_packets_with_missing_titles()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create packet with empty title (since null is not allowed)
        $packet = Packet::factory()->create(['title' => '']);

        // Make request to profile page
        $response = $this->get(route('user.profile'));

        // Assert successful response
        $response->assertStatus(200);

        // Assert fallback title is used
        $response->assertSee('Untitled Packet');
    }
}
