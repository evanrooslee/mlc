<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Packet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RealWorldPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_payment_flow_like_user_described()
    {
        // Step 1: Create users and packet like in real scenario
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

        echo "Created users and packet\n";
        echo "Student ID: " . $student->id . "\n";
        echo "Packet ID: " . $packet->id . "\n";

        // Step 2: Simulate user buying packet (this should create payment record)
        // Let's check how payments are created in the real flow

        // First, let's see if there's a payment creation process
        // For now, let's manually create the payment like the system would
        DB::table('payments')->insert([
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Belum Bayar',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        echo "Created payment record\n";

        // Step 3: Verify initial state
        $payment = DB::table('payments')
            ->where('user_id', $student->id)
            ->where('packet_id', $packet->id)
            ->first();

        $this->assertNotNull($payment, 'Payment record should exist');
        $this->assertEquals('Belum Bayar', $payment->status);

        // Verify no packet_user relationship exists yet
        $relationship = DB::table('packet_user')
            ->where('user_id', $student->id)
            ->where('packet_id', $packet->id)
            ->first();

        $this->assertNull($relationship, 'Packet-user relationship should not exist yet');

        echo "Initial state verified - payment exists, no relationship yet\n";

        // Step 4: Admin verifies payment
        $this->actingAs($admin);
        $response = $this->post(route('admin.pembayaran.verifikasi'), [
            'user_id' => $student->id,
            'packet_id' => $packet->id
        ]);

        echo "Admin verification request sent\n";
        echo "Response status: " . $response->getStatusCode() . "\n";
        echo "Response content: " . $response->getContent() . "\n";

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);

        // Step 5: Check payment status updated
        $updatedPayment = DB::table('payments')
            ->where('user_id', $student->id)
            ->where('packet_id', $packet->id)
            ->first();

        echo "Payment status after verification: " . $updatedPayment->status . "\n";
        $this->assertEquals('Sudah Bayar', $updatedPayment->status);

        // Step 6: Check packet_user relationship created
        $relationship = DB::table('packet_user')
            ->where('user_id', $student->id)
            ->where('packet_id', $packet->id)
            ->first();

        if ($relationship) {
            echo "SUCCESS: Packet-user relationship created\n";
            echo "User ID: " . $relationship->user_id . "\n";
            echo "Packet ID: " . $relationship->packet_id . "\n";
        } else {
            echo "ERROR: Packet-user relationship NOT created\n";

            // Let's debug why
            echo "Checking if insertOrIgnore failed...\n";

            // Try manual insert to see what happens
            try {
                DB::table('packet_user')->insert([
                    'user_id' => $student->id,
                    'packet_id' => $packet->id
                ]);
                echo "Manual insert succeeded\n";
            } catch (\Exception $e) {
                echo "Manual insert failed: " . $e->getMessage() . "\n";
            }
        }

        $this->assertNotNull($relationship, 'Packet-user relationship should be created');

        // Step 7: Test data-siswa shows the packet
        $this->actingAs($admin);
        $dataSiswaResponse = $this->get(route('admin.data-siswa.data'));
        $dataSiswaResponse->assertSuccessful();

        $data = $dataSiswaResponse->json();
        $studentData = collect($data['data'])->first(function ($item) use ($student) {
            return $item['name'] === $student->name;
        });

        $this->assertNotNull($studentData, 'Student should appear in data-siswa');
        echo "Student data in admin table: " . json_encode($studentData) . "\n";

        if ($studentData['packets'] !== '-') {
            echo "SUCCESS: Student has packets in admin table: " . $studentData['packets'] . "\n";
        } else {
            echo "ERROR: Student shows no packets in admin table\n";
        }

        // Step 8: Test student can see packet in kelas page
        $this->actingAs($student);
        $kelasResponse = $this->get(route('user.kelas'));
        $kelasResponse->assertSuccessful();

        echo "Kelas page loaded successfully\n";

        // Check if packet appears in active packets
        $kelasResponse->assertSee('Paket 4 - Fisika Kelas 8');

        echo "All tests completed successfully!\n";
    }
}
