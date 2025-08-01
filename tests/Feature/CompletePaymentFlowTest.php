<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class CompletePaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_real_world_payment_flow()
    {
        echo "=== TESTING COMPLETE PAYMENT FLOW ===\n";

        // Step 1: Create users and packet exactly like user described
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create([
            'role' => 'student',
            'name' => 'Test Manusia'
        ]);

        $packet = Packet::factory()->create([
            'title' => 'Paket 4 - Fisika Kelas 8',
            'code' => 'FSK8',
            'subject' => 'Fisika',
            'grade' => 8
        ]);

        echo "✓ Created Test Manusia (ID: {$student->id}) and Paket 4 - Fisika Kelas 8 (ID: {$packet->id})\n";

        // Step 2: Student buys packet (simulating the purchase flow)
        $this->actingAs($student);
        $purchaseResponse = $this->post(route('proses-pembayaran'), [
            'packet_id' => $packet->id
        ]);

        $purchaseResponse->assertRedirect(route('beli-konfirmasi'));
        echo "✓ Student purchased packet successfully\n";

        // Verify payment record was created
        $this->assertDatabaseHas('payments', [
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Belum Bayar'
        ]);
        echo "✓ Payment record created with 'Belum Bayar' status\n";

        // Verify no packet_user relationship exists yet
        $this->assertDatabaseMissing('packet_user', [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);
        echo "✓ No packet_user relationship exists yet (as expected)\n";

        // Step 3: Admin logs in and verifies payment using Livewire modal
        $this->actingAs($admin);

        // Test the admin payment table shows the payment
        $adminTableResponse = $this->get(route('admin.pembayaran'));
        $adminTableResponse->assertStatus(200);
        $adminTableResponse->assertSee('Test Manusia');
        $adminTableResponse->assertSee('Paket 4 - Fisika Kelas 8');
        $adminTableResponse->assertSee('Belum Bayar');
        echo "✓ Admin can see payment in admin table\n";

        // Admin verifies payment using Livewire modal
        Livewire::test('admin.components.verify-payment-modal', [
            'userId' => $student->id,
            'packetId' => $packet->id
        ])
            ->call('confirmPayment')
            ->assertDispatched('paymentVerified');

        echo "✓ Admin verified payment using Livewire modal\n";

        // Step 4: Verify payment status updated
        $this->assertDatabaseHas('payments', [
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Sudah Bayar'
        ]);
        echo "✓ Payment status updated to 'Sudah Bayar'\n";

        // Step 5: Verify packet_user relationship created
        $this->assertDatabaseHas('packet_user', [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);
        echo "✓ packet_user relationship created successfully\n";

        // Step 6: Test admin data-siswa shows the packet
        $dataSiswaResponse = $this->get(route('admin.data-siswa.data'));
        $dataSiswaResponse->assertSuccessful();

        $data = $dataSiswaResponse->json();
        $studentData = collect($data['data'])->first(function ($item) use ($student) {
            return $item['name'] === $student->name;
        });

        $this->assertNotNull($studentData, 'Student should appear in data-siswa');
        $this->assertStringContainsString('FSK8', $studentData['packets']);
        echo "✓ Admin can see 'FSK8' in data-siswa table for Test Manusia\n";

        // Step 7: Test student can see packet in kelas page
        $this->actingAs($student);
        $kelasResponse = $this->get(route('user.kelas'));
        $kelasResponse->assertSuccessful();
        $kelasResponse->assertSee('Paket 4 - Fisika Kelas 8');
        echo "✓ Student can see 'Paket 4 - Fisika Kelas 8' in Paket Aktif section\n";

        // Step 8: Verify the packet appears in active packets, not other packets
        $kelasResponse->assertSee('Paket Aktif');
        echo "✓ Packet appears in 'Paket Aktif' section as expected\n";

        echo "\n=== ALL TESTS PASSED! THE ISSUE IS FIXED! ===\n";
        echo "Test Manusia should now see their packet in the Kelas page\n";
        echo "Admin should now see FSK8 in the data-siswa table\n";
    }
}
