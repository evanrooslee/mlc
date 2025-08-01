<div x-data="{
    modalOpen: true,
    focusableElements: [],
    firstFocusableElement: null,
    lastFocusableElement: null,
    init() {
        this.$nextTick(() => {
                    this.focusableElements = this.$el.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex=\"-1\"])'); this.firstFocusableElement=this.focusableElements[0];
    this.lastFocusableElement=this.focusableElements[this.focusableElements.length - 1]; if (this.firstFocusableElement)
    { this.firstFocusableElement.focus(); } }); }, handleKeydown(event) { if (event.key === 'Escape') { $wire.cancel();
    } else if (event.key === 'Tab') { if (event.shiftKey) { if (document.activeElement===this.firstFocusableElement)
    { event.preventDefault(); this.lastFocusableElement.focus(); } } else { if
    (document.activeElement===this.lastFocusableElement) { event.preventDefault(); this.firstFocusableElement.focus(); }
    } } } }" @keydown="handleKeydown" role="dialog" aria-modal="true" aria-labelledby="modal-title"
    class="flex flex-col h-full max-h-[90vh] w-full">
    <!-- Modal Header -->
    <div class="bg-white p-4 sm:px-6 sm:pt-6 sm:pb-4">
        <div class="flex justify-between items-center">
            <h2 id="modal-title" class="text-lg sm:text-xl font-medium text-gray-900 font-['Quicksand',_sans-serif]">
                Tambahkan Paket</h2>
            <button type="button" wire:click="cancel"
                class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md p-1"
                aria-label="Tutup modal">
                <svg class="h-5 w-5 sm:h-6 sm:w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Modal Body -->
    <div class="p-4 sm:p-6 flex-grow overflow-y-auto">
        <!-- Network Error Alert -->
        @if ($networkError)
            <div class="mb-4 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-md" role="alert" aria-live="polite">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ $networkError }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button type="button" wire:click="$set('networkError', '')"
                                class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600"
                                aria-label="Tutup pesan error">
                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Validation Summary -->
        @if ($errors->any())
            <div class="mb-4 p-3 sm:p-4 bg-yellow-50 border border-yellow-200 rounded-md" role="alert"
                aria-live="polite">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Mohon periksa kembali form Anda</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Terdapat {{ $errors->count() }} kesalahan yang perlu diperbaiki sebelum menyimpan.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="space-y-4 sm:space-y-6">
                <!-- First Row: Judul Paket and Harga -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Judul Paket Field -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Paket <span
                                class="text-red-500" aria-label="wajib diisi">*</span></label>
                        <input type="text" id="title" wire:model.live="title"
                            class="w-full px-3 py-2 border @error('title') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="Paket Lengkap Matematika - Kelas 12" maxlength="255" aria-required="true"
                            aria-invalid="@error('title')true @else false @enderror"
                            @error('title') aria-describedby="title-error" @enderror>
                        @error('title')
                            <span id="title-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Harga Field -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga <span
                                class="text-red-500" aria-label="wajib diisi">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">Rp</span>
                            </div>
                            <input type="number" id="price" wire:model.live="price"
                                class="w-full pl-10 pr-3 py-2 border @error('price') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                placeholder="350000" min="0" max="99999999" aria-required="true"
                                aria-invalid="@error('price')true @else false @enderror"
                                @error('price') aria-describedby="price-error" @enderror>
                        </div>
                        @error('price')
                            <span id="price-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Second Row: Kode Paket and Grade -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Kode Paket Field -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Kode Paket <span
                                class="text-red-500" aria-label="wajib diisi">*</span></label>
                        <input type="text" id="code" wire:model.live="code"
                            class="w-full px-3 py-2 border @error('code') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="MAT12001" maxlength="50" aria-required="true"
                            aria-invalid="@error('code')true @else false @enderror"
                            @error('code') aria-describedby="code-error" @enderror>
                        @error('code')
                            <span id="code-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Grade Field -->
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">Kelas <span
                                class="text-red-500" aria-label="wajib diisi">*</span></label>
                        <select id="grade" wire:model.live="grade"
                            class="w-full px-3 py-2 border @error('grade') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            aria-required="true"
                            aria-invalid="@error('grade')true @else false @enderror"
                            @error('grade') aria-describedby="grade-error" @enderror>
                            <option value="">Pilih Kelas</option>
                            @for($i = 7; $i <= 12; $i++)
                                <option value="{{ $i }}">Kelas {{ $i }}</option>
                            @endfor
                        </select>
                        @error('grade')
                            <span id="grade-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Third Row: Subject and Type -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Subject Field -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran <span
                                class="text-red-500" aria-label="wajib diisi">*</span></label>
                        <select id="subject" wire:model.live="subject"
                            class="w-full px-3 py-2 border @error('subject') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            aria-required="true"
                            aria-invalid="@error('subject')true @else false @enderror"
                            @error('subject') aria-describedby="subject-error" @enderror>
                            <option value="">Pilih Mata Pelajaran</option>
                            <option value="Matematika">Matematika</option>
                            <option value="Fisika">Fisika</option>
                            <option value="Kimia">Kimia</option>
                        </select>
                        @error('subject')
                            <span id="subject-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- Type Field -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Paket <span
                                class="text-red-500" aria-label="wajib diisi">*</span></label>
                        <select id="type" wire:model.live="type"
                            class="w-full px-3 py-2 border @error('type') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            aria-required="true"
                            aria-invalid="@error('type')true @else false @enderror"
                            @error('type') aria-describedby="type-error" @enderror>
                            <option value="">Pilih Tipe</option>
                            <option value="standard">Standard</option>
                            <option value="premium">Premium</option>
                        </select>
                        @error('type')
                            <span id="type-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Fourth Row: Diskon Section -->
                <div class="grid grid-cols-1 gap-4 sm:gap-6">

                    <!-- Diskon Section -->
                    <div>
                        <div class="flex items-center mb-3">
                            <input type="checkbox" id="has_discount" wire:model.live="has_discount"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="has_discount" class="ml-2 text-sm font-medium text-gray-700">Tambahkan Diskon</label>
                        </div>

                        <div x-show="$wire.has_discount" x-transition>
                            <div class="space-y-4">
                                <!-- Pilih Diskon Field -->
                                <div class="relative">
                                    <label for="discount_search"
                                        class="block text-sm font-medium text-gray-700 mb-2">Pilih Diskon <span
                                            class="text-red-500" aria-label="wajib diisi">*</span></label>
                                    <div class="relative">
                                        <input type="text" id="discount_search" wire:model.live="discount_search"
                                            class="w-full px-3 py-2 border @error('selected_discount_id') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-10"
                                            placeholder="Cari kode diskon atau persentase..."
                                            autocomplete="off"
                                            aria-invalid="@error('selected_discount_id')true @else false @enderror"
                                            @error('selected_discount_id') aria-describedby="discount-error" @enderror>
                                        
                                        @if($selected_discount_id)
                                            <button type="button" wire:click="clearDiscountSelection"
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Dropdown Results -->
                                    @if($show_discount_dropdown && count($discount_search_results) > 0)
                                        <div class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                            @foreach($discount_search_results as $discount)
                                                <button type="button" wire:click="selectDiscount({{ $discount->id }})"
                                                    class="w-full px-3 py-2 text-left hover:bg-gray-50 border-b border-gray-100 last:border-b-0 focus:outline-none focus:bg-blue-50">
                                                    <div class="flex justify-between items-center">
                                                        <span class="font-mono text-sm text-gray-900">{{ $discount->code }}</span>
                                                        <span class="text-sm text-blue-600 font-medium">{{ $discount->percentage }}%</span>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Status: <span class="text-green-600">Aktif</span>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @elseif($show_discount_dropdown && strlen($discount_search) >= 1)
                                        <div class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg py-2">
                                            <div class="px-3 py-2 text-sm text-gray-500 text-center">
                                                Tidak ada diskon ditemukan
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @error('selected_discount_id')
                                        <span id="discount-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    
                                    <div class="text-xs text-gray-500 mt-1">
                                        <p>Ketik untuk mencari diskon berdasarkan kode atau persentase</p>
                                        <p class="mt-1">
                                            <span class="font-medium">Tip:</span> 
                                            Ketik <code class="bg-gray-100 px-1 rounded text-gray-700">~</code> untuk melihat semua diskon yang tersedia
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manfaat Field -->
                <div>
                    <label for="benefit" class="block text-sm font-medium text-gray-700 mb-2">Manfaat Paket</label>
                    <textarea id="benefit" wire:model.live="benefit" rows="4"
                        class="w-full px-3 py-2 border @error('benefit') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"
                        placeholder="Kurikulum matematika lengkap untuk kelas 12 termasuk kalkulus, statistik, dan aljabar lanjutan.&#10;Dilengkapi dengan tutorial video, latihan soal, dan simulasi ujian.&#10;Akses ke forum diskusi untuk tanya jawab dengan tutor." maxlength="2000"
                        aria-invalid="@error('benefit')true @else false @enderror"
                        @error('benefit') aria-describedby="benefit-error" @enderror></textarea>
                    <div class="text-xs text-gray-500 mt-1">Pisahkan setiap manfaat dengan baris baru untuk tampilan yang lebih rapi</div>
                    @error('benefit')
                        <span id="benefit-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Gambar Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Paket</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-8 text-center bg-gray-50">
                        @if($image)
                            <div class="flex flex-col items-center">
                                <div class="mb-4">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview gambar paket" class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ $image->getClientOriginalName() }}</p>
                                <div class="flex gap-2">
                                    <button type="button" onclick="document.getElementById('image-upload').click()"
                                        class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Ganti Gambar
                                    </button>
                                    <button type="button" wire:click="$set('image', null)"
                                        class="px-3 sm:px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center">
                                <div class="border-2 border-dashed border-gray-400 rounded-lg p-3 sm:p-4 mb-3">
                                    <svg class="h-6 w-6 sm:h-8 sm:w-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <button type="button" onclick="document.getElementById('image-upload').click()"
                                    class="px-3 sm:px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    aria-label="Pilih file gambar untuk paket">
                                    Pilih Gambar
                                </button>
                                <p id="image-help" class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF (Max: 2MB)</p>
                                <p class="text-xs text-gray-400 mt-1">Jika tidak dipilih, akan menggunakan gambar default</p>
                            </div>
                        @endif
                        <input type="file" wire:model="image" accept="image/*" class="hidden"
                            id="image-upload" aria-describedby="image-help">
                    </div>
                    @error('image')
                        <span class="text-red-500 text-sm mt-1 block" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 sm:mt-8">
                <button type="button" wire:click="cancel"
                    class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-opacity duration-200">
                    <span wire:loading.remove wire:target="save">+ Buat Paket</span>
                    <span wire:loading wire:target="save" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
