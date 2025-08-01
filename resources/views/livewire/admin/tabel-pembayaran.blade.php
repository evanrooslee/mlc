<div>
  @if (session()->has('message'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
      {{ session('message') }}
    </div>
  @endif

  <div class="flex justify-between mb-4">
    <div></div>
    <div class="relative w-72">
      <input type="text" wire:model.live.debounce.100ms="search"
        class="w-full px-4 py-2 border border-[rgba(223,223,223,0.8)] rounded-md pr-10"
        placeholder="Cari nama siswa/orang tua, nomor HP, pesanan, atau status...">
      <div class="absolute inset-y-0 right-0 flex items-center pr-3">
        <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
            stroke="#7B7B7B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </div>
    </div>
  </div>

  <div class="border border-[rgba(223,223,223,0.8)] rounded-lg overflow-hidden overflow-x-auto">
    <table class="min-w-full">
      <thead>
        <tr class="bg-white">
          <th class="py-3 px-4 text-left">
            <button wire:click="sortByColumn('name')"
              class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
              <span>Nama</span>
              @if ($sortBy === 'name')
                @if ($sortDirection === 'asc')
                  <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                  </svg>
                @else
                  <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                  </svg>
                @endif
              @else
                <svg class="w-3 h-3 ml-1 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                </svg>
              @endif
            </button>
          </th>
          <th class="py-3 px-4 text-right text-xs text-black">
            Nomor HP Siswa
          </th>
          <th class="py-3 px-4 text-left text-xs text-black">
            <button wire:click="sortByColumn('parent_name')"
              class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
              <span>Nama Ayah/Ibu</span>
              @if ($sortBy === 'parent_name')
                @if ($sortDirection === 'asc')
                  <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                  </svg>
                @else
                  <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                  </svg>
                @endif
              @else
                <svg class="w-3 h-3 ml-1 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                </svg>
              @endif
            </button>
          </th>
          <th class="py-3 px-4 text-right text-xs text-black">
            Nomor HP Ayah/Ibu
          </th>
          <th class="py-3 px-4 text-left text-xs text-black">
            <button wire:click="sortByColumn('packets.title')"
              class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
              <span>Pesanan</span>
              @if ($sortBy === 'packets.title')
                @if ($sortDirection === 'asc')
                  <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                  </svg>
                @else
                  <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                  </svg>
                @endif
              @else
                <svg class="w-3 h-3 ml-1 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                </svg>
              @endif
            </button>
          </th>
          <th class="py-3 px-4 text-left">
            <button wire:click="sortByColumn('payments.status')"
              class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
              <span>Status</span>
              @if ($sortBy === 'payments.status')
                @if ($sortDirection === 'asc')
                  <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                  </svg>
                @else
                  <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                  </svg>
                @endif
              @else
                <svg class="w-3 h-3 ml-1 opacity-30" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                </svg>
              @endif
            </button>
          </th>
          <th class="py-3 px-4 text-left text-xs text-black">
            Aksi
          </th>
        </tr>
      </thead>
      <tbody>
        @forelse($payments as $payment)
          <tr class="border-t border-[rgba(223,223,223,0.25)]">
            <td class="py-2 px-4 text-xs">{{ $payment->student_name }}</td>
            <td class="py-2 px-4 text-xs text-right">{{ $payment->student_phone }}</td>
            <td class="py-2 px-4 text-xs">{{ $payment->parent_name }}</td>
            <td class="py-2 px-4 text-xs text-right">{{ $payment->parent_phone }}</td>
            <td class="py-2 px-4 text-xs">{{ $payment->pesanan }}</td>
            <td class="py-2 px-4 text-xs">
              @if ($payment->status === 'Sudah Bayar')
                <div class="inline-flex items-center rounded px-2 py-1">
                  <svg class="w-4 h-4 mr-1" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M5.57061 10.9999L2.24561 7.67493L3.07686 6.84368L5.57061 9.33743L10.9227 3.98535L11.7539 4.8166L5.57061 10.9999Z"
                      fill="#3CB84E" />
                  </svg>
                  <span class="text-[#3CB84E] font-bold">Sudah Bayar</span>
                </div>
              @else
                <div class="inline-flex items-center rounded px-2 py-1">
                  <svg class="w-4 h-4 mr-1" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M10.9758 2.99758H11.713V1.94434H3.28711V2.99758H4.02438V5.1643L4.24472 5.32187L6.59428 6.99989L4.24514 8.67791L4.0248 8.83548V11.0022H3.28711V12.0554H11.713V11.0022H10.9758V8.83548L10.7554 8.67791L8.40586 6.99989L10.755 5.32187L10.9753 5.1643L10.9758 2.99758ZM9.92252 2.99758H5.07762V4.62252L7.50007 6.35278L9.92252 4.62252V2.99758Z"
                      fill="#EBBE5B" />
                  </svg>
                  <span class="text-[#EBBE5B] font-bold">Belum Bayar</span>
                </div>
              @endif
            </td>
            <td class="py-2 px-4 text-xs">
              @if ($payment->status !== 'Sudah Bayar')
                <button wire:click="openVerificationModal({{ $payment->user_id }}, {{ $payment->packet_id }})"
                  class="bg-[#0882E6] rounded py-1 px-2 flex items-center hover:bg-[#0668B8] transition-colors">
                  <svg class="w-4 h-4 mr-1" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M8 14C8.78793 14 9.56815 13.8448 10.2961 13.5433C11.0241 13.2417 11.6855 12.7998 12.2426 12.2426C12.7998 11.6855 13.2417 11.0241 13.5433 10.2961C13.8448 9.56815 14 8.78793 14 8C14 7.21207 13.8448 6.43185 13.5433 5.7039C13.2417 4.97595 12.7998 4.31451 12.2426 3.75736C11.6855 3.20021 11.0241 2.75825 10.2961 2.45672C9.56815 2.15519 8.78793 2 8 2C6.4087 2 4.88258 2.63214 3.75736 3.75736C2.63214 4.88258 2 6.4087 2 8C2 9.5913 2.63214 11.1174 3.75736 12.2426C4.88258 13.3679 6.4087 14 8 14ZM7.84533 10.4267L11.1787 6.42667L10.1547 5.57333L7.288 9.01267L5.80467 7.52867L4.862 8.47133L6.862 10.4713L7.378 10.9873L7.84533 10.4267Z"
                      fill="white" />
                  </svg>
                  <span class="text-white font-semibold text-sm">Verifikasi</span>
                </button>
              @else
                <span class="text-gray-500">-</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="py-8 px-4 text-center text-gray-500">
              Tidak ada data pembayaran yang ditemukan
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $payments->links('livewire.admin.pagination') }}
  </div>
</div>
