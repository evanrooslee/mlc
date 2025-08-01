<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutingIntegrationManualTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_routing_flow_from_profile_to_payment()
    {
        // Create a student user
        $user = User::factory()->create([
            'role' => 'student',
            'name' => 'Test Student',
            'email' => 'student@test.com'
        ]);

        // Create a test packet
        $packet = Packet::factory()->create([
            'title' => 'Test Mathematics Class',
            'price' => 100000,
            'benefit' => 'Video lessons|Practice tests|Certificate',
            'grade' => 10,
            'subject' => 'Mathematics'
        ]);

        // Step 1: Access profile page
        $profileResponse = $this->actingAs($user)
            ->get(route('user.profile'));

        $profileResponse->assertStatus(200);
        $profileResponse->assertSee('Kelas Populer');

        // Step 2: Verify the "Lihat Detail" link is correctly generated
        $profileResponse->assertSee('href="' . route('beli-paket.show', $packet->id) . '"', false);

        // Step 3: Follow the link to the payment page
        $paymentResponse = $this->actingAs($user)
            ->get(route('beli-paket.show', $packet->id));

        $paymentResponse->assertStatus(200);
        $paymentResponse->assertViewIs('beli-paket');

        // Step 4: Verify packet information is correctly displayed
        $paymentResponse->assertSee($packet->title);
        $paymentResponse->assertSee('Rp' . number_format($packet->price, 0, ',', '.'));
        $paymentResponse->assertSee('Video lessons');
        $paymentResponse->assertSee('Practice tests');
        $paymentResponse->assertSee('Certificate');

        // Step 5: Verify form contains correct packet ID
        $paymentResponse->assertSee('name="packet_id"', false);
        $paymentResponse->assertSee('value="' . $packet->id . '"', false);

        // Step 6: Verify the form action points to the correct route
        $paymentResponse->assertSee('action="' . route('proses-pembayaran') . '"', false);

        $this->assertTrue(true, 'Complete routing flow test passed successfully');
    }

    public function test_route_helper_generates_correct_urls()
    {
        $packet = Packet::factory()->create();

        // Test route helper generates correct URL
        $expectedUrl = url('/beli-paket/' . $packet->id);
        $actualUrl = route('beli-paket.show', $packet->id);

        $this->assertEquals($expectedUrl, $actualUrl);

        // Test that the route can be resolved
        $this->assertTrue(
            \Illuminate\Support\Facades\Route::has('beli-paket.show'),
            'Route beli-paket.show should exist'
        );
    }

    public function test_error_handling_for_routing_edge_cases()
    {
        $user = User::factory()->create(['role' => 'student']);

        // Test with string packet ID
        $response = $this->actingAs($user)->get('/beli-paket/abc');
        $this->assertEquals(404, $response->status());

        // Test with negative packet ID
        $response = $this->actingAs($user)->get('/beli-paket/-1');
        $this->assertEquals(404, $response->status());

        // Test with zero packet ID
        $response = $this->actingAs($user)->get('/beli-paket/0');
        $this->assertEquals(404, $response->status());

        // Test with very large packet ID
        $response = $this->actingAs($user)->get('/beli-paket/999999999');
        $this->assertEquals(404, $response->status());
    }
}
