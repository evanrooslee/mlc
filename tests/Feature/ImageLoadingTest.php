<?php

namespace Tests\Feature;

use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageLoadingTest extends TestCase
{
    use RefreshDatabase;

    public function test_packet_image_url_attribute_works_correctly()
    {
        // Test 1: Packet with empty image should return fallback
        $packet1 = Packet::factory()->create(['image' => '']);
        $this->assertEquals(asset('images/hero-illustration.png'), $packet1->image_url);

        // Test 2: Packet with non-existent image should return fallback
        $packet2 = Packet::factory()->create(['image' => 'images/non-existent.jpg']);
        $this->assertEquals(asset('images/hero-illustration.png'), $packet2->image_url);

        // Test 3: Create a fake image file and test
        Storage::fake('public');
        $fakeImage = UploadedFile::fake()->image('test.jpg');
        $path = $fakeImage->storeAs('images', 'test-packet.jpg', 'public');

        $packet3 = Packet::factory()->create(['image' => $path]);
        $expectedUrl = asset('storage/' . $path);

        // Since we're using fake storage, the file won't actually exist in the real filesystem
        // So it should still return the fallback
        $this->assertEquals(asset('images/hero-illustration.png'), $packet3->image_url);

        echo "Test 1 - No image: " . $packet1->image_url . "\n";
        echo "Test 2 - Non-existent image: " . $packet2->image_url . "\n";
        echo "Test 3 - Fake image: " . $packet3->image_url . "\n";
        echo "Expected URL for test 3: " . $expectedUrl . "\n";
    }

    public function test_landing_page_uses_image_url_attribute()
    {
        // Create a packet
        $packet = Packet::factory()->create([
            'title' => 'Test Packet',
            'image' => 'images/test.jpg'
        ]);

        // Visit landing page
        $response = $this->get('/');

        $response->assertStatus(200);

        // The page should contain the fallback image URL since the file doesn't exist
        $response->assertSee(asset('images/hero-illustration.png'));
    }

    public function test_profile_page_uses_image_url_attribute()
    {
        // Create a student user
        $student = \App\Models\User::factory()->create(['role' => 'student']);

        // Create a packet
        $packet = Packet::factory()->create([
            'title' => 'Test Packet',
            'image' => 'images/test.jpg'
        ]);

        // Visit profile page as student
        $this->actingAs($student);
        $response = $this->get(route('user.profile'));

        $response->assertStatus(200);

        // Should use image_url attribute
        $response->assertSee('hero-illustration.png');
    }

    public function test_kelas_page_uses_image_url_attribute()
    {
        // Create a student user
        $student = \App\Models\User::factory()->create(['role' => 'student']);

        // Create packets
        $activePacket = Packet::factory()->create([
            'title' => 'Active Packet',
            'image' => 'images/active.jpg'
        ]);

        $otherPacket = Packet::factory()->create([
            'title' => 'Other Packet',
            'image' => 'images/other.jpg'
        ]);

        // Make one packet active for the student
        $student->packets()->attach($activePacket->id);

        // Visit kelas page as student
        $this->actingAs($student);
        $response = $this->get(route('user.kelas'));

        $response->assertStatus(200);

        // Should use image_url attribute for both active and other packets
        $response->assertSee('Active Packet');
        $response->assertSee('Other Packet');
        $response->assertSee('hero-illustration.png');
    }
}
