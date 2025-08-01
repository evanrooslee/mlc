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
    } } } }" @keydown="handleKeydown" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <!-- Modal Header -->
    <div class="bg-white p-4 sm:px-6 sm:pt-6 sm:pb-4">
        <div class="flex justify-between items-center">
            <h2 id="modal-title" class="text-lg sm:text-xl font-medium text-gray-900 font-['Quicksand',_sans-serif]">
                Tambah Diskon</h2>
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
    <div class="p-4 sm:p-6">
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
                <!-- Kode Diskon Field -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Kode Diskon <span
                            class="text-red-500" aria-label="wajib diisi">*</span></label>
                    <input type="text" id="code" wire:model.live="code"
                        class="w-full px-3 py-2 border @error('code') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="DISKON20" maxlength="30" aria-required="true"
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

                <!-- Persentase Field -->
                <div>
                    <label for="percentage" class="block text-sm font-medium text-gray-700 mb-2">Persentase Diskon <span
                            class="text-red-500" aria-label="wajib diisi">*</span></label>
                    <div class="relative">
                        <input type="number" id="percentage" wire:model.live="percentage"
                            class="w-full px-3 pr-8 py-2 border @error('percentage') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            placeholder="20" min="1" max="100" aria-required="true"
                            aria-invalid="@error('percentage')true @else false @enderror"
                            @error('percentage') aria-describedby="percentage-error" @enderror>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">%</span>
                        </div>
                    </div>
                    @error('percentage')
                        <span id="percentage-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Status Field -->
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" id="is_valid" wire:model.live="is_valid"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_valid" class="ml-2 text-sm font-medium text-gray-700">Aktif</label>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Centang untuk mengaktifkan diskon ini</p>
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
                    <span wire:loading.remove wire:target="save">+ Tambah Diskon</span>
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
