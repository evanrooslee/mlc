<?php

namespace Tests\Feature;

use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_redirected_to_login_when_accessing_beli_paket()
    {
        // Create a test packet
        $packet = Packet::factory()->create();

        // Try to access the beli-paket page as a guest
        $response = $this->get(route('beli-paket.show', $packet->id));

        // Should redirect to login
        $response->assertRedirect(route('login'));

        // Should have the message in session
        $response->assertSessionHas('message', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
    }

    public function test_guest_user_redirected_to_login_when_accessing_user_profile()
    {
        // Try to access user profile as a guest
        $response = $this->get(route('user.profile'));

        // Should redirect to login
        $response->assertRedirect(route('login'));

        // Should have the message in session
        $response->assertSessionHas('message', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
    }
}
