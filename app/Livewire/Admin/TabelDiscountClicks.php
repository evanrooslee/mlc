<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DiscountClick;

class TabelDiscountClicks extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'clicked_at';
    public $sortDirection = 'desc';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function render()
    {
        $discountClicks = DiscountClick::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('user_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.tabel-discount-clicks', compact('discountClicks'));
    }
}
