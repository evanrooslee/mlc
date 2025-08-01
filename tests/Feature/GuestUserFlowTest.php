<?php

namespace Tests\Feature;

use App\Models\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestUserFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_guest_to_authenticated_user_flow()
    {
        // Create a test packet
        $packet = Packet::factory()->create();

        // Step 1: Guest tries to access beli-paket page
        $response = $this->get(route('beli-paket.show', $packet->id));

        // Should redirect to login with message
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('message', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');

        // Step 2: Follow the redirect to login page
        $loginResponse = $this->followRedirects($response);
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('Silakan login terlebih dahulu untuk mengakses halaman ini.');

        // Step 3: Create a user and login
        $user = User::factory()->create([
            'role' => 'student',
            'phone_number' => '081234567890',
            'password' => bcrypt('password123')
        ]);

        // Step 4: Login with valid credentials
        $loginAttempt = $this->post(route('login.authenticate'), [
            'phone_number' => '081234567890',
            'password' => 'password123'
        ]);

        // Should redirect after successful login
        $loginAttempt->assertRedirect();

        // Step 5: Now try to access the beli-paket page as authenticated user
        $this->actingAs($user);
        $authenticatedResponse = $this->get(route('beli-paket.show', $packet->id));

        // Should now be able to access the page
        $authenticatedResponse->assertStatus(200);
        $authenticatedResponse->assertSee('Detail Pemesanan');
        $authenticatedResponse->assertSee($packet->title);
    }

    public function test_authenticated_user_with_wrong_role_gets_403()
    {
        // Create a test packet
        $packet = Packet::factory()->create();

        // Create an admin user (wrong role for beli-paket)
        $adminUser = User::factory()->create([
            'role' => 'admin'
        ]);

        // Try to access beli-paket as admin
        $this->actingAs($adminUser);
        $response = $this->get(route('beli-paket.show', $packet->id));

        // Should get 403 error (not redirect to login)
        $response->assertStatus(403);
    }
}
