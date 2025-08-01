<div>
    <!-- Article Management Component -->
    <div class="mb-6">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Artikel Unggulan</h3>
        <p class="text-sm text-gray-500 mb-4">
            Kelola tiga artikel yang ditampilkan di landing page. Anda dapat menandai satu artikel sebagai unggulan.
        </p>

        <!-- Success/Error Messages -->
        @if (session()->has('message'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6" role="grid"
            aria-label="Daftar artikel">
            <!-- Article Cards -->
            @foreach ($articles as $article)
                <div class="border border-gray-200 rounded-lg bg-white shadow-sm overflow-hidden relative focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2"
                    x-data="{ showControls: false, showControlsOnFocus: false }" @mouseenter="showControls = true" @mouseleave="showControls = false"
                    role="gridcell">

                    <!-- Article Image -->
                    <div class="relative h-32 bg-gray-100">
                        @if ($article['image'])
                            <img src="{{ asset('storage/' . $article['image']) }}" alt="{{ $article['title'] }}"
                                class="w-full h-full object-cover"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <!-- Star Badge -->
                        @if ($article['is_starred'])
                            <div class="absolute top-2 right-2 bg-yellow-400 text-white p-1 rounded-full shadow-sm"
                                title="Artikel Unggulan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Article Info -->
                    <div class="p-3">
                        <h4 class="font-medium text-gray-800 text-sm mb-1 line-clamp-1"
                            id="article-{{ $article['id'] }}-title">{{ $article['title'] }}</h4>
                        <p class="text-gray-500 text-xs mb-2">{{ $article['source'] }}</p>

                        <!-- Controls (visible on hover/focus) -->
                        <div class="flex justify-between items-center mt-2"
                            :class="{
                                'opacity-100': showControls || showControlsOnFocus,
                                'opacity-0': !showControls && !
                                    showControlsOnFocus
                            }"
                            class="transition-opacity duration-200">

                            <!-- Star Toggle Button -->
                            <button wire:click="toggleStar({{ $article['id'] }})" wire:loading.attr="disabled"
                                wire:target="toggleStar({{ $article['id'] }})" @focus="showControlsOnFocus = true"
                                @blur="showControlsOnFocus = false"
                                class="flex items-center text-xs px-2 py-1 rounded-md transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500"
                                :class="{
                                    'bg-yellow-100 text-yellow-700 hover:bg-yellow-200': {{ $article['is_starred'] ? 'true' : 'false' }},
                                    'bg-gray-100 text-gray-700 hover:bg-gray-200': {{ $article['is_starred'] ? 'false' : 'true' }}
                                }"
                                aria-label="{{ $article['is_starred'] ? 'Hapus dari unggulan' : 'Tandai sebagai unggulan' }}"
                                title="{{ $article['is_starred'] ? 'Hapus dari unggulan' : 'Tandai sebagai unggulan' }}">

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1"
                                    :class="{ 'text-yellow-500': {{ $article['is_starred'] ? 'true' : 'false' }}, 'text-gray-400': {{ $article['is_starred'] ? 'false' : 'true' }} }"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                {{ $article['is_starred'] ? 'Unggulan' : 'Tandai' }}

                                <span wire:loading wire:target="toggleStar({{ $article['id'] }})" class="ml-1">
                                    <svg class="animate-spin h-3 w-3 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </span>
                            </button>

                            <!-- Edit Button -->
                            <button wire:click="editArticle({{ $article['id'] }})" @focus="showControlsOnFocus = true"
                                @blur="showControlsOnFocus = false"
                                class="flex items-center text-xs px-2 py-1 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500"
                                aria-label="Edit artikel {{ $article['title'] }}"
                                aria-describedby="article-{{ $article['id'] }}-title">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Placeholder Cards for Missing Articles -->
            @foreach ($placeholders as $index)
                <div
                    class="border border-dashed border-gray-300 rounded-lg bg-gray-50 overflow-hidden relative h-56 flex flex-col items-center justify-center text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm">Artikel belum tersedia</p>
                </div>
            @endforeach
        </div>

        <!-- Help Text -->
        <div class="text-xs text-gray-500 mt-4">
            <p>* Maksimal tiga artikel akan ditampilkan di landing page.</p>
            <p>* Hanya satu artikel yang dapat ditandai sebagai unggulan.</p>
        </div>
    </div>
</div>
