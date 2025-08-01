<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class PacketModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_default_image_when_no_image_is_set()
    {
        // Create packet with empty image field (since it's required in DB)
        $packet = Packet::factory()->create(['image' => '']);

        $imageUrl = $packet->image_url;

        $this->assertStringContainsString('hero-illustration.png', $imageUrl);
    }

    /** @test */
    public function it_returns_default_image_when_image_file_does_not_exist()
    {
        // Create packet with non-existent image path
        $packet = Packet::factory()->create(['image' => 'non-existent-image.jpg']);

        $imageUrl = $packet->image_url;

        // Should fallback to default image since file doesn't exist
        $this->assertStringContainsString('hero-illustration.png', $imageUrl);
    }

    /** @test */
    public function it_returns_storage_url_when_image_file_exists()
    {
        // Create a real temporary file for testing
        $tempDir = storage_path('app/public');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $testImagePath = $tempDir . '/test-image.jpg';
        file_put_contents($testImagePath, 'fake image content');

        $packet = Packet::factory()->create(['image' => 'test-image.jpg']);

        $imageUrl = $packet->image_url;

        $this->assertStringContainsString('storage/test-image.jpg', $imageUrl);

        // Clean up
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
        }
    }

    /** @test */
    public function it_calculates_purchase_count_correctly()
    {
        $packet = Packet::factory()->create();
        $users = User::factory()->count(3)->create();

        // Attach users to packet (simulating purchases)
        $packet->users()->attach($users);

        $this->assertEquals(3, $packet->purchase_count);
    }

    /** @test */
    public function it_returns_zero_purchase_count_when_no_users_attached()
    {
        $packet = Packet::factory()->create();

        $this->assertEquals(0, $packet->purchase_count);
    }

    /** @test */
    public function popular_scope_orders_by_purchase_count_desc()
    {
        // Create packets with different purchase counts
        $packet1 = Packet::factory()->create(['title' => 'Least Popular']);
        $packet2 = Packet::factory()->create(['title' => 'Most Popular']);
        $packet3 = Packet::factory()->create(['title' => 'Moderately Popular']);

        $users = User::factory()->count(5)->create();

        // Attach different numbers of users
        $packet1->users()->attach($users->take(1)); // 1 purchase
        $packet2->users()->attach($users->take(3)); // 3 purchases
        $packet3->users()->attach($users->take(2)); // 2 purchases

        $popularPackets = Packet::popular()->get();

        $this->assertEquals('Most Popular', $popularPackets->first()->title);
        $this->assertEquals('Moderately Popular', $popularPackets->get(1)->title);
        $this->assertEquals('Least Popular', $popularPackets->last()->title);
    }

    /** @test */
    public function popular_scope_falls_back_to_creation_date_when_purchase_counts_equal()
    {
        // Create packets with same purchase count but different creation dates
        $olderPacket = Packet::factory()->create([
            'title' => 'Older Packet',
            'created_at' => now()->subDays(2)
        ]);

        $newerPacket = Packet::factory()->create([
            'title' => 'Newer Packet',
            'created_at' => now()->subDay()
        ]);

        // Both have 0 purchases (equal count)
        $popularPackets = Packet::popular()->get();

        // Newer packet should come first
        $this->assertEquals('Newer Packet', $popularPackets->first()->title);
        $this->assertEquals('Older Packet', $popularPackets->last()->title);
    }

    /** @test */
    public function it_handles_empty_database_gracefully()
    {
        // Ensure no packets exist
        Packet::query()->delete();

        $popularPackets = Packet::popular()->get();

        $this->assertTrue($popularPackets->isEmpty());
        $this->assertEquals(0, $popularPackets->count());
    }
}
