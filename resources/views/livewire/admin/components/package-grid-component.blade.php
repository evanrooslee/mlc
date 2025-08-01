<div>
    <!-- Grid Layout for Paket Belajar -->
    <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 mb-6"
        role="grid" aria-label="Daftar paket belajar">
        <!-- Add Package Button (First Position) -->
        <div class="h-32 border-2 border-dashed border-gray-300 rounded-lg bg-white hover:border-blue-500 transition-colors duration-200 cursor-pointer flex items-center justify-center focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2"
            x-data="{}" role="gridcell">
            <button @click="$dispatch('openModal', { component: 'admin.components.add-package-modal' })"
                @keydown.enter="$dispatch('openModal', { component: 'admin.components.add-package-modal' })"
                @keydown.space.prevent="$dispatch('openModal', { component: 'admin.components.add-package-modal' })"
                class="w-full h-full flex flex-col items-center justify-center text-gray-400 hover:text-blue-500 transition-colors duration-200 focus:outline-none focus:text-blue-500 rounded-lg"
                aria-label="Tambah paket belajar baru">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-8 sm:w-8 mb-1" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="text-xs sm:text-sm font-medium">Tambah Paket</span>
            </button>
        </div>

        <!-- Dynamic Package Cards -->
        @foreach ($packages as $package)
            <div class="h-32 border border-gray-200 rounded-lg bg-white shadow-sm overflow-hidden relative focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2"
                x-data="{ showEdit: false, showEditOnFocus: false }" @mouseenter="showEdit = true" @mouseleave="showEdit = false" role="gridcell">
                <!-- Default State -->
                <div class="absolute inset-0 flex flex-col items-center justify-center transition-opacity duration-200 ease-in-out"
                    :class="{ 'opacity-100': !showEdit && !showEditOnFocus, 'opacity-0': showEdit || showEditOnFocus }">
                    <span class="text-gray-700 font-bold text-sm sm:text-base"
                        id="package-{{ $package['id'] }}-code">{{ $package['code'] }}</span>
                    <span class="text-gray-500 text-xs mt-1 px-2 truncate max-w-full">{{ $package['title'] }}</span>
                </div>
                <!-- Hover/Focus State -->
                <div class="absolute inset-0 flex items-center justify-center bg-blue-50 transition-opacity duration-200 ease-in-out"
                    :class="{ 'opacity-0': !showEdit && !showEditOnFocus, 'opacity-100': showEdit || showEditOnFocus }">
                    <button
                        class="flex items-center px-2 sm:px-3 py-1.5 bg-white border border-blue-500 text-blue-600 rounded-md shadow-sm hover:bg-blue-50 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm"
                        @click="$dispatch('openModal', { component: 'admin.components.edit-package-modal', arguments: { packageId: {{ $package['id'] }} } })"
                        @keydown.enter="$dispatch('openModal', { component: 'admin.components.edit-package-modal', arguments: { packageId: {{ $package['id'] }} } })"
                        @keydown.space.prevent="$dispatch('openModal', { component: 'admin.components.edit-package-modal', arguments: { packageId: {{ $package['id'] }} } })"
                        @focus="showEditOnFocus = true" @blur="showEditOnFocus = false"
                        aria-label="Edit paket {{ $package['title'] }}"
                        aria-describedby="package-{{ $package['id'] }}-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
