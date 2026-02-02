<?php

namespace Tests\Feature;

use App\Models\Packet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPaymentActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_cancel_pending_payment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);
        $packet = Packet::factory()->create();

        DB::table('payments')->insert([
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Belum Bayar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($admin);

        Livewire::test('admin.components.cancel-payment-modal', [
            'userId' => $student->id,
            'packetId' => $packet->id,
        ])
            ->call('confirmCancel')
            ->assertDispatched('paymentCanceled');

        $this->assertDatabaseHas('payments', [
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Batal',
        ]);
    }

    public function test_admin_can_delete_canceled_payment(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);
        $packet = Packet::factory()->create();

        DB::table('payments')->insert([
            'user_id' => $student->id,
            'packet_id' => $packet->id,
            'status' => 'Batal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('packet_user')->insert([
            'user_id' => $student->id,
            'packet_id' => $packet->id,
        ]);

        $this->actingAs($admin);

        Livewire::test('admin.components.delete-payment-modal', [
            'userId' => $student->id,
            'packetId' => $packet->id,
        ])
            ->call('confirmDelete')
            ->assertDispatched('paymentDeleted');

        $this->assertDatabaseMissing('payments', [
            'user_id' => $student->id,
            'packet_id' => $packet->id,
        ]);

        $this->assertDatabaseMissing('packet_user', [
            'user_id' => $student->id,
            'packet_id' => $packet->id,
        ]);
    }
}
