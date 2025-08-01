<?php

namespace Tests\Feature;

use App\Models\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PopularPacketsPerformanceTest extends TestCase
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
    public function popular_packets_query_uses_database_indexes_efficiently()
    {
        // Create test data
        $packets = Packet::factory()->count(10)->create();
        $users = User::factory()->count(20)->create();

        // Create purchase relationships
        foreach ($packets as $index => $packet) {
            // Create varying purchase counts (more purchases for lower index)
            $purchaseCount = 10 - $index;
            $randomUsers = $users->random($purchaseCount);
            $packet->users()->attach($randomUsers->pluck('id'));
        }

        // Enable query logging
        DB::enableQueryLog();

        // Execute the popular packets query
        $popularPackets = Packet::popular()->get();

        // Get executed queries
        $queries = DB::getQueryLog();

        // Verify the query is optimized
        $this->assertCount(1, $queries, 'Should execute only one query');

        $mainQuery = $queries[0]['query'];

        // Verify the query uses proper joins and ordering
        $this->assertStringContainsString('left join `packet_user`', $mainQuery);
        $this->assertStringContainsString('order by `users_count` desc', $mainQuery);
        $this->assertStringContainsString('order by `created_at` desc', $mainQuery);

        // Verify results are properly ordered by popularity
        $this->assertEquals($packets[0]->id, $popularPackets->first()->id);
        $this->assertTrue($popularPackets->first()->users_count >= $popularPackets->last()->users_count);

        DB::disableQueryLog();
    }

    /** @test */
    public function popular_packets_are_cached_properly()
    {
        // Create test data
        $packets = Packet::factory()->count(5)->create();
        $user = User::factory()->create();

        // Attach user to packets
        $packets->each(function ($packet) use ($user) {
            $packet->users()->attach($user->id);
        });

        // First request should hit database and cache result
        $this->actingAs($user);

        DB::enableQueryLog();
        $response1 = $this->get(route('user.profile'));
        $queries1 = DB::getQueryLog();
        DB::disableQueryLog();

        $response1->assertStatus(200);

        // Verify cache was set
        $this->assertTrue(Cache::has('popular_packets'));

        // Second request should use cache (no database queries for packets)
        DB::enableQueryLog();
        $response2 = $this->get(route('user.profile'));
        $queries2 = DB::getQueryLog();
        DB::disableQueryLog();

        $response2->assertStatus(200);

        // Should have fewer queries on second request (cached)
        $this->assertLessThan(count($queries1), count($queries2));
    }

    /** @test */
    public function cache_is_invalidated_when_packet_is_updated()
    {
        $packet = Packet::factory()->create();
        $user = User::factory()->create();

        // Prime the cache
        $this->actingAs($user);
        $this->get(route('user.profile'));
        $this->assertTrue(Cache::has('popular_packets'));

        // Update packet should clear cache
        $packet->update(['title' => 'Updated Title']);
        $this->assertFalse(Cache::has('popular_packets'));
    }

    /** @test */
    public function cache_is_invalidated_when_purchase_relationship_changes()
    {
        $packet = Packet::factory()->create();
        $user = User::factory()->create();

        // Prime the cache
        $this->actingAs($user);
        $this->get(route('user.profile'));
        $this->assertTrue(Cache::has('popular_packets'));

        // Adding purchase relationship should clear cache
        $packet->users()->attach($user->id);
        $this->assertFalse(Cache::has('popular_packets'));

        // Prime cache again
        $this->get(route('user.profile'));
        $this->assertTrue(Cache::has('popular_packets'));

        // Removing purchase relationship should clear cache
        $packet->users()->detach($user->id);
        $this->assertFalse(Cache::has('popular_packets'));
    }

    /** @test */
    public function profile_page_handles_large_number_of_packets_efficiently()
    {
        // Create a large number of packets and users
        $packets = Packet::factory()->count(100)->create();
        $users = User::factory()->count(50)->create();

        // Create random purchase relationships
        foreach ($packets as $packet) {
            $randomUsers = $users->random(rand(1, 10));
            $packet->users()->attach($randomUsers->pluck('id'));
        }

        $testUser = $users->first();
        $this->actingAs($testUser);

        // Measure execution time
        $startTime = microtime(true);

        $response = $this->get(route('user.profile'));

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        $response->assertStatus(200);

        // Should complete within reasonable time (2 seconds)
        $this->assertLessThan(2.0, $executionTime, 'Profile page should load within 2 seconds');
    }

    /** @test */
    public function image_urls_are_generated_efficiently()
    {
        $packets = Packet::factory()->count(10)->create([
            'image' => 'test-image.jpg'
        ]);

        // Test image URL generation performance
        $startTime = microtime(true);

        foreach ($packets as $packet) {
            $imageUrl = $packet->image_url;
            $this->assertNotEmpty($imageUrl);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // Should be very fast (under 0.1 seconds for 10 packets)
        $this->assertLessThan(0.1, $executionTime, 'Image URL generation should be fast');
    }

    /** @test */
    public function popular_packets_scope_performance_with_no_purchases()
    {
        // Create packets without any purchases
        Packet::factory()->count(20)->create();

        DB::enableQueryLog();

        $popularPackets = Packet::popular()->get();

        $queries = DB::getQueryLog();
        DB::disableQueryLog();

        // Should still execute efficiently with no purchases
        $this->assertCount(1, $queries);
        $this->assertCount(20, $popularPackets);

        // Should be ordered by created_at when no purchases exist
        $this->assertTrue(
            $popularPackets->first()->created_at >= $popularPackets->last()->created_at
        );
    }

    /** @test */
    public function cache_fallback_works_when_database_fails()
    {
        $packets = Packet::factory()->count(3)->create();
        $user = User::factory()->create();

        // Prime the cache
        $this->actingAs($user);
        $this->get(route('user.profile'));
        $this->assertTrue(Cache::has('popular_packets'));

        // Simulate database failure by using invalid connection
        config(['database.default' => 'invalid']);

        // Should still work with cached data
        $response = $this->get(route('user.profile'));
        $response->assertStatus(200);

        // Reset database connection
        config(['database.default' => 'testing']);
    }
}
