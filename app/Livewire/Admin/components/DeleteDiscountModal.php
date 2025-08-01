<?php

namespace App\Livewire\Admin\Components;

use LivewireUI\Modal\ModalComponent;
use App\Models\Discount;

class DeleteDiscountModal extends ModalComponent
{
    public $discount_id;
    public $code;
    public $percentage;
    
    public function mount($discountId, $code, $percentage)
    {
        $this->discount_id = $discountId;
        $this->code = $code;
        $this->percentage = $percentage;
    }
    
    public function deleteDiscount()
    {
        $discount = Discount::find($this->discount_id);
        
        if ($discount) {
            $discount->delete();
            
            $this->dispatch('discount_deleted', [
                'message' => 'Diskon "' . $this->code . '" berhasil dihapus!',
            ]);
        }
        
        $this->closeModal();
    }
    
    public function render()
    {
        return view('livewire.admin.components.delete-discount-modal');
    }
}
