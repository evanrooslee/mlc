<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class TabelPembayaran extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $search = '';

    public $perPage = 10;

    #[Url(except: 'users.name')]
    public $sortBy = 'users.name';

    #[Url(except: 'asc')]
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'users.name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortByColumn($field)
    {
        // Map parent_name to the actual database field
        if ($field === 'parent_name') {
            $field = 'users.parent_name';
        }

        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function openVerificationModal($userId, $packetId)
    {
        $this->dispatch('openModal', 'admin.components.verify-payment-modal', [
            'userId' => $userId,
            'packetId' => $packetId
        ]);
    }

    #[On('paymentVerified')]
    public function handlePaymentVerified($data)
    {
        session()->flash('message', $data['message']);
    }

    public function render()
    {
        $payments = DB::table('payments')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->join('packets', 'payments.packet_id', '=', 'packets.id')
            ->select(
                'payments.user_id',
                'payments.packet_id',
                'users.name as student_name',
                'users.phone_number as student_phone',
                'users.parents_phone_number as parent_phone',
                'users.parent_name',
                'packets.title as pesanan',
                'payments.status',
                'users.name as full_name'
            )
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('users.name', 'like', '%' . $this->search . '%')
                        ->orWhere('users.phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('users.parents_phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('users.parent_name', 'like', '%' . $this->search . '%')
                        ->orWhere('packets.title', 'like', '%' . $this->search . '%')
                        ->orWhere('payments.status', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        // Provide fallback for users without parent_name
        $payments->getCollection()->transform(function ($payment) {
            if (empty($payment->parent_name)) {
                $firstName = explode(' ', $payment->student_name)[0];
                $payment->parent_name = 'Ayah/Ibu ' . $firstName;
            }
            return $payment;
        });

        return view('livewire.admin.tabel-pembayaran', compact('payments'));
    }
}
