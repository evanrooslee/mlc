<div>
    <!-- Flash Message -->
    @if ($flash_message)
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
            x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)">
            {{ $flash_message }}
            <button wire:click="clearFlashMessage"
                class="absolute top-0 right-0 mt-2 mr-2 text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    <div class="flex justify-between mb-4">
        <div></div>
        <div class="relative w-72">
            <input type="text" wire:model.live.debounce.100ms="search"
                class="w-full px-4 py-2 border border-[rgba(223,223,223,0.8)] rounded-md pr-10"
                placeholder="Cari nama, email, nomor HP, nama ayah/ibu, sekolah, kelas, atau paket...">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
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
                    <th class="py-3 px-4 text-left text-xs text-black">
                        <button wire:click="sortByColumn('email')"
                            class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
                            <span>Email Aktif</span>
                            @if ($sortBy === 'email')
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
                    <th class="py-3 px-4 text-right text-xs text-black">
                        Nomor HP Ayah/Ibu
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
                    <th class="py-3 px-4 text-left text-xs text-black">
                        <button wire:click="sortByColumn('school')"
                            class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
                            <span>Sekolah</span>
                            @if ($sortBy === 'school')
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
                        <button wire:click="sortByColumn('grade')"
                            class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
                            <span>Kelas</span>
                            @if ($sortBy === 'grade')
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
                        <button wire:click="sortByColumn('packets')"
                            class="flex items-center text-xs text-black hover:text-blue-600 transition-colors">
                            <span>Paket yang Diikuti</span>
                            @if ($sortBy === 'packets')
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
                @forelse($students as $student)
                    <tr class="border-t border-[rgba(223,223,223,0.25)]">
                        <td class="py-2 px-4 text-xs">{{ $student->name }}</td>
                        <td class="py-2 px-4 text-xs">{{ $student->email }}</td>
                        <td class="py-2 px-4 text-xs text-right">{{ $student->phone_number }}</td>
                        <td class="py-2 px-4 text-xs text-right">{{ $student->parents_phone_number }}</td>
                        <td class="py-2 px-4 text-xs">{{ $student->parent_name ?? '-' }}</td>
                        <td class="py-2 px-4 text-xs">{{ $student->school }}</td>
                        <td class="py-2 px-4 text-xs">{{ $student->grade }}</td>
                        <td class="py-2 px-4 text-xs">
                            @if ($student->packets->isEmpty())
                                -
                            @else
                                {{ $student->packets->pluck('code')->implode(', ') }}
                            @endif
                        </td>
                        <td class="py-2 px-4 text-xs">
                            <div class="flex items-center gap-2">
                                {{-- Edit Button --}}
                                <button
                                    wire:click="$dispatch('openModal', {component: 'admin.components.edit-user', arguments: {user_id: {{ $student->id }}}})"
                                    class="border border-[#68A2DD] rounded px-2 py-1 flex items-center gap-2 group hover:bg-[#68A2DD]">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.25 15.75V12.5625L12.15 2.68125C12.3 2.54375 12.4658 2.4375 12.6473 2.3625C12.8288 2.2875 13.0192 2.25 13.2188 2.25C13.4183 2.25 13.612 2.2875 13.8 2.3625C13.988 2.4375 14.1505 2.55 14.2875 2.7L15.3187 3.75C15.4687 3.8875 15.5783 4.05 15.6473 4.2375C15.7163 4.425 15.7505 4.6125 15.75 4.8C15.75 5 15.7158 5.19075 15.6473 5.37225C15.5788 5.55375 15.4692 5.71925 15.3187 5.86875L5.4375 15.75H2.25ZM13.2 5.85L14.25 4.8L13.2 3.75L12.15 4.8L13.2 5.85Z"
                                            fill="#68A2DD" class="group-hover:fill-white transition-colors" />
                                    </svg>
                                    <span
                                        class="text-xs text-[#68A2DD] group-hover:text-white transition-colors">Edit</span>
                                </button>

                                {{-- Delete Button --}}
                                <button
                                    wire:click="$dispatch('openModal', {component: 'admin.components.delete-user', arguments: {id: {{ $student->id }}, name: '{{ $student->name }}', email: '{{ $student->email }}', school: '{{ $student->school }}', grade: '{{ $student->grade }}'}})"
                                    class="border border-[#BA2B15] rounded px-2 py-1 flex items-center gap-1 group hover:bg-[#BA2B15]">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.25 3H11.625L10.875 2.25H7.125L6.375 3H3.75V4.5H14.25M4.5 14.25C4.5 14.6478 4.65804 15.0294 4.93934 15.3107C5.22064 15.592 5.60218 15.75 6 15.75H12C12.3978 15.75 12.7794 15.592 13.0607 15.3107C13.342 15.0294 13.5 14.6478 13.5 14.25V5.25H4.5V14.25Z"
                                            fill="#EB6E6A" class="group-hover:fill-white transition-colors" />
                                    </svg>
                                    <span
                                        class="text-xs text-[#BA2B15] group-hover:text-white transition-colors">Hapus</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 px-4 text-center text-gray-500">
                            Tidak ada data siswa yang ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $students->links('livewire.admin.pagination') }}
    </div>


</div>
