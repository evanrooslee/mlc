<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DebugPaymentVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_debug_payment_verification_process()
    {
        // Create admin and student users
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student', 'name' => 'Test Student']);

        // Create a packet
        $packet = Packet::factory()->create(['title' => 'Test Packet', 'code' => 'TEST01']);

        // Create a payment record
        DB::table('payments')->insert([
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Belum Bayar'
        ]);

        echo "Created payment record\n";

        // Verify initial state
        $this->assertDatabaseHas('payments', [
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Belum Bayar'
        ]);

        $this->assertDatabaseMissing('packet_user', [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);

        echo "Initial state verified\n";

        // Admin verifies the payment
        $this->actingAs($admin);
        $response = $this->post(route('admin.pembayaran.verifikasi'), [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);

        echo "Payment verification request sent\n";
        echo "Response status: " . $response->getStatusCode() . "\n";
        echo "Response content: " . $response->getContent() . "\n";

        // Check if payment status was updated
        $payment = DB::table('payments')
            ->where('user_id', $student->id)
            ->where('packet_id', $packet->id)
            ->first();

        echo "Payment status after verification: " . $payment->status . "\n";

        // Check if packet_user relationship was created
        $relationship = DB::table('packet_user')
            ->where('user_id', $student->id)
            ->where('packet_id', $packet->id)
            ->first();

        if ($relationship) {
            echo "Packet-user relationship created successfully\n";
        } else {
            echo "ERROR: Packet-user relationship NOT created\n";
        }

        // Verify final state
        $this->assertDatabaseHas('payments', [
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Sudah Bayar'
        ]);

        $this->assertDatabaseHas('packet_user', [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);
    }
}
