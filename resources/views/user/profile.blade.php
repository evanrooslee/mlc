@extends('layouts.user-layout')
@section('title', 'MLC - Profile')
@section('content')
    <div class="relative">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="absolute top-6 right-4 rounded-full px-3 py-2 text-red-600 border border-red-600 flex items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12.75 5.25L11.6925 6.3075L13.6275 8.25H6V9.75H13.6275L11.6925 11.685L12.75 12.75L16.5 9M3 3.75H9V2.25H3C2.175 2.25 1.5 2.925 1.5 3.75V14.25C1.5 15.075 2.175 15.75 3 15.75H9V14.25H3V3.75Z"
                    fill="#BA2B15" />
            </svg>
            <span>
                Sign Out
            </span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </a>
        <div class="flex items-center p-8 pb-12 border-b border-[#C9C9C9]">
            <div class="w-32 h-32 rounded-full overflow-hidden mr-6">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=01A8DC&color=fff&size=128"
                    alt="User Avatar" class="w-full h-full object-cover">
            </div>
            <div class="">
                <p class="font-medium">Hello,</p>
                <h1 class="text-2xl font-bold mb-3">
                    {{ Auth::user()->name }}
                </h1>
                <button data-modal-target="edit-modal" data-modal-toggle="edit-modal"
                    class="bg-[#01a8dc] text-white font-bold py-3 px-4 rounded-full inline-flex items-center shadow-[0_2px_8px_rgba(0,0,0,0.25)] hover:bg-[#0195c9] transition-colors">
                    <span>Edit</span>
                    <svg class="ml-1" width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2.5 17.5V13.9583L13.5 2.97917C13.6667 2.82639 13.8508 2.70833 14.0525 2.625C14.2542 2.54167 14.4658 2.5 14.6875 2.5C14.9092 2.5 15.1244 2.54167 15.3333 2.625C15.5422 2.70833 15.7228 2.83333 15.875 3L17.0208 4.16667C17.1875 4.31944 17.3092 4.5 17.3858 4.70833C17.4625 4.91667 17.5006 5.125 17.5 5.33333C17.5 5.55556 17.4619 5.7675 17.3858 5.96917C17.3097 6.17083 17.1881 6.35472 17.0208 6.52083L6.04167 17.5H2.5ZM14.6667 6.5L15.8333 5.33333L14.6667 4.16667L13.5 5.33333L14.6667 6.5Z"
                            fill="white" />
                    </svg>
                </button>

                <div id="edit-modal" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-md max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div class="flex items-center justify-between p-4 md:p-5 rounded-t">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Edit Profil
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-hide="edit-modal">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <form class="p-4 md:p-5" action="{{ route('user.profile.update') }}" method="POST">
                                @csrf
                                <div class="grid gap-4 mb-4 grid-cols-2">
                                    <div class="col-span-1">
                                        <label for="name"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                            Lengkap</label>
                                        <input type="text" name="name" id="name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('name') border-red-500 @enderror"
                                            value="{{ old('name', Auth::user()->name) }}" required>
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-span-1">
                                        <label for="parent_name"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Ayah /
                                            Ibu</label>
                                        <input type="text" name="parent_name" id="parent_name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('parent_name') border-red-500 @enderror"
                                            value="{{ old('parent_name', Auth::user()->parent_name) }}" required>
                                        @error('parent_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-span-1">
                                        <label for="parents_phone_number"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor HP
                                            Orang Tua</label>
                                        <input type="text" name="parents_phone_number" id="parents_phone_number"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('parents_phone_number') border-red-500 @enderror"
                                            value="{{ old('parents_phone_number', Auth::user()->parents_phone_number) }}"
                                            required>
                                        @error('parents_phone_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-span-1">
                                        <label for="email"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                                            Aktif</label>
                                        <input type="email" name="email" id="email"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('email') border-red-500 @enderror"
                                            value="{{ old('email', Auth::user()->email) }}" required>
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-span-1">
                                        <label for="school"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asal
                                            Sekolah</label>
                                        <input type="text" name="school" id="school"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('school') border-red-500 @enderror"
                                            value="{{ old('school', Auth::user()->school) }}" required>
                                        @error('school')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-span-1">
                                        <label for="phone_number"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No HP
                                            Siswa</label>
                                        <input type="text" name="phone_number" id="phone_number"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('phone_number') border-red-500 @enderror"
                                            value="{{ old('phone_number', Auth::user()->phone_number) }}" required>
                                        @error('phone_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-span-1">
                                        <label for="grade"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelas</label>
                                        <select name="grade" id="grade"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @error('grade') border-red-500 @enderror"
                                            required>
                                            @foreach (range(7, 12) as $gradeOption)
                                                <option value="{{ $gradeOption }}"
                                                    {{ old('grade', Auth::user()->grade) == $gradeOption ? 'selected' : '' }}>
                                                    {{ $gradeOption }}</option>
                                            @endforeach
                                        </select>
                                        @error('grade')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit"
                                    class="block mx-auto text-white items-center bg-[#308F3F] hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between py-10 pl-6 pr-12">
            {{-- User Information Column --}}
            <div class="flex flex-col gap-4 w-1/3">
                <div class="flex flex-col gap-2">
                    <label class="block font-medium">Nama Lengkap</label>
                    <div class="mt-1 p-3 bg-[#EBEBEB] rounded-lg text-[#595959]">{{ Auth::user()->name }}</div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="block font-medium">Email Aktif</label>
                    <div class="mt-1 p-3 bg-[#EBEBEB] rounded-lg text-[#595959]">{{ Auth::user()->email }}</div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="block font-medium">No HP Siswa</label>
                    <div class="mt-1 p-3 bg-[#EBEBEB] rounded-lg text-[#595959]">
                        {{ Auth::user()->phone_number ?? 'Tidak ada' }}
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="block font-medium">Asal Sekolah</label>
                    <div class="mt-1 p-3 bg-[#EBEBEB] rounded-lg text-[#595959]">
                        {{ Auth::user()->school ?? 'Tidak ada' }}
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="block font-medium">Kelas</label>
                    <div class="mt-1 p-3 bg-[#EBEBEB] rounded-lg text-[#595959]">Kelas
                        {{ Auth::user()->grade ?? 'Tidak ada' }}
                    </div>
                </div>
            </div>

            {{-- Kelas Populer Column --}}
            <div class="flex flex-col items-center w-1/3 h-1/2">
                <h2 class="text-xl font-medium text-gray-800 mb-2">Kelas Populer</h2>
                <div class="popular-classes-container">

                    @if ($popularPackets->count() > 0)
                        {{-- Single Packet Display --}}
                        <div class="bg-[#ECECEC] rounded-lg p-6">
                            <div id="packet-display" class="packet-card bg-white overflow-hidden">
                                <div class="aspect-video w-full overflow-hidden relative">
                                    {{-- Loading placeholder --}}
                                    <div id="image-loading" class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <img id="packet-image"
                                        src="{{ $popularPackets->first()->image_url ?? asset('images/hero-illustration.png') }}"
                                        alt="{{ $popularPackets->first()->title ?? 'Packet Image' }}"
                                        class="packet-image w-full h-full object-cover" loading="lazy" width="400"
                                        height="225" onerror="handleImageError(this)" onload="handleImageLoad(this)">
                                </div>
                                <div class="p-4">
                                    <h4 id="packet-title" class="packet-title">
                                        {{ $popularPackets->first()->title ?? 'Untitled Packet' }}
                                    </h4>
                                </div>
                            </div>

                            {{-- Action Button --}}
                            <div class="flex items-center justify-center mt-6">
                                <a id="detail-btn" href="{{ route('beli-paket.show', $popularPackets->first()->id) }}"
                                    class="detail-button text-white font-bold p-2.5 rounded-full">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>

                        {{-- Pagination Controls --}}
                        @if ($popularPackets->count() > 1)
                            <div class="pagination-controls">
                                <button id="prev-btn"
                                    class="text-2xl font-bold transition-all duration-200 cursor-pointer select-none p-2 rounded-full w-12 h-12 flex items-center justify-center bg-transparent border-none disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled aria-label="Previous packet">
                                    <svg class="w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7">
                                        </path>
                                    </svg>
                                </button>
                                <button id="next-btn"
                                    class="text-2xl font-bold transition-all duration-200 cursor-pointer select-none p-2 rounded-full w-12 h-12 flex items-center justify-center bg-transparent border-none disabled:opacity-50 disabled:cursor-not-allowed"
                                    aria-label="Next packet">
                                    <svg class="w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            {{-- No-JS Fallback: Show all packets when JavaScript is disabled --}}
                            <noscript>
                                <div class="no-js-fallback">
                                    <p class="text-center text-sm text-gray-600 mb-4">JavaScript dinonaktifkan.
                                        Menampilkan
                                        semua kelas populer:</p>
                                    <div class="grid gap-4">
                                        @foreach ($popularPackets as $index => $packet)
                                            @if ($index > 0)
                                                {{-- Skip first packet as it's already shown above --}}
                                                <div class="packet-card bg-white overflow-hidden">
                                                    <div class="aspect-video w-full overflow-hidden">
                                                        <img src="{{ $packet->image_url }}"
                                                            alt="{{ $packet->title ?? 'Packet Image' }}"
                                                            class="packet-image w-full h-full object-cover" loading="lazy"
                                                            width="400" height="225"
                                                            onerror="this.src='{{ asset('images/hero-illustration.png') }}'">
                                                    </div>
                                                    <div class="p-4">
                                                        <h4 class="packet-title">
                                                            {{ $packet->title ?? 'Untitled Packet' }}
                                                        </h4>
                                                        <div class="mt-4 text-center">
                                                            <a href="{{ route('beli-paket.show', $packet->id) }}"
                                                                class="detail-button text-white font-bold py-2 px-6 rounded-full inline-block">
                                                                Lihat Detail
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </noscript>
                        @endif
                    @else
                        {{-- Empty State --}}
                        <div class="bg-[#ECECEC] rounded-lg p-6">
                            <div class="empty-state">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                <h3>Belum ada kelas populer tersedia</h3>
                                <p>Kelas populer akan muncul setelah ada pembelian paket.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- JavaScript for Popular Classes Pagination --}}
        @if ($popularPackets->count() > 1)
            @php
                $packetsData = $popularPackets->map(function ($packet) {
                    return [
                        'id' => $packet->id,
                        'title' => $packet->title ?? 'Untitled Packet',
                        'image_url' => $packet->image,
                    ];
                });
            @endphp
            <script>
                // Set global default image for the pagination component
                window.defaultPacketImage = '{{ asset('images/hero-illustration.png') }}';
            </script>
            @vite('resources/js/popular-classes-pagination.js')
            <script>
                // Initialize pagination when DOM is loaded
                document.addEventListener('DOMContentLoaded', function() {
                    try {
                        const packetsData = {!! json_encode($packetsData) !!};

                        if (packetsData && packetsData.length > 1) {
                            new PopularClassesPagination(packetsData);
                        }
                    } catch (error) {
                        console.error('Failed to initialize popular classes pagination:', error);
                    }
                });
            </script>
        @endif
    @endsection
