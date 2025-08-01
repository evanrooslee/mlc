<div>
  <!-- Close Button -->
  <div class="flex justify-end p-4">
    <button type="button" wire:click="cancel" class="text-gray-400 hover:text-gray-600 transition-colors">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- Modal Content -->
  <div class="px-6 pb-6 max-h-[90vh] overflow-y-auto">
    <!-- Warning Icon and Text -->
    <div class="text-center mb-6">
      <div class="mx-auto w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
      </div>
      <h3 class="text-lg font-medium text-gray-900 text-center">
        Apakah anda ingin memverifikasi bahwa siswa berikut telah menyelesaikan pembayaran?
      </h3>
    </div>

    <!-- Payment Details Form -->
    <div class="space-y-4 mb-6">
      <!-- Nama Siswa -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Siswa</label>
        <input type="text" value="{{ $student_name }}" disabled 
               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
      </div>

      <!-- Nomor HP Siswa -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP Siswa</label>
        <input type="text" value="{{ $student_phone }}" disabled 
               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
      </div>

      <!-- Nama Ayah/Ibu -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah/Ibu</label>
        <input type="text" value="{{ $parent_name }}" disabled 
               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
      </div>

      <!-- Nomor HP Ayah/Ibu -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP Ayah/Ibu</label>
        <input type="text" value="{{ $parent_phone }}" disabled 
               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
      </div>

      <!-- Pesanan -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Pesanan</label>
        <input type="text" value="{{ $pesanan }}" disabled 
               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600">
      </div>
    </div>

    <!-- Confirm Button -->
    <div class="flex justify-center">
      <button wire:click="confirmPayment" 
              class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition-colors">
        Konfirmasi Pembayaran
      </button>
    </div>
  </div>
</div>