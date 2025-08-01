<!-- Modal content -->
<div class="p-6">
  <!-- Icon -->
  <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 rounded-full">
    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
    </svg>
  </div>

  <!-- Header -->
  <h3 class="text-lg font-semibold text-center text-gray-900 mb-2">Hapus Diskon</h3>
  
  <!-- Content -->
  <div class="text-sm text-gray-600 mb-6">
    <p class="text-center mb-4">Apakah Anda yakin ingin menghapus diskon "<span class="font-semibold text-gray-900">{{ $code }}</span>"?</p>
    <p class="text-center text-red-600 mb-4">Tindakan ini tidak dapat dibatalkan. Diskon akan dihapus secara permanen.</p>
    
    <!-- Discount Details -->
    <div class="bg-gray-50 p-4 rounded-lg">
      <div class="grid grid-cols-1 gap-2 text-sm">
        <div class="flex justify-between">
          <span class="font-medium text-gray-700">Kode Diskon:</span>
          <span class="text-gray-900 font-mono">{{ $code }}</span>
        </div>
        <div class="flex justify-between">
          <span class="font-medium text-gray-700">Persentase:</span>
          <span class="text-gray-900">{{ $percentage }}%</span>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Footer -->
  <div class="flex justify-center gap-3">
    <button type="button" wire:click="$dispatch('closeModal')" 
      class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
      Batal
    </button>
    <button type="button" wire:click="deleteDiscount" 
      class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
      Ya, Hapus Diskon
    </button>
  </div>
</div>
