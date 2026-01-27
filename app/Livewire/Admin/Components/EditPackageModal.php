<?php

namespace App\Livewire\Admin\Components;

use App\Models\Discount;
use App\Models\Packet;
use App\Services\ImageService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class EditPackageModal extends ModalComponent
{
    use WithFileUploads;

    public $packageId;

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

    public $current_image;

    public $showDeleteConfirmation = false;

    // Error handling properties
    public $networkError = '';

    public $isSubmitting = false;

    public $isDeleting = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'code' => 'required|string|max:50',
        'tingkatan' => 'required|in:SMP,SMA,Olimpiade',
        'kurikulum' => 'nullable|in:NAS,NAS+/International',
        'grade' => 'nullable|integer|min:1|max:12',
        'subject' => 'required|in:Matematika,Fisika,Kimia,Campuran',
        'benefit' => 'nullable|string|max:2000',
        'sesi' => 'required|integer|in:8',
        'price' => 'required|numeric|min:0|max:99999999',
        'has_discount' => 'boolean',
        'selected_discount_id' => 'nullable|exists:discounts,id',
        'image' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'title.required' => 'Judul paket harus diisi',
        'title.string' => 'Judul paket harus berupa teks',
        'title.max' => 'Judul paket maksimal 255 karakter',
        'code.required' => 'Kode paket harus diisi',
        'code.string' => 'Kode paket harus berupa teks',
        'code.max' => 'Kode paket maksimal 50 karakter',
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

    public function mount($packageId)
    {
        $this->packageId = $packageId;

        // Fetch package data from database
        $package = Packet::with('discount')->find($packageId);

        if ($package) {
            $this->title = $package->title;
            $this->code = $package->code;
            $this->tingkatan = $package->tingkatan;
            $this->kurikulum = $package->kurikulum;
            $this->grade = $package->grade;
            $this->subject = $package->subject;
            $this->benefit = $package->benefit;
            $this->sesi = $package->sesi ?? 8;
            $this->price = $package->price;
            $this->current_image = $package->image;

            if ($this->tingkatan === 'Olimpiade') {
                $this->kurikulum = null;
                $this->grade = null;
            }

            // Handle discount
            if ($package->discount_id && $package->discount instanceof Discount) {
                $this->has_discount = true;
                $this->selected_discount_id = $package->discount_id;
                $this->discount_search = $package->discount->code.' ('.$package->discount->percentage.'%)';
            } else {
                $this->has_discount = false;
                $this->selected_discount_id = null;
                $this->discount_search = '';
            }
        }
    }

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
            'code' => 'required|string|max:50|unique:packets,code,'.$this->packageId,
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

    public function update()
    {
        // Clear previous errors
        $this->networkError = '';
        $this->isSubmitting = true;

        try {
            // Validate all fields
            $this->validate();

            // Find the package
            $package = Packet::find($this->packageId);
            if (! $package) {
                throw new \Exception('Paket tidak ditemukan.');
            }

            // Handle image upload with resizing
            $imageName = $this->current_image; // Keep current image by default
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

            // Update the package
            $package->update([
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
            $this->dispatch('packageUpdated', [
                'message' => 'Paket berhasil diperbarui',
                'package' => $package->fresh()->toArray(),
            ]);

            $this->closeModal();
            $this->isSubmitting = false;
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            // Handle network/server errors
            $this->networkError = $e->getMessage() ?: 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.';
            $this->isSubmitting = false;
        }
    }

    public function confirmDelete()
    {
        $this->showDeleteConfirmation = true;
        $this->networkError = ''; // Clear any existing errors
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
        $this->isDeleting = false;
        $this->networkError = '';
    }

    public function delete()
    {
        $this->isDeleting = true;
        $this->networkError = '';

        try {
            // Find and delete the package
            $package = Packet::find($this->packageId);
            if (! $package) {
                throw new \Exception('Paket tidak ditemukan.');
            }

            $package->delete();

            // Dispatch success event
            $this->dispatch('packageDeleted', [
                'message' => 'Paket berhasil dihapus',
                'id' => $this->packageId,
            ]);

            $this->closeModal();
        } catch (\Exception $e) {
            // Handle network/server errors
            $this->networkError = $e->getMessage() ?: 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.';
            $this->isDeleting = false;
        }
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

    public function cancel()
    {
        $this->resetErrorStates();
        $this->closeModal();
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
        $this->resetErrorStates();
        $this->closeModal();
    }

    // Reset error states and cleanup
    private function resetErrorStates()
    {
        $this->networkError = '';
        $this->isSubmitting = false;
        $this->isDeleting = false;
        $this->showDeleteConfirmation = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * Specify the modal size.
     */
    public static function modalMaxWidth(): string
    {
        return 'md';
    }

    public function render()
    {
        return view('livewire.admin.components.edit-package-modal');
    }
}
