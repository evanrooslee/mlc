<?php

namespace App\Livewire\Admin\components;

use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;
use App\Models\Discount;
use App\Models\Packet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\ImageService;

class AddPackageModal extends ModalComponent
{
    use WithFileUploads;

    public $title;
    public $code;
    public $grade;
    public $subject;
    public $type;
    public $benefit;
    public $price;
    public $has_discount = false;
    public $selected_discount_id = null;
    public $discount_search = '';
    public $discount_search_results = [];
    public $show_discount_dropdown = false;
    public $image;

    // Error handling properties
    public $networkError = '';
    public $isSubmitting = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:packets,code',
        'grade' => 'required|integer|min:1|max:12',
        'subject' => 'required|in:Matematika,Fisika,Kimia',
        'type' => 'required|in:standard,premium',
        'benefit' => 'nullable|string|max:2000',
        'price' => 'required|numeric|min:0|max:99999999',
        'has_discount' => 'boolean',
        'selected_discount_id' => 'nullable|exists:discounts,id',
        'image' => 'nullable|image|max:2048', // 2MB max
    ];

    protected $messages = [
        'title.required' => 'Judul paket harus diisi',
        'title.string' => 'Judul paket harus berupa teks',
        'title.max' => 'Judul paket maksimal 255 karakter',
        'code.required' => 'Kode paket harus diisi',
        'code.string' => 'Kode paket harus berupa teks',
        'code.max' => 'Kode paket maksimal 50 karakter',
        'code.unique' => 'Kode paket sudah digunakan',
        'grade.required' => 'Kelas harus diisi',
        'grade.integer' => 'Kelas harus berupa angka',
        'grade.min' => 'Kelas minimal 1',
        'grade.max' => 'Kelas maksimal 12',
        'subject.required' => 'Mata pelajaran harus diisi',
        'subject.in' => 'Mata pelajaran harus salah satu dari: Matematika, Fisika, atau Kimia',
        'type.required' => 'Tipe paket harus diisi',
        'type.in' => 'Tipe paket harus standard atau premium',
        'benefit.string' => 'Manfaat harus berupa teks',
        'benefit.max' => 'Manfaat maksimal 2000 karakter',
        'price.required' => 'Harga harus diisi',
        'price.numeric' => 'Harga harus berupa angka',
        'price.min' => 'Harga tidak boleh negatif',
        'price.max' => 'Harga terlalu besar',
        'selected_discount_id.exists' => 'Diskon yang dipilih tidak valid',
        'image.image' => 'File harus berupa gambar',
        'image.max' => 'Ukuran gambar maksimal 2MB',
    ];

    // Real-time validation methods
    public function updated($propertyName)
    {
        // Clear network error when user starts typing
        $this->networkError = '';

        // Validate specific field on update
        try {
            $this->validateOnly($propertyName);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
        }

        // Custom validation for discount fields
        if ($propertyName === 'has_discount' && !$this->has_discount) {
            $this->selected_discount_id = null;
            $this->discount_search = '';
            $this->discount_search_results = [];
            $this->show_discount_dropdown = false;
            // Clear validation errors for discount fields when disabled
            $this->resetErrorBag(['selected_discount_id']);
        }

        // Handle discount search
        if ($propertyName === 'discount_search') {
            $this->searchDiscounts();
        }

        // Additional client-side validation feedback
        if ($propertyName === 'price' && $this->price !== null) {
            if ($this->price < 0) {
                $this->addError('price', 'Harga tidak boleh negatif');
            } elseif ($this->price > 99999999) {
                $this->addError('price', 'Harga terlalu besar');
            }
        }
    }

    // Custom validation rules
    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:packets,code',
            'grade' => 'required|integer|min:1|max:12',
            'subject' => 'required|in:Matematika,Fisika,Kimia',
            'type' => 'required|in:standard,premium',
            'benefit' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0|max:99999999',
            'has_discount' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ];

        // Add discount validation only if discount is enabled
        if ($this->has_discount) {
            $rules['selected_discount_id'] = 'required|exists:discounts,id';
        } else {
            $rules['selected_discount_id'] = 'nullable';
        }

        return $rules;
    }

    public function searchDiscounts()
    {
        if (strlen($this->discount_search) >= 1) {
            // Special command: "~" shows all discounts
            if ($this->discount_search === '~') {
                $this->discount_search_results = Discount::where('is_valid', true)
                    ->orderBy('code')
                    ->limit(15)
                    ->get();
            } else {
                $this->discount_search_results = Discount::where('is_valid', true)
                    ->where(function ($query) {
                        $query->where('code', 'like', '%' . $this->discount_search . '%')
                            ->orWhere('percentage', 'like', '%' . $this->discount_search . '%');
                    })
                    ->orderBy('code')
                    ->limit(10)
                    ->get();
            }
            $this->show_discount_dropdown = true;
        } else {
            $this->discount_search_results = [];
            $this->show_discount_dropdown = false;
        }
    }

    public function selectDiscount($discountId)
    {
        $discount = Discount::find($discountId);
        if ($discount) {
            $this->selected_discount_id = $discountId;
            $this->discount_search = $discount->code . ' (' . $discount->percentage . '%)';
            $this->show_discount_dropdown = false;
            $this->discount_search_results = [];
        }
    }

    public function clearDiscountSelection()
    {
        $this->selected_discount_id = null;
        $this->discount_search = '';
        $this->discount_search_results = [];
        $this->show_discount_dropdown = false;
    }

    public function save()
    {
        // Clear previous errors
        $this->networkError = '';
        $this->isSubmitting = true;

        try {
            // Validate all fields
            $this->validate();

            // Handle image upload with resizing
            $imageName = 'packet1.jpg'; // Default image
            if ($this->image) {
                // Use ImageService to resize and save the image (with fallback)
                try {
                    $imageService = new ImageService();
                    $path = $imageService->resizeForType($this->image, 'package', 'images');
                } catch (\Exception $e) {
                    // Fallback: Save without resizing if GD is not available
                    $imageName = time() . '.' . $this->image->getClientOriginalExtension();
                    $path = $this->image->storePubliclyAs('images', $imageName, 'public');

                    // Log the error for debugging
                    Log::warning('Image resizing failed, saved without resizing: ' . $e->getMessage());
                }

                // Store the full path for database storage
                $imageName = $path;
            }

            // Create the packet
            $packet = Packet::create([
                'title' => $this->title,
                'code' => $this->code,
                'grade' => $this->grade,
                'subject' => $this->subject,
                'type' => $this->type,
                'benefit' => $this->benefit,
                'price' => $this->price,
                'discount_id' => $this->has_discount ? $this->selected_discount_id : null,
                'image' => $imageName,
            ]);

            // Dispatch success event
            $this->dispatch('packageAdded', [
                'message' => 'Paket berhasil ditambahkan',
                'packet' => $packet->toArray()
            ]);

            // Reset form and close modal
            $this->resetForm();
            $this->closeModal();
            $this->isSubmitting = false;
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            // Handle network/server errors
            $this->networkError = 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.';
            $this->isSubmitting = false;
        }
    }

    public function cancel()
    {
        $this->resetForm();
        $this->closeModal();
    }

    // Handle browser back button or ESC key
    public function dehydrate()
    {
        // Ensure form is clean when component is dehydrated
        if ($this->networkError) {
            $this->networkError = '';
        }
    }

    // Handle modal close event (when user clicks outside or presses ESC)
    public function closeModalAction()
    {
        $this->resetForm();
        $this->closeModal();
    }

    // Reset form data and errors
    private function resetForm()
    {
        $this->reset([
            'title',
            'code',
            'grade',
            'subject',
            'type',
            'benefit',
            'price',
            'has_discount',
            'selected_discount_id',
            'discount_search',
            'discount_search_results',
            'show_discount_dropdown',
            'image',
            'networkError',
            'isSubmitting'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * Specify the modal size.
     *
     * @return string
     */
    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public function render()
    {
        return view('livewire.admin.components.add-package-modal');
    }
}
