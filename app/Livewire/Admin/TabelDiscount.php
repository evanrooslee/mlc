<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Discount;

class TabelDiscount extends Component
{
    use WithPagination;
    
    #[Url(except: '')]
    public $search = '';
    
    public $perPage = 10;
    
    #[Url(except: 'code')]
    public $sortBy = 'code';
    
    #[Url(except: 'asc')]
    public $sortDirection = 'asc';
    
    public $flash_message = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'code'],
        'sortDirection' => ['except' => 'asc'],
    ];
    
    protected $listeners = [
        'discount_created' => 'handleDiscountCreated',
        'discount_updated' => 'handleDiscountUpdated',
        'discount_deleted' => 'handleDiscountDeleted',
    ];
    
    public function handleDiscountCreated($event_data = [])
    {
        $this->flash_message = $event_data['message'] ?? 'Diskon berhasil ditambahkan!';
        $this->resetPage();
    }
    
    public function handleDiscountUpdated($event_data = [])
    {
        $this->flash_message = $event_data['message'] ?? 'Diskon berhasil diperbarui!';
    }
    
    public function handleDiscountDeleted($event_data = [])
    {
        $this->flash_message = $event_data['message'] ?? 'Diskon berhasil dihapus!';
        $this->resetPage();
    }
    
    public function clearFlashMessage()
    {
        $this->flash_message = '';
    }
    
    public function updatingSearch()
    {
        $this->clearFlashMessage();
        $this->resetPage();
    }
    
    public function sortByColumn($field)
    {
        $this->clearFlashMessage();
        
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }
    
    public function render()
    {
        $discounts = Discount::when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('code', 'like', '%' . $this->search . '%')
                      ->orWhere('percentage', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.admin.tabel-discount', compact('discounts'));
    }
}
