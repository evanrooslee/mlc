<?php

namespace App\Livewire\Admin\Components;

use Illuminate\Support\Facades\DB;
use LivewireUI\Modal\ModalComponent;

class DeletePaymentModal extends ModalComponent
{
    public $user_id;

    public $packet_id;

    public $student_name;

    public $student_phone;

    public $parent_name;

    public $parent_phone;

    public $pesanan;

    public function mount(int $userId, int $packetId): void
    {
        $this->user_id = $userId;
        $this->packet_id = $packetId;

        $payment = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->join('packets', 'payments.packet_id', '=', 'packets.id')
            ->select(
                'users.name as student_name',
                'users.phone_number as student_phone',
                'users.parents_phone_number as parent_phone',
                'users.parent_name as parent_name',
                'packets.title as pesanan'
            )
            ->where('payments.user_id', $userId)
            ->where('payments.packet_id', $packetId)
            ->first();

        if ($payment) {
            $this->student_name = $payment->student_name;
            $this->student_phone = $payment->student_phone;
            $this->parent_phone = $payment->parent_phone;
            $this->pesanan = $payment->pesanan;
            $this->parent_name = $payment->parent_name;

            if (empty($this->parent_name)) {
                $firstName = explode(' ', $payment->student_name)[0];
                $this->parent_name = 'Ayah/Ibu '.$firstName;
            }
        }
    }

    public function confirmDelete(): void
    {
        DB::table('payments')
            ->where('user_id', $this->user_id)
            ->where('packet_id', $this->packet_id)
            ->delete();

        DB::table('packet_user')
            ->where('user_id', $this->user_id)
            ->where('packet_id', $this->packet_id)
            ->delete();

        $this->dispatch('paymentDeleted', [
            'message' => 'Pembayaran berhasil dihapus.',
        ]);

        $this->closeModal();
    }

    public function cancel(): void
    {
        $this->closeModal();
    }

    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public function render()
    {
        return view('livewire.admin.components.delete-payment-modal');
    }
}
