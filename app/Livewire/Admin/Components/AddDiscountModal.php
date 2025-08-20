<?php

namespace App\Livewire\Admin\Components;

use LivewireUI\Modal\ModalComponent;
use App\Models\Discount;

class AddDiscountModal extends ModalComponent
{
    public $code;
    public $percentage;
    public $is_valid = true;
    
    public $networkError = '';
    public $isSubmitting = false;
    
    protected $rules = [
        'code' => 'required|string|max:30|unique:discounts,code',
        'percentage' => 'required|numeric|min:1|max:100',
        'is_valid' => 'boolean',
    ];
    
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
            
            Discount::create([
                'code' => $this->code,
                'percentage' => $this->percentage,
                'is_valid' => $this->is_valid,
            ]);
            
            $this->dispatch('discount_created', [
                'message' => 'Diskon "' . $this->code . '" berhasil ditambahkan!',
            ]);
            
            $this->resetForm();
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
        $this->resetForm();
        $this->closeModal();
    }
    
    private function resetForm()
    {
        $this->reset([
            'code',
            'percentage',
            'is_valid',
            'networkError',
            'isSubmitting'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }
    
    public static function modalMaxWidth(): string
    {
        return 'md';
    }
    
    public function render()
    {
        return view('livewire.admin.components.add-discount-modal');
    }
}
