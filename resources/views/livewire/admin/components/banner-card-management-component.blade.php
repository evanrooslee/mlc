<div>
    <div class="mb-4">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium">Kelola Banner Cards</h3>
            <button wire:click="openCreateModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center space-x-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                <span>Tambah Card</span>
            </button>
        </div>
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

    <!-- Banner Cards Grid with Drag & Drop -->
    <div x-data="bannerCardSorter()" class="mb-6">
        <!-- Drag & Drop Instructions -->
        @if (count($bannerCards) > 1)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-700">
                        <strong>Tips:</strong> Seret dan lepas kartu untuk mengubah urutan tampilan. Perubahan akan
                        disimpan secara otomatis.
                    </p>
                </div>
            </div>
        @endif

        <div x-ref="sortableContainer" class="banner-cards-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($bannerCards as $card)
                <div data-card-id="{{ $card['id'] }}" x-ref="card-{{ $card['id'] }}"
                    class="banner-card bg-white rounded-lg shadow-md overflow-hidden border {{ $card['is_active'] ? 'border-green-200' : 'border-gray-200' }} cursor-move transition-all duration-200 hover:shadow-lg"
                    :class="{ 'banner-card-dragging': dragging && draggedId == {{ $card['id'] }} }">

                    <!-- Drag Handle -->
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <svg class="drag-handle h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8h16M4 16h16"></path>
                            </svg>
                            <span class="text-xs text-gray-500 font-medium">Seret untuk mengubah urutan</span>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            #{{ $card['display_order'] }}
                        </span>
                    </div>

                    <!-- Card Image -->
                    <div class="relative h-48 bg-gray-100">
                        @if ($card['background_image'])
                            <img src="{{ Storage::url($card['background_image']) }}" alt="{{ $card['title'] }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full bg-gray-200">
                                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-2 right-2">
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full {{ $card['is_active'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $card['is_active'] ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-4">
                        <h4 class="font-medium text-gray-900 mb-2 line-clamp-2">{{ $card['title'] }}</h4>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $card['description'] }}</p>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-2">
                                <button wire:click="editCard({{ $card['id'] }})"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full hover:bg-blue-100 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="toggleCardStatus({{ $card['id'] }})"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium {{ $card['is_active'] ? 'text-yellow-600 bg-yellow-50 hover:bg-yellow-100' : 'text-green-600 bg-green-50 hover:bg-green-100' }} rounded-full transition-colors">
                                    @if ($card['is_active'])
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                            </path>
                                        </svg>
                                        Nonaktifkan
                                    @else
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Aktifkan
                                    @endif
                                </button>
                            </div>
                            <button wire:click="deleteCard({{ $card['id'] }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus banner card ini?"
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-600 bg-red-50 rounded-full hover:bg-red-100 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada banner card</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat banner card pertama Anda.</p>
                    <div class="mt-6">
                        <button wire:click="openCreateModal"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tambah Banner Card
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <!-- Create/Edit Card Modal -->
    <div x-data="{ show: @entangle('showCardModal') }" x-show="show"
        @open-modal.window="if ($event.detail.id === 'banner-card-modal') show = true"
        @close-modal.window="if ($event.detail.id === 'banner-card-modal') show = false"
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
                class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full z-50 max-h-[90vh] overflow-y-auto">

                <!-- Modal Header -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $isEditing ? 'Edit Banner Card' : 'Tambah Banner Card' }}
                        </h3>
                        <button wire:click="closeModal" type="button"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md p-1">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <form wire:submit.prevent="saveCard">
                        <div class="space-y-6">
                            <!-- Title Field -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Judul <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" wire:model="title"
                                    class="w-full px-3 py-2 border @error('title') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                    placeholder="Masukkan judul banner card" maxlength="255">
                                @error('title')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Description Field -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description" wire:model="description" rows="3"
                                    class="w-full px-3 py-2 border @error('description') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"
                                    placeholder="Masukkan deskripsi banner card" maxlength="1000"></textarea>
                                @error('description')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Background Image Field -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Gambar Latar {{ !$isEditing ? '*' : '' }}
                                </label>
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                                    @if ($background_image)
                                        <div class="flex flex-col items-center">
                                            <div class="mb-4">
                                                <img src="{{ $background_image->temporaryUrl() }}"
                                                    alt="Preview gambar"
                                                    class="h-32 w-48 object-cover rounded-lg border border-gray-300">
                                            </div>
                                            <p class="text-sm text-gray-600 mb-3">
                                                {{ $background_image->getClientOriginalName() }}</p>
                                            <div class="flex gap-2">
                                                <button type="button"
                                                    onclick="document.getElementById('background-image-upload').click()"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                                                    Ganti Gambar
                                                </button>
                                                <button type="button" wire:click="$set('background_image', null)"
                                                    class="px-4 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center">
                                            <svg class="h-12 w-12 text-gray-400 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <button type="button"
                                                onclick="document.getElementById('background-image-upload').click()"
                                                class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                                                Pilih Gambar
                                            </button>
                                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG (Max: 2MB)</p>
                                        </div>
                                    @endif
                                    <input type="file" wire:model="background_image" accept="image/*"
                                        class="hidden" id="background-image-upload">
                                </div>
                                @error('background_image')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Display Order and Status -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Display Order Field -->
                                <div>
                                    <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Urutan Tampilan <span class="text-red-500">*</span>
                                    </label>
                                    <select id="display_order" wire:model="display_order"
                                        class="w-full px-3 py-2 border @error('display_order') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                        @for ($i = 1; $i <= 6; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('display_order')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Status Field -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <div class="flex items-center mt-3">
                                        <input type="checkbox" id="is_active" wire:model="is_active"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                                    </div>
                                    @error('is_active')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse mt-6 -mx-4 -mb-4">
                            <button type="submit" wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <span wire:loading.remove wire:target="saveCard">
                                    {{ $isEditing ? 'Perbarui' : 'Simpan' }}
                                </span>
                                <span wire:loading wire:target="saveCard">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    {{ $isEditing ? 'Memperbarui...' : 'Menyimpan...' }}
                                </span>
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
