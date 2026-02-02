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
                                    <svg class="w-4 h-4 mr-1" viewBox="0 0 14 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.57061 10.9999L2.24561 7.67493L3.07686 6.84368L5.57061 9.33743L10.9227 3.98535L11.7539 4.8166L5.57061 10.9999Z"
                                            fill="#3CB84E" />
                                    </svg>
                                    <span class="text-[#3CB84E] font-bold">Sudah Bayar</span>
                                </div>
                            @elseif ($payment->status === 'Batal')
                                <div class="inline-flex items-center rounded px-2 py-1">
                                    <svg class="w-4 h-4 mr-1" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14ZM5.96967 5.96967C6.26256 5.67678 6.73744 5.67678 7.03033 5.96967L8 6.93934L8.96967 5.96967C9.26256 5.67678 9.73744 5.67678 10.0303 5.96967C10.3232 6.26256 10.3232 6.73744 10.0303 7.03033L9.06066 8L10.0303 8.96967C10.3232 9.26256 10.3232 9.73744 10.0303 10.0303C9.73744 10.3232 9.26256 10.3232 8.96967 10.0303L8 9.06066L7.03033 10.0303C6.73744 10.3232 6.26256 10.3232 5.96967 10.0303C5.67678 9.73744 5.67678 9.26256 5.96967 8.96967L6.93934 8L5.96967 7.03033C5.67678 6.73744 5.67678 6.26256 5.96967 5.96967Z"
                                            fill="#DC2626" />
                                    </svg>
                                    <span class="text-[#DC2626] font-bold">Batal</span>
                                </div>
                            @else
                                <div class="inline-flex items-center rounded px-2 py-1">
                                    <svg class="w-4 h-4 mr-1" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.9758 2.99758H11.713V1.94434H3.28711V2.99758H4.02438V5.1643L4.24472 5.32187L6.59428 6.99989L4.24514 8.67791L4.0248 8.83548V11.0022H3.28711V12.0554H11.713V11.0022H10.9758V8.83548L10.7554 8.67791L8.40586 6.99989L10.755 5.32187L10.9753 5.1643L10.9758 2.99758ZM9.92252 2.99758H5.07762V4.62252L7.50007 6.35278L9.92252 4.62252V2.99758Z"
                                            fill="#EBBE5B" />
                                    </svg>
                                    <span class="text-[#EBBE5B] font-bold">Belum Bayar</span>
                                </div>
                            @endif
                        </td>
                        <td class="py-2 px-4 text-xs">
                            @if ($payment->status === 'Belum Bayar')
                                <div class="flex items-center gap-2">
                                    <button
                                        wire:click="openVerificationModal({{ $payment->user_id }}, {{ $payment->packet_id }})"
                                        class="bg-[#0882E6] rounded py-1 px-2 flex items-center hover:bg-[#0668B8] transition-colors">
                                        <svg class="w-4 h-4 mr-1" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8 14C8.78793 14 9.56815 13.8448 10.2961 13.5433C11.0241 13.2417 11.6855 12.7998 12.2426 12.2426C12.7998 11.6855 13.2417 11.0241 13.5433 10.2961C13.8448 9.56815 14 8.78793 14 8C14 7.21207 13.8448 6.43185 13.5433 5.7039C13.2417 4.97595 12.7998 4.31451 12.2426 3.75736C11.6855 3.20021 11.0241 2.75825 10.2961 2.45672C9.56815 2.15519 8.78793 2 8 2C6.4087 2 4.88258 2.63214 3.75736 3.75736C2.63214 4.88258 2 6.4087 2 8C2 9.5913 2.63214 11.1174 3.75736 12.2426C4.88258 13.3679 6.4087 14 8 14ZM7.84533 10.4267L11.1787 6.42667L10.1547 5.57333L7.288 9.01267L5.80467 7.52867L4.862 8.47133L6.862 10.4713L7.378 10.9873L7.84533 10.4267Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-white font-semibold text-sm">Verifikasi</span>
                                    </button>
                                    <button
                                        wire:click="openCancelPaymentModal({{ $payment->user_id }}, {{ $payment->packet_id }})"
                                        class="bg-red-600 rounded py-1 px-2 flex items-center hover:bg-red-700 transition-colors">
                                        <svg class="w-4 h-4 mr-1" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8 14C11.3137 14 14 11.3137 14 8C14 4.68629 11.3137 2 8 2C4.68629 2 2 4.68629 2 8C2 11.3137 4.68629 14 8 14ZM5.96967 5.96967C6.26256 5.67678 6.73744 5.67678 7.03033 5.96967L8 6.93934L8.96967 5.96967C9.26256 5.67678 9.73744 5.67678 10.0303 5.96967C10.3232 6.26256 10.3232 6.73744 10.0303 7.03033L9.06066 8L10.0303 8.96967C10.3232 9.26256 10.3232 9.73744 10.0303 10.0303C9.73744 10.3232 9.26256 10.3232 8.96967 10.0303L8 9.06066L7.03033 10.0303C6.73744 10.3232 6.26256 10.3232 5.96967 10.0303C5.67678 9.73744 5.67678 9.26256 5.96967 8.96967L6.93934 8L5.96967 7.03033C5.67678 6.73744 5.67678 6.26256 5.96967 5.96967Z"
                                                fill="white" />
                                        </svg>
                                        <span class="text-white font-semibold text-sm">Batalkan</span>
                                    </button>
                                </div>
                            @elseif ($payment->status === 'Batal')
                                <button
                                    wire:click="openDeletePaymentModal({{ $payment->user_id }}, {{ $payment->packet_id }})"
                                    class="bg-red-600 rounded py-1 px-2 flex items-center hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6 2.5C6 2.22386 6.22386 2 6.5 2H9.5C9.77614 2 10 2.22386 10 2.5V3H12C12.2761 3 12.5 3.22386 12.5 3.5C12.5 3.77614 12.2761 4 12 4H11.5V12C11.5 12.5523 11.0523 13 10.5 13H5.5C4.94772 13 4.5 12.5523 4.5 12V4H4C3.72386 4 3.5 3.77614 3.5 3.5C3.5 3.22386 3.72386 3 4 3H6V2.5ZM6 5C6 4.72386 6.22386 4.5 6.5 4.5C6.77614 4.5 7 4.72386 7 5V11C7 11.2761 6.77614 11.5 6.5 11.5C6.22386 11.5 6 11.2761 6 11V5ZM9 5C9 4.72386 9.22386 4.5 9.5 4.5C9.77614 4.5 10 4.72386 10 5V11C10 11.2761 9.77614 11.5 9.5 11.5C9.22386 11.5 9 11.2761 9 11V5Z"
                                            fill="white" />
                                    </svg>
                                    <span class="text-white font-semibold text-sm">Hapus</span>
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
