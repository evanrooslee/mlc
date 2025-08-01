<div x-data="{
    modalOpen: true,
    focusableElements: [],
    firstFocusableElement: null,
    lastFocusableElement: null,
    init() {
        this.$nextTick(() => {
            this.focusableElements = this.$el.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            this.firstFocusableElement = this.focusableElements[0];
            this.lastFocusableElement = this.focusableElements[this.focusableElements.length - 1];
            if (this.firstFocusableElement) {
                this.firstFocusableElement.focus();
            }
        });
    },
    handleKeydown(event) {
        if (event.key === 'Escape') {
            $wire.cancel();
        } else if (event.key === 'Tab') {
            if (event.shiftKey) {
                if (document.activeElement === this.firstFocusableElement) {
                    event.preventDefault();
                    this.lastFocusableElement.focus();
                }
            } else {
                if (document.activeElement === this.lastFocusableElement) {
                    event.preventDefault();
                    this.firstFocusableElement.focus();
                }
            }
        }
    }
}" @keydown="handleKeydown" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <!-- Modal Header -->
    <div class="bg-white p-4 sm:px-6 sm:pt-6 sm:pb-4">
        <div class="flex justify-between items-center">
            <h2 id="modal-title" class="text-lg sm:text-xl font-medium text-gray-900 font-['Quicksand',_sans-serif]">
                {{ $is_editing ? 'Edit Video' : 'Tambah Video' }}
            </h2>
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
                </div>
            </div>
        @endif

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
                <!-- Judul Video Field -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Video <span
                            class="text-red-500" aria-label="wajib diisi">*</span></label>
                    <input type="text" id="title" wire:model.live="title"
                        class="w-full px-3 py-2 border @error('title') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan judul video" maxlength="255" aria-required="true"
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

                <!-- Mata Pelajaran Field -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran <span
                            class="text-red-500" aria-label="wajib diisi">*</span></label>
                    <input type="text" id="subject" wire:model.live="subject"
                        class="w-full px-3 py-2 border @error('subject') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: Matematika, Fisika, Kimia" maxlength="100" aria-required="true"
                        aria-invalid="@error('subject')true @else false @enderror"
                        @error('subject') aria-describedby="subject-error" @enderror>
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

                <!-- Penerbit Field -->
                <div>
                    <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">Penerbit <span
                            class="text-red-500" aria-label="wajib diisi">*</span></label>
                    <input type="text" id="publisher" wire:model.live="publisher"
                        class="w-full px-3 py-2 border @error('publisher') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan nama penerbit" maxlength="100" aria-required="true"
                        aria-invalid="@error('publisher')true @else false @enderror"
                        @error('publisher') aria-describedby="publisher-error" @enderror>
                    @error('publisher')
                        <span id="publisher-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- File Video Field -->
                <div>
                    <label for="video" class="block text-sm font-medium text-gray-700 mb-2">
                        File Video 
                        @if (!$is_editing || !$existing_video)
                            <span class="text-red-500" aria-label="wajib diisi">*</span>
                        @endif
                    </label>
                    
                    @if ($is_editing && $existing_video)
                        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Video saat ini tersimpan</p>
                                    <p class="text-xs text-blue-600">Upload file baru untuk mengganti video yang ada</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <input type="file" id="video" wire:model="video" accept="video/*"
                        class="w-full px-3 py-2 border @error('video') border-red-300 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        @if (!$is_editing || !$existing_video) aria-required="true" @endif
                        aria-invalid="@error('video')true @else false @enderror"
                        @error('video') aria-describedby="video-error" @enderror>
                    
                    <p class="text-xs text-gray-500 mt-1">
                        Format yang didukung: MP4, AVI, MOV, WMV, FLV, WebM. Maksimal 150MB.
                    </p>
                    
                    @error('video')
                        <span id="video-error" class="text-red-500 text-sm mt-1 flex items-center" role="alert">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror

                    <!-- File upload progress -->
                    <div wire:loading wire:target="video" class="mt-2">
                        <div class="flex items-center text-sm text-blue-600">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengupload video...
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 sm:mt-8">
                <button type="button" wire:click="cancel"
                    class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium transition-colors duration-200">
                    Batal
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="save"
                    class="w-full sm:w-auto px-6 sm:px-8 py-2 sm:py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-opacity duration-200">
                    <span wire:loading.remove wire:target="save">
                        {{ $is_editing ? 'Perbarui Video' : 'Simpan Video' }}
                    </span>
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
