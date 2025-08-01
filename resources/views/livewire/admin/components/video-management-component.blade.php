<div>
  @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
      {{ session('success') }}
    </div>
  @endif

  <!-- Loading State -->
  @if ($isLoading)
    <div class="flex items-center justify-center py-12">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <span class="ml-2 text-gray-600">Memuat video...</span>
    </div>
  @else
    <!-- Video Grid Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" role="grid"
      aria-label="Daftar video pembelajaran">
      @foreach ($featuredVideos as $index => $video)
        <div
          class="aspect-video bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden relative focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2"
          x-data="{ showEdit: false, showEditOnFocus: false }" @mouseenter="showEdit = true" @mouseleave="showEdit = false" role="gridcell">

          @if ($video)
            <!-- Video Content -->
            <div class="absolute inset-0 transition-opacity duration-200 ease-in-out"
              :class="{ 'opacity-100': !showEdit && !showEditOnFocus, 'opacity-0': showEdit || showEditOnFocus }">

              <!-- Video Thumbnail/Preview -->
              <div class="w-full h-2/3 bg-gray-100 flex items-center justify-center">
                @if ($video->video)
                  <!-- Video thumbnail placeholder - will be replaced with actual video element later -->
                  <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                @else
                  <!-- No video placeholder -->
                  <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none"
                      viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                  </div>
                @endif
              </div>

              <!-- Video Info -->
              <div class="p-3 h-1/3 flex flex-col justify-between">
                <div>
                  <h3 class="text-sm font-medium text-gray-900 truncate" id="video-{{ $video->id }}-title">
                    {{ $video->title }}
                  </h3>
                  <p class="text-xs text-gray-500 truncate">{{ $video->subject }}</p>
                </div>
                <p class="text-xs text-gray-400 truncate">{{ $video->publisher }}</p>
              </div>
            </div>

            <!-- Hover/Focus Edit State -->
            <div
              class="absolute inset-0 flex items-center justify-center bg-blue-50 bg-opacity-95 transition-opacity duration-200 ease-in-out"
              :class="{ 'opacity-0': !showEdit && !showEditOnFocus, 'opacity-100': showEdit || showEditOnFocus }">
              <button
                class="flex items-center px-3 py-2 bg-white border border-blue-500 text-blue-600 rounded-md shadow-sm hover:bg-blue-50 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm"
                @click="$dispatch('openModal', { component: 'admin.components.edit-video-modal', arguments: { videoId: {{ $video->id }} } })"
                @keydown.enter="$dispatch('openModal', { component: 'admin.components.edit-video-modal', arguments: { videoId: {{ $video->id }} } })"
                @keydown.space.prevent="$dispatch('openModal', { component: 'admin.components.edit-video-modal', arguments: { videoId: {{ $video->id }} } })" @focus="showEditOnFocus = true"
                @blur="showEditOnFocus = false" aria-label="Edit video {{ $video->title }}"
                aria-describedby="video-{{ $video->id }}-title">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit
              </button>
            </div>
          @else
            <!-- Empty Video Slot -->
            <div class="absolute inset-0 transition-opacity duration-200 ease-in-out"
              :class="{ 'opacity-100': !showEdit && !showEditOnFocus, 'opacity-0': showEdit || showEditOnFocus }">
              <div
                class="w-full h-full bg-gray-50 border-2 border-dashed border-gray-300 flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none"
                  viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span class="text-xs text-gray-500 text-center px-2">Video belum ditambahkan</span>
              </div>
            </div>

            <!-- Hover/Focus Edit State for Empty Slot -->
            <div
              class="absolute inset-0 flex items-center justify-center bg-blue-50 bg-opacity-95 transition-opacity duration-200 ease-in-out"
              :class="{ 'opacity-0': !showEdit && !showEditOnFocus, 'opacity-100': showEdit || showEditOnFocus }">
              <button
                class="flex items-center px-3 py-2 bg-white border border-blue-500 text-blue-600 rounded-md shadow-sm hover:bg-blue-50 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm"
                @click="$dispatch('openModal', { component: 'admin.components.edit-video-modal', arguments: { videoId: null } })"
                @keydown.enter="$dispatch('openModal', { component: 'admin.components.edit-video-modal', arguments: { videoId: null } })"
                @keydown.space.prevent="$dispatch('openModal', { component: 'admin.components.edit-video-modal', arguments: { videoId: null } })" @focus="showEditOnFocus = true"
                @blur="showEditOnFocus = false" aria-label="Tambah video pembelajaran slot {{ $index + 1 }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Video
              </button>
            </div>
          @endif
        </div>
      @endforeach
    </div>

    <!-- Info Text -->
    <div class="text-sm text-gray-600 bg-blue-50 p-4 rounded-lg">
      <div class="flex items-start">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
          <p class="font-medium text-blue-800 mb-1">Informasi Video Pembelajaran</p>
          <p>Sistem menampilkan 4 video pembelajaran pertama di halaman utama. Klik tombol "Edit" pada setiap
            slot untuk mengubah atau menambahkan video baru.</p>
        </div>
      </div>
    </div>
  @endif
</div>
