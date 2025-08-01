<div>
  <!-- Modal header -->
  <div class="flex items-center justify-between p-4 border-b border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900">Edit Data Siswa</h3>
    <button type="button" class="text-gray-400 hover:text-gray-600" wire:click="$dispatch('closeModal')">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
  </div>

  <!-- Modal content -->
  <form wire:submit="updateStudent" class="p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
          Nama Siswa <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" wire:model="name" 
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('name')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
          Email <span class="text-red-500">*</span>
        </label>
        <input type="email" id="email" wire:model="email" 
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('email')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>

      <!-- Phone Number -->
      <div>
        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
          Nomor HP Siswa <span class="text-red-500">*</span>
        </label>
        <input type="text" id="phone_number" wire:model="phone_number" 
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('phone_number')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>

      <!-- Parents Phone Number -->
      <div>
        <label for="parents_phone_number" class="block text-sm font-medium text-gray-700 mb-1">
          Nomor HP Orang Tua
        </label>
        <input type="text" id="parents_phone_number" wire:model="parents_phone_number" 
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('parents_phone_number')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>

      <!-- School -->
      <div>
        <label for="school" class="block text-sm font-medium text-gray-700 mb-1">
          Sekolah <span class="text-red-500">*</span>
        </label>
        <input type="text" id="school" wire:model="school" 
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('school')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>

      <!-- Grade -->
      <div>
        <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">
          Kelas <span class="text-red-500">*</span>
        </label>
        <input type="text" id="grade" wire:model="grade" 
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        @error('grade')
          <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
        @enderror
      </div>
    </div>

    <!-- Modal footer -->
    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
      <button type="button" wire:click="$dispatch('closeModal')" 
        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
        Batal
      </button>
      <button type="submit" 
        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
        Simpan Perubahan
      </button>
    </div>
  </form>
</div>