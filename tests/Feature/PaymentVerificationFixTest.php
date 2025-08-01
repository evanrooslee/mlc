<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PaymentVerificationFixTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_verification_creates_packet_user_relationship()
    {
        // Create admin and student users
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        // Create a packet
        $packet = Packet::factory()->create();

        // Create a payment record
        DB::table('payments')->insert([
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Belum Bayar'
        ]);

        // Verify that packet_user relationship doesn't exist yet
        $this->assertDatabaseMissing('packet_user', [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);

        // Admin verifies the payment
        $this->actingAs($admin);
        $response = $this->post(route('admin.pembayaran.verifikasi'), [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);

        $response->assertSuccessful();

        // Verify payment status is updated
        $this->assertDatabaseHas('payments', [
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Sudah Bayar'
        ]);

        // Verify packet_user relationship is created
        $this->assertDatabaseHas('packet_user', [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);
    }

    public function test_student_can_see_verified_packets_in_kelas_page()
    {
        // Create a student
        $student = User::factory()->create(['role' => 'student']);

        // Create packets
        $activePacket = Packet::factory()->create(['title' => 'Active Packet']);
        $inactivePacket = Packet::factory()->create(['title' => 'Inactive Packet']);

        // Create packet_user relationship (simulating verified payment)
        $student->packets()->attach($activePacket->id);

        // Access kelas page as student
        $this->actingAs($student);
        $response = $this->get(route('user.kelas'));

        $response->assertStatus(200);
        $response->assertSee('Active Packet');
        $response->assertSee('Inactive Packet'); // Should appear in "Paket Lainnya"
    }

    public function test_verified_packets_show_in_admin_data_siswa()
    {
        // Create admin and student
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        // Create packets with codes
        $packet1 = Packet::factory()->create(['code' => 'MTK12']);
        $packet2 = Packet::factory()->create(['code' => 'FSK09']);

        // Create packet_user relationships (simulating verified payments)
        $student->packets()->attach([$packet1->id, $packet2->id]);

        // Access data-siswa as admin
        $this->actingAs($admin);
        $response = $this->get(route('admin.data-siswa.data'));

        $response->assertStatus(200);

        $data = $response->json();
        $studentData = collect($data['data'])->first(function ($item) use ($student) {
            return $item['name'] === $student->name;
        });

        $this->assertNotNull($studentData);
        $this->assertStringContainsString('MTK12', $studentData['packets']);
        $this->assertStringContainsString('FSK09', $studentData['packets']);
    }

    public function test_packet_images_display_correctly()
    {
        // Create a packet with image
        $packet = Packet::factory()->create([
            'title' => 'Test Packet',
            'image' => 'packets/test-image.jpg'
        ]);

        // Test that image_url attribute works correctly
        $expectedUrl = asset('storage/packets/test-image.jpg');

        // Since we can't actually create the file in tests, the model should fallback
        $fallbackUrl = asset('images/hero-illustration.png');
        $this->assertEquals($fallbackUrl, $packet->image_url);

        // Test landing page uses image_url
        $response = $this->get('/');
        $response->assertStatus(200);
        // The view should use $packet->image_url, not $packet->image
    }
}
