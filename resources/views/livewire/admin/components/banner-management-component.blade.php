<div>
    <div class="mb-4">
        <h3 class="text-lg font-medium mb-2">Atur Banner</h3>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="relative mb-6">
        <div class="w-full h-auto rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
            @if ($currentBannerUrl)
                <img src="{{ $currentBannerUrl }}" alt="Banner" class="w-full h-auto object-cover">
            @else
                <div class="flex items-center justify-center h-64 bg-gray-100">
                    <p class="text-gray-500">Belum ada banner</p>
                </div>
            @endif
        </div>

        <div
            class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
            <button wire:click="openUploadModal"
                class="bg-black bg-opacity-70 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition-all hover:bg-opacity-80">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                        clip-rule="evenodd" />
                </svg>
                <span>Ganti Banner</span>
            </button>
        </div>
    </div>

    <div class="flex justify-between">
        @if ($currentBannerUrl)
            <button wire:click="deleteBanner" onclick="return confirm('Apakah Anda yakin ingin menghapus banner ini?')"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 102 0v3a1 1 0 11-2 0V9zm4 0a1 1 0 10-2 0v3a1 1 0 102 0V9z"
                        clip-rule="evenodd" />
                </svg>
                <span>Hapus Banner</span>
            </button>
        @else
            <div></div>
        @endif

        <button wire:click="saveBannerChanges"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd" />
            </svg>
            <span>Simpan Perubahan</span>
        </button>
    </div>

    <!-- Upload Banner Modal -->
    <div x-data="{ show: @entangle('showUploadModal') }" x-show="show"
        @open-modal.window="if ($event.detail.id === 'banner-upload-modal') show = true"
        @close-modal.window="if ($event.detail.id === 'banner-upload-modal') show = false"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-30" x-on:click="show = false"></div>

            <div x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full z-50">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Unggah banner ini?
                            </h3>
                            <div class="mt-4">
                                <div x-data="{ isHovering: false, fileName: '' }" x-on:dragover.prevent="isHovering = true"
                                    x-on:dragleave.prevent="isHovering = false"
                                    x-on:drop.prevent="
                                        isHovering = false;
                                        if ($event.dataTransfer.files.length > 0) {
                                            const fileInput = document.getElementById('banner-upload');
                                            const dataTransfer = new DataTransfer();
                                            dataTransfer.items.add($event.dataTransfer.files[0]);
                                            fileInput.files = dataTransfer.files;
                                            fileInput.dispatchEvent(new Event('change'));
                                            fileName = $event.dataTransfer.files[0].name;
                                        }
                                    "
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 rounded-md"
                                    :class="{ 'border-blue-300 bg-blue-50': isHovering, 'border-gray-300': !isHovering }">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="banner-upload"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Unggah file</span>
                                                <input id="banner-upload" wire:model="banner" type="file"
                                                    class="sr-only"
                                                    x-on:change="fileName = $event.target.files[0].name">
                                            </label>
                                            <p class="pl-1">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, GIF hingga 2MB
                                        </p>
                                        <p x-show="fileName" x-text="'File dipilih: ' + fileName"
                                            class="text-sm text-blue-600 mt-2"></p>
                                    </div>
                                </div>
                                @error('banner')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="uploadBanner" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span wire:loading.remove wire:target="uploadBanner">Unggah</span>
                        <span wire:loading wire:target="uploadBanner">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Mengunggah...
                        </span>
                    </button>
                    <button wire:click="closeUploadModal" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
