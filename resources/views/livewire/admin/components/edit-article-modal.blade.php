<div x-data="{
    modalOpen: true,
    focusableElements: [],
    firstFocusableElement: null,
    lastFocusableElement: null,
    init() {
        this.$nextTick(() => {
                    this.focusableElements = this.$el.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'); this.firstFocusableElement=this.focusableElements[0];
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
                Edit Artikel</h2>
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

        <form wire:submit.prevent="update">
            <div class="space-y-4 sm:space-y-6">
                <!-- Judul Artikel Field -->
                <div>
                    <label for="edit-title" class="block text-sm font-medium text-gray-700 mb-2">Judul Artikel <span
                            class="text-red-500" aria-label="wajib diisi">*</span></label>
                    <input type="text" id="edit-title" wire:model.live="title"
                        class="w-full px-3 py-2 border @error('title') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Judul artikel" maxlength="255" aria-required="true"
                        aria-invalid="@error('title')true @else false @enderror"
                        @error('title') aria-describedby="edit-title-error" @enderror>
                    @error('title')
                        <span id="edit-title-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Sumber Artikel Field -->
                <div>
                    <label for="edit-source" class="block text-sm font-medium text-gray-700 mb-2">Sumber Artikel <span
                            class="text-red-500" aria-label="wajib diisi">*</span></label>
                    <input type="text" id="edit-source" wire:model.live="source"
                        class="w-full px-3 py-2 border @error('source') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Nama sumber artikel" maxlength="255" aria-required="true"
                        aria-invalid="@error('source')true @else false @enderror"
                        @error('source') aria-describedby="edit-source-error" @enderror>
                    @error('source')
                        <span id="edit-source-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- URL Artikel Field -->
                <div>
                    <label for="edit-url" class="block text-sm font-medium text-gray-700 mb-2">URL Artikel <span
                            class="text-red-500" aria-label="wajib diisi">*</span></label>
                    <input type="url" id="edit-url" wire:model.live="url"
                        class="w-full px-3 py-2 border @error('url') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="https://example.com/artikel" maxlength="500" aria-required="true"
                        aria-invalid="@error('url')true @else false @enderror"
                        @error('url') aria-describedby="edit-url-error" @enderror>
                    @error('url')
                        <span id="edit-url-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Star Status Field -->
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="edit-is_starred" wire:model.live="is_starred"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            aria-describedby="star-description">
                        <label for="edit-is_starred" class="ml-2 text-sm font-medium text-gray-700">Jadikan Artikel
                            Berbintang</label>
                    </div>
                    <p id="star-description" class="mt-1 text-xs text-gray-500">
                        Artikel berbintang akan ditampilkan dengan ikon bintang di halaman utama. Hanya satu artikel
                        yang dapat berbintang.
                    </p>
                </div>

                <!-- Gambar Artikel Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Artikel</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 text-center bg-gray-50">
                        @if ($image)
                            <div class="flex flex-col items-center">
                                <div class="mb-4">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview gambar artikel"
                                        class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ $image->getClientOriginalName() }}</p>
                                <div class="flex gap-2">
                                    <button type="button"
                                        onclick="document.getElementById('edit-image-upload').click()"
                                        class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Ganti Gambar
                                    </button>
                                    <button type="button" wire:click="$set('image', null)"
                                        class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @elseif($current_image)
                            <div class="flex flex-col items-center">
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $current_image) }}" alt="Gambar artikel saat ini"
                                        class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                </div>
                                <p class="text-sm text-gray-600 mb-3">Gambar saat ini</p>
                                <button type="button" onclick="document.getElementById('edit-image-upload').click()"
                                    class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Ganti Gambar
                                </button>
                            </div>
                        @else
                            <div class="space-y-2 sm:space-y-3">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex justify-center text-sm text-gray-600">
                                    <label for="edit-image-upload"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Unggah gambar</span>
                                        <input id="edit-image-upload" wire:model.live="image" type="file"
                                            class="sr-only" accept="image/*">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                            </div>
                        @endif

                        <input id="edit-image-upload" wire:model.live="image" type="file" class="hidden"
                            accept="image/*">

                        @error('image')
                            <span class="block text-red-500 text-sm mt-2" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Footer -->
    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col sm:flex-row-reverse gap-2 sm:gap-1">
        <button type="button" wire:click="update" wire:loading.attr="disabled" wire:target="update, image"
            class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center">
            <span wire:loading.remove wire:target="update">Simpan Perubahan</span>
            <span wire:loading wire:target="update" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Menyimpan...
            </span>
        </button>
        <button type="button" wire:click="cancel" wire:loading.attr="disabled" wire:target="update, cancel"
            class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
            Batal
        </button>
    </div>
</div>
