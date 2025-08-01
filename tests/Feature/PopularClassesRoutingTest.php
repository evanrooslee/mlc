<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PopularClassesRoutingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user with student role
        $this->user = User::factory()->create([
            'role' => 'student',
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'phone_number' => '081234567890',
            'parents_phone_number' => '081234567891',
            'school' => 'Test School',
            'grade' => 10
        ]);
    }

    /** @test */
    public function it_can_access_profile_page_with_popular_packets()
    {
        // Create test packets
        $packets = Packet::factory()->count(3)->create();

        // Create some purchase relationships to make packets popular
        foreach ($packets as $packet) {
            $packet->users()->attach(User::factory()->count(rand(1, 5))->create());
        }

        $response = $this->actingAs($this->user)
            ->get(route('user.profile'));

        $response->assertStatus(200);
        $response->assertViewIs('user.profile');
        $response->assertViewHas('popularPackets');
    }

    /** @test */
    public function lihat_detail_button_routes_to_correct_packet_page()
    {
        // Create a test packet
        $packet = Packet::factory()->create([
            'title' => 'Test Packet',
            'price' => 100000,
            'benefit' => 'Test benefit 1|Test benefit 2|Test benefit 3'
        ]);

        // Test the route with valid packet ID
        $response = $this->actingAs($this->user)
            ->get(route('beli-paket.show', $packet->id));

        $response->assertStatus(200);
        $response->assertViewIs('beli-paket');
        $response->assertViewHas('packet');
        $response->assertSee($packet->title);
    }

    /** @test */
    public function it_handles_invalid_packet_id_gracefully()
    {
        // Test with non-existent packet ID
        $response = $this->actingAs($this->user)
            ->get(route('beli-paket.show', 99999));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_handles_non_numeric_packet_id()
    {
        // Test with non-numeric packet ID
        $response = $this->actingAs($this->user)
            ->get('/beli-paket/invalid-id');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_handles_negative_packet_id()
    {
        // Test with negative packet ID
        $response = $this->actingAs($this->user)
            ->get('/beli-paket/-1');

        $response->assertStatus(404);
    }

    /** @test */
    public function payment_page_receives_correct_packet_information()
    {
        // Create a test packet with specific data
        $packet = Packet::factory()->create([
            'title' => 'Mathematics Class',
            'price' => 150000,
            'benefit' => 'Video lessons|Practice tests|Certificate',
            'grade' => 10,
            'subject' => 'Mathematics',
            'type' => 'premium'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('beli-paket.show', $packet->id));

        $response->assertStatus(200);

        // Check that packet information is correctly displayed
        $response->assertSee($packet->title);
        $response->assertSee('Rp' . number_format($packet->price, 0, ',', '.'));
        $response->assertSee('Video lessons');
        $response->assertSee('Practice tests');
        $response->assertSee('Certificate');
        $response->assertSee('Kelas ' . $packet->grade);
        $response->assertSee($packet->subject);
        $response->assertSee('Premium');
    }

    /** @test */
    public function profile_page_displays_packets_ordered_by_popularity()
    {
        // Create packets with different popularity levels
        $packet1 = Packet::factory()->create(['title' => 'Less Popular Packet']);
        $packet2 = Packet::factory()->create(['title' => 'Most Popular Packet']);
        $packet3 = Packet::factory()->create(['title' => 'Moderately Popular Packet']);

        // Create different numbers of purchases for each packet
        $packet1->users()->attach(User::factory()->count(2)->create()); // 2 purchases
        $packet2->users()->attach(User::factory()->count(5)->create()); // 5 purchases
        $packet3->users()->attach(User::factory()->count(3)->create()); // 3 purchases

        $response = $this->actingAs($this->user)
            ->get(route('user.profile'));

        $response->assertStatus(200);

        // Get the popular packets from the view data
        $popularPackets = $response->viewData('popularPackets');

        // Assert packets are ordered by popularity (most purchases first)
        $this->assertEquals('Most Popular Packet', $popularPackets->first()->title);
        $this->assertEquals('Moderately Popular Packet', $popularPackets->skip(1)->first()->title);
        $this->assertEquals('Less Popular Packet', $popularPackets->skip(2)->first()->title);
    }

    /** @test */
    public function profile_page_handles_empty_popular_packets()
    {
        // Don't create any packets
        $response = $this->actingAs($this->user)
            ->get(route('user.profile'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada kelas populer tersedia');
        $response->assertSee('Kelas populer akan muncul setelah ada pembelian paket');
    }

    /** @test */
    public function route_parameter_passing_works_correctly()
    {
        $packet = Packet::factory()->create();

        // Test that the route helper generates correct URL
        $expectedUrl = url('/beli-paket/' . $packet->id);
        $actualUrl = route('beli-paket.show', $packet->id);

        $this->assertEquals($expectedUrl, $actualUrl);
    }

    /** @test */
    public function payment_form_contains_correct_packet_id()
    {
        $packet = Packet::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('beli-paket.show', $packet->id));

        $response->assertStatus(200);

        // Check that the form contains the correct packet ID
        $response->assertSee('name="packet_id"', false);
        $response->assertSee('value="' . $packet->id . '"', false);
    }

    /** @test */
    public function unauthorized_user_cannot_access_packet_purchase_page()
    {
        $packet = Packet::factory()->create();

        // Test without authentication
        $response = $this->get(route('beli-paket.show', $packet->id));

        // Should redirect to login or return 401/403
        $this->assertTrue(
            $response->status() === 302 ||
                $response->status() === 401 ||
                $response->status() === 403
        );
    }

    /** @test */
    public function admin_user_cannot_access_packet_purchase_page()
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $packet = Packet::factory()->create();

        $response = $this->actingAs($adminUser)
            ->get(route('beli-paket.show', $packet->id));

        // Should be forbidden for admin users
        $this->assertTrue(
            $response->status() === 302 ||
                $response->status() === 403
        );
    }
}
