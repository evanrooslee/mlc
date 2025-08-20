<?php

namespace App\Livewire\Admin\Components;

use LivewireUI\Modal\ModalComponent;
use App\Models\Discount;
use Illuminate\Validation\Rule;

class EditDiscountModal extends ModalComponent
{
    public $discount_id;
    public $code;
    public $percentage;
    public $is_valid;
    
    public $networkError = '';
    public $isSubmitting = false;
    
    protected $messages = [
        'code.required' => 'Kode diskon harus diisi',
        'code.string' => 'Kode diskon harus berupa teks',
        'code.max' => 'Kode diskon maksimal 30 karakter',
        'code.unique' => 'Kode diskon sudah digunakan',
        'percentage.required' => 'Persentase diskon harus diisi',
        'percentage.numeric' => 'Persentase diskon harus berupa angka',
        'percentage.min' => 'Persentase diskon minimal 1%',
        'percentage.max' => 'Persentase diskon maksimal 100%',
    ];
    
    public function mount($discountId)
    {
        $this->discount_id = $discountId;
        
        $discount = Discount::find($discountId);
        if ($discount) {
            $this->code = $discount->code;
            $this->percentage = $discount->percentage;
            $this->is_valid = $discount->is_valid;
        }
    }
    
    protected function rules()
    {
        return [
            'code' => [
                'required',
                'string',
                'max:30',
                Rule::unique('discounts', 'code')->ignore($this->discount_id)
            ],
            'percentage' => 'required|numeric|min:1|max:100',
            'is_valid' => 'boolean',
        ];
    }
    
    public function updated($propertyName)
    {
        $this->networkError = '';
        
        try {
            $this->validateOnly($propertyName);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
        }
    }
    
    public function save()
    {
        $this->networkError = '';
        $this->isSubmitting = true;
        
        try {
            $this->validate();
            
            $discount = Discount::find($this->discount_id);
            
            if ($discount) {
                $discount->update([
                    'code' => $this->code,
                    'percentage' => $this->percentage,
                    'is_valid' => $this->is_valid,
                ]);
                
                $this->dispatch('discount_updated', [
                    'message' => 'Diskon "' . $this->code . '" berhasil diperbarui!',
                ]);
            }
            
            $this->closeModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            $this->networkError = 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.';
            $this->isSubmitting = false;
        }
    }
    
    public function cancel()
    {
        $this->closeModal();
    }
    
    public static function modalMaxWidth(): string
    {
        return 'md';
    }
    
    public function render()
    {
        return view('livewire.admin.components.edit-discount-modal');
    }
}
