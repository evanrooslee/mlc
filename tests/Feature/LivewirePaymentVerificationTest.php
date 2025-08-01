<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class LivewirePaymentVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_livewire_modal_creates_packet_user_relationship()
    {
        // Create admin and student users
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create([
            'role' => 'student',
            'name' => 'Test Manusia'
        ]);

        // Create a packet
        $packet = Packet::factory()->create([
            'title' => 'Paket 4 - Fisika Kelas 8',
            'code' => 'FSK8'
        ]);

        // Create a payment record
        DB::table('payments')->insert([
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Belum Bayar',
            'created_at' => now(),
            'updated_at' => now()
        ]);

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

        // Act as admin and test the Livewire modal
        $this->actingAs($admin);

        Livewire::test('admin.components.verify-payment-modal', [
            'userId' => $student->id,
            'packetId' => $packet->id
        ])
            ->call('confirmPayment')
            ->assertDispatched('paymentVerified');

        // Verify payment status updated
        $this->assertDatabaseHas('payments', [
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Sudah Bayar'
        ]);

        // Verify packet_user relationship created
        $this->assertDatabaseHas('packet_user', [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);

        echo "SUCCESS: Livewire modal now creates packet_user relationship!\n";
    }

    public function test_student_sees_packet_after_livewire_verification()
    {
        // Create admin and student users
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create([
            'role' => 'student',
            'name' => 'Test Manusia'
        ]);

        // Create a packet
        $packet = Packet::factory()->create([
            'title' => 'Paket 4 - Fisika Kelas 8',
            'code' => 'FSK8'
        ]);

        // Create a payment record
        DB::table('payments')->insert([
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Belum Bayar',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Admin verifies payment using Livewire modal
        $this->actingAs($admin);

        Livewire::test('admin.components.verify-payment-modal', [
            'userId' => $student->id,
            'packetId' => $packet->id
        ])
            ->call('confirmPayment');

        // Test that student can see packet in kelas page
        $this->actingAs($student);
        $response = $this->get(route('user.kelas'));

        $response->assertStatus(200);
        $response->assertSee('Paket 4 - Fisika Kelas 8');

        // Test that admin can see packet in data-siswa
        $this->actingAs($admin);
        $dataSiswaResponse = $this->get(route('admin.data-siswa.data'));

        $data = $dataSiswaResponse->json();
        $studentData = collect($data['data'])->first(function ($item) use ($student) {
            return $item['name'] === $student->name;
        });

        $this->assertNotNull($studentData);
        $this->assertStringContainsString('FSK8', $studentData['packets']);

        echo "SUCCESS: Complete flow works with Livewire modal!\n";
    }
}
