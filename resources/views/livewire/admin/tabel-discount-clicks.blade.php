<div>
    <div class="flex justify-between mb-4">
        <div></div>
        <div class="relative w-72">
            <input type="text" wire:model.live.debounce.100ms="search"
                class="w-full px-4 py-2 border border-[rgba(223,223,223,0.8)] rounded-md pr-10"
                placeholder="Cari nomor HP atau nama...">
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
                        <button wire:click="sortByColumn('phone_number')"
                            class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
                            <span>Nomor HP</span>
                            @if ($sortBy === 'phone_number')
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
                        Nama
                    </th>
                    <th class="py-3 px-4 text-left">
                        <button wire:click="sortByColumn('clicked_at')"
                            class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
                            <span>Time Stamp</span>
                            @if ($sortBy === 'clicked_at')
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
                </tr>
            </thead>
            <tbody>
                @forelse($discountClicks as $click)
                    <tr class="border-t border-[rgba(223,223,223,0.25)]">
                        <td class="py-2 px-4 text-xs">{{ $click->phone_number }}</td>
                        <td class="py-2 px-4 text-xs">
                            {{ $click->user_name ?? ($click->user ? $click->user->name : '-') }}
                        </td>
                        <td class="py-2 px-4 text-xs">{{ $click->clicked_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 px-4 text-center text-gray-500">
                            Belum ada data pengguna yang mengklik diskon
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $discountClicks->links('livewire.admin.pagination') }}
    </div>
</div>
