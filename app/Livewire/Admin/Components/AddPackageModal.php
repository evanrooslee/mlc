<?php

namespace App\Livewire\Admin\Components;

use App\Models\Discount;
use App\Models\Packet;
use App\Services\ImageService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class AddPackageModal extends ModalComponent
{
    use WithFileUploads;

    public $title;

    public $code;

    public $tingkatan;

    public $kurikulum;

    public $grade;

    public $subject;

    public $benefit;

    public $sesi = 8;

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
        'tingkatan' => 'required|in:SMP,SMA,Olimpiade',
        'kurikulum' => 'nullable|in:NAS,NAS+/International',
        'grade' => 'nullable|integer|min:1|max:12',
        'subject' => 'required|in:Matematika,Fisika,Kimia,Campuran',
        'benefit' => 'nullable|string|max:2000',
        'sesi' => 'required|integer|in:8',
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
        'tingkatan.required' => 'Jenjang pendidikan harus diisi',
        'tingkatan.in' => 'Jenjang pendidikan harus salah satu dari: SMP, SMA, atau Olimpiade',
        'kurikulum.required' => 'Kurikulum harus diisi',
        'kurikulum.in' => 'Kurikulum tidak valid',
        'grade.required' => 'Kelas harus diisi',
        'grade.integer' => 'Kelas harus berupa angka',
        'grade.in' => 'Kelas harus sesuai jenjang yang dipilih',
        'subject.required' => 'Mata pelajaran harus diisi',
        'subject.in' => 'Mata pelajaran harus salah satu dari: Matematika, Fisika, Kimia, atau Campuran',
        'sesi.required' => 'Sesi harus diisi',
        'sesi.in' => 'Sesi harus bernilai 8',
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

        // Debug: Log when subject is updated
        if ($propertyName === 'subject') {
            Log::info('Subject updated', [
                'subject' => $this->subject,
                'rules' => $this->rules()['subject'] ?? 'not found',
            ]);
        }

        // Validate specific field on update
        try {
            $this->validateOnly($propertyName);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Debug: Log validation errors
            if ($propertyName === 'subject') {
                Log::error('Subject validation failed', [
                    'subject' => $this->subject,
                    'errors' => $e->errors(),
                ]);
            }
            // Validation errors are automatically handled by Livewire
        }

        // Custom validation for discount fields
        if ($propertyName === 'has_discount' && ! $this->has_discount) {
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
        $allowedGrades = $this->getAllowedGradesForTingkatan();

        $rules = [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:packets,code',
            'tingkatan' => 'required|in:SMP,SMA,Olimpiade',
            'subject' => 'required|in:Matematika,Fisika,Kimia,Campuran',
            'benefit' => 'nullable|string|max:2000',
            'sesi' => 'required|integer|in:8',
            'price' => 'required|numeric|min:0|max:99999999',
            'has_discount' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ];

        if ($this->tingkatan === 'Olimpiade') {
            $rules['kurikulum'] = 'required|in:SMP,SMA';
            $rules['grade'] = 'nullable';
        } else {
            $rules['kurikulum'] = 'required|in:NAS,NAS+/International';
            $rules['grade'] = 'required|integer|in:'.implode(',', $allowedGrades);
        }

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
                        $query->where('code', 'like', '%'.$this->discount_search.'%')
                            ->orWhere('percentage', 'like', '%'.$this->discount_search.'%');
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
            $this->discount_search = $discount->code.' ('.$discount->percentage.'%)';
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
            // Debug: Log the current subject value and validation rules
            Log::info('AddPackageModal Debug', [
                'subject' => $this->subject,
                'validation_rules' => $this->rules(),
                'all_data' => [
                    'title' => $this->title,
                    'code' => $this->code,
                    'tingkatan' => $this->tingkatan,
                    'kurikulum' => $this->kurikulum,
                    'grade' => $this->grade,
                    'subject' => $this->subject,
                    'sesi' => $this->sesi,
                ],
            ]);

            // Validate all fields
            $this->validate();

            // Handle image upload with resizing
            $imageName = 'packet1.jpg'; // Default image
            if ($this->image) {
                // Use ImageService to resize and save the image (with fallback)
                try {
                    $imageService = new ImageService;
                    $path = $imageService->resizeForType($this->image, 'package', 'images');
                } catch (\Exception $e) {
                    // Fallback: Save without resizing if GD is not available
                    $imageName = time().'.'.$this->image->getClientOriginalExtension();
                    $path = $this->image->storePubliclyAs('images', $imageName, 'public');

                    // Log the error for debugging
                    Log::warning('Image resizing failed, saved without resizing: '.$e->getMessage());
                }

                // Store the full path for database storage
                $imageName = $path;
            }

            // Create the packet
            $packet = Packet::create([
                'title' => $this->title,
                'code' => $this->code,
                'tingkatan' => $this->tingkatan,
                'kurikulum' => $this->kurikulum,
                'grade' => $this->grade,
                'subject' => $this->subject,
                'benefit' => $this->benefit,
                'sesi' => $this->sesi,
                'price' => $this->price,
                'type' => 'standard',
                'discount_id' => $this->has_discount ? $this->selected_discount_id : null,
                'image' => $imageName,
            ]);

            // Dispatch success event
            $this->dispatch('packageAdded', [
                'message' => 'Paket berhasil ditambahkan',
                'packet' => $packet->toArray(),
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
            Log::error('Package save error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'title' => $this->title,
                    'subject' => $this->subject,
                    'code' => $this->code,
                ],
            ]);
            $this->networkError = 'Error: '.$e->getMessage();
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
            'tingkatan',
            'kurikulum',
            'grade',
            'subject',
            'benefit',
            'sesi',
            'price',
            'has_discount',
            'selected_discount_id',
            'discount_search',
            'discount_search_results',
            'show_discount_dropdown',
            'image',
            'networkError',
            'isSubmitting',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedTingkatan(): void
    {
        if ($this->tingkatan === 'Olimpiade') {
            $this->kurikulum = null;
            $this->grade = null;
        }

        if ($this->tingkatan === 'SMP' && $this->grade !== null && ($this->grade < 7 || $this->grade > 9)) {
            $this->grade = null;
        }

        if ($this->tingkatan === 'SMA' && $this->grade !== null && ($this->grade < 10 || $this->grade > 12)) {
            $this->grade = null;
        }

        if ($this->tingkatan === 'Olimpiade' && in_array($this->kurikulum, ['NAS', 'NAS+/International'], true)) {
            $this->kurikulum = null;
        }

        if (in_array($this->tingkatan, ['SMP', 'SMA'], true) && in_array($this->kurikulum, ['SMP', 'SMA'], true)) {
            $this->kurikulum = null;
        }

        if ($this->tingkatan === null || $this->tingkatan === '') {
            $this->kurikulum = null;
            $this->grade = null;
        }

        $this->resetErrorBag(['kurikulum', 'grade']);
    }

    private function getAllowedGradesForTingkatan(): array
    {
        return match ($this->tingkatan) {
            'SMP' => [7, 8, 9],
            'SMA' => [10, 11, 12],
            default => [7, 8, 9, 10, 11, 12],
        };
    }

    /**
     * Specify the modal size.
     */
    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    // Diagnostic method to test validation rules
    public function testValidation()
    {
        // Simple test to see if method is called
        $this->networkError = 'Test button clicked! Subject validation test running...';

        $this->subject = 'Campuran';
        $rules = $this->rules();

        Log::info('=== CAMPURAN TEST START ===');
        Log::info('Test Validation', [
            'subject' => $this->subject,
            'subject_rule' => $rules['subject'] ?? 'not found',
            'all_rules' => $rules,
        ]);

        try {
            $this->validateOnly('subject');
            Log::info('Validation passed for Campuran');
            $this->networkError = 'SUCCESS: Campuran validation passed!';
        } catch (\Exception $e) {
            Log::error('Validation failed for Campuran', [
                'error' => $e->getMessage(),
                'errors' => method_exists($e, 'errors') ? $e->errors() : 'no errors method',
            ]);
            $this->networkError = 'FAILED: '.$e->getMessage();
        }
        Log::info('=== CAMPURAN TEST END ===');
    }

    public function render()
    {
        return view('livewire.admin.components.add-package-modal');
    }
}
