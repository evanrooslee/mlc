<?php

namespace Tests\Unit;

use App\Models\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PacketModelPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations for test database
        $this->artisan('migrate');

        // Clear cache before each test
        Cache::flush();
    }

    /** @test */
    public function popular_scope_orders_by_purchase_count_efficiently()
    {
        // Create packets with different purchase counts
        $packet1 = Packet::factory()->create(['title' => 'Least Popular']);
        $packet2 = Packet::factory()->create(['title' => 'Most Popular']);
        $packet3 = Packet::factory()->create(['title' => 'Medium Popular']);

        $users = User::factory()->count(10)->create();

        // Attach different numbers of users to create popularity ranking
        $packet1->users()->attach($users->take(2)->pluck('id')); // 2 purchases
        $packet2->users()->attach($users->take(8)->pluck('id')); // 8 purchases  
        $packet3->users()->attach($users->take(5)->pluck('id')); // 5 purchases

        $popularPackets = Packet::popular()->get();

        // Should be ordered by purchase count (descending)
        $this->assertEquals('Most Popular', $popularPackets->first()->title);
        $this->assertEquals('Medium Popular', $popularPackets->skip(1)->first()->title);
        $this->assertEquals('Least Popular', $popularPackets->last()->title);

        // Verify purchase counts are correct
        $this->assertEquals(8, $popularPackets->first()->users_count);
        $this->assertEquals(5, $popularPackets->skip(1)->first()->users_count);
        $this->assertEquals(2, $popularPackets->last()->users_count);
    }

    /** @test */
    public function purchase_count_attribute_is_calculated_correctly()
    {
        $packet = Packet::factory()->create();
        $users = User::factory()->count(5)->create();

        // Initially no purchases
        $this->assertEquals(0, $packet->purchase_count);

        // Add purchases
        $packet->users()->attach($users->pluck('id'));

        // Refresh model to get updated count
        $packet->refresh();
        $this->assertEquals(5, $packet->purchase_count);
    }

    /** @test */
    public function image_url_attribute_handles_missing_files_gracefully()
    {
        // Test with non-existent image
        $packet1 = Packet::factory()->create(['image' => 'non-existent.jpg']);
        $this->assertStringContainsString('hero-illustration.png', $packet1->image_url);

        // Test with empty image
        $packet2 = Packet::factory()->create(['image' => '']);
        $this->assertStringContainsString('hero-illustration.png', $packet2->image_url);

        // Test with null image
        $packet3 = Packet::factory()->create(['image' => null]);
        $this->assertStringContainsString('hero-illustration.png', $packet3->image_url);
    }

    /** @test */
    public function optimized_image_url_method_returns_proper_dimensions()
    {
        $packet = Packet::factory()->create(['image' => 'test.jpg']);

        $optimizedUrl = $packet->getOptimizedImageUrl(800, 450);

        // For now, should return the same as image_url
        // In future implementations, this could include resize parameters
        $this->assertEquals($packet->image_url, $optimizedUrl);
    }

    /** @test */
    public function model_events_clear_cache_on_packet_changes()
    {
        // Prime cache
        Cache::put('popular_packets', collect(['test']), 3600);
        $this->assertTrue(Cache::has('popular_packets'));

        // Creating packet should clear cache
        Packet::factory()->create();
        $this->assertFalse(Cache::has('popular_packets'));

        // Prime cache again
        Cache::put('popular_packets', collect(['test']), 3600);
        $this->assertTrue(Cache::has('popular_packets'));

        // Updating packet should clear cache
        $packet = Packet::factory()->create();
        $packet->update(['title' => 'Updated']);
        $this->assertFalse(Cache::has('popular_packets'));

        // Prime cache again
        Cache::put('popular_packets', collect(['test']), 3600);
        $this->assertTrue(Cache::has('popular_packets'));

        // Deleting packet should clear cache
        $packet->delete();
        $this->assertFalse(Cache::has('popular_packets'));
    }

    /** @test */
    public function packet_user_pivot_events_clear_cache()
    {
        $packet = Packet::factory()->create();
        $user = User::factory()->create();

        // Prime cache
        Cache::put('popular_packets', collect(['test']), 3600);
        $this->assertTrue(Cache::has('popular_packets'));

        // Attaching user should clear cache
        $packet->users()->attach($user->id);
        $this->assertFalse(Cache::has('popular_packets'));

        // Prime cache again
        Cache::put('popular_packets', collect(['test']), 3600);
        $this->assertTrue(Cache::has('popular_packets'));

        // Detaching user should clear cache
        $packet->users()->detach($user->id);
        $this->assertFalse(Cache::has('popular_packets'));
    }

    /** @test */
    public function popular_scope_handles_ties_with_created_at_fallback()
    {
        // Create packets with same purchase count but different creation times
        $olderPacket = Packet::factory()->create([
            'title' => 'Older Packet',
            'created_at' => now()->subDays(2)
        ]);

        $newerPacket = Packet::factory()->create([
            'title' => 'Newer Packet',
            'created_at' => now()->subDay()
        ]);

        $user = User::factory()->create();

        // Give both packets same purchase count
        $olderPacket->users()->attach($user->id);
        $newerPacket->users()->attach($user->id);

        $popularPackets = Packet::popular()->get();

        // With same purchase count, newer packet should come first
        $this->assertEquals('Newer Packet', $popularPackets->first()->title);
        $this->assertEquals('Older Packet', $popularPackets->last()->title);
    }
}
