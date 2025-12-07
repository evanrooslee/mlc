@extends('layouts.user-layout')
@section('title', 'MLC - Kelas')
@section('content')
    <div class="relative mb-10">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="hidden md:flex absolute top-6 right-4 rounded-full px-3 py-2 text-red-600 border border-red-600 items-center gap-2">
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

        <div class="flex flex-col md:flex-row items-center p-4 md:p-8 pb-8 md:pb-12 border-b border-[#C9C9C9] text-center md:text-left">
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden mb-4 md:mb-0 md:mr-6">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=01A8DC&color=fff&size=128"
                    alt="User Avatar" class="w-full h-full object-cover">
            </div>
            <div>
                <p class="font-medium">Hello,</p>
                <h1 class="text-xl md:text-2xl font-bold mt-2 md:mt-3">
                    {{ Auth::user()->name }}
                </h1>
            </div>
        </div>

        <div class="mx-4 md:mx-8 my-4">
            {{-- Paket Aktif --}}
            <div class="flex flex-col inset-shadow-[0px_2px_4px_rgba(0,0,0,0.25)] rounded-lg border border-[#C9C9C9] mb-6">
                <div class="border-b border-[#C9C9C9] p-2.5 pb-6">
                    <h2 class="font-medium text-lg">Paket Aktif</h2>
                </div>

                @if ($activePackets->isNotEmpty())
                    <div
                        class="grid grid-cols-1 md:grid-cols-4 gap-6 p-3 {{ $activePackets->count() >= 3 ? 'hidden md:grid' : '' }}">
                        @foreach ($activePackets as $packet)
                            <a href="https://murid.bimbelterdekat.com/#/login" target="_blank"
                                class="bg-white rounded-lg overflow-hidden shadow-[4px_0_8px_rgba(0,0,0,0.25)]">
                                <img src="{{ $packet->image_url }}" alt="{{ $packet->title }}"
                                    class="w-full h-40 object-cover">
                                <div class="p-4">
                                    <h3 class="font-medium text-center md:text-left">{{ $packet->title }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Mobile View for Active Packets (if >= 3) --}}
                    @if ($activePackets->count() >= 3)
                        <div class="md:hidden p-3" x-data="{ expanded: false }">
                            <div class="grid grid-cols-1 gap-4 mb-4">
                                @foreach ($activePackets as $index => $packet)
                                    <a href="https://murid.bimbelterdekat.com/#/login" target="_blank"
                                        class="bg-white rounded-lg overflow-hidden shadow-[4px_0_8px_rgba(0,0,0,0.25)] {{ $index >= 2 ? 'hidden' : '' }}"
                                        :class="{ 'hidden': !expanded && {{ $index }} >= 2 }">
                                        <img src="{{ $packet->image_url }}" alt="{{ $packet->title }}"
                                            class="w-full h-40 object-cover">
                                        <div class="p-4">
                                            <h3 class="font-medium text-center">{{ $packet->title }}</h3>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <button @click="expanded = true" x-show="!expanded"
                                class="block w-full py-2 text-center text-[#01a8dc] border border-[#01a8dc] rounded-lg font-medium hover:bg-[#01a8dc] hover:text-white transition-colors">
                                Lihat Semua
                            </button>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 p-2.5"">Anda belum memiliki paket aktif.</p>
                @endif
            </div>

            {{-- Paket Lainnya --}}
            <div class="flex flex-col inset-shadow-[0px_2px_4px_rgba(0,0,0,0.25)] rounded-lg border border-[#C9C9C9] mb-6">
                <div class="border-b border-[#C9C9C9] p-2.5 pb-6">
                    <h2 class="font-medium text-lg">Paket Lainnya</h2>
                </div>
                {{-- Mobile View (Horizontal Scroll, All Packets) --}}
                @if ($allOtherPackets->isNotEmpty())
                    <div class="flex md:hidden overflow-x-auto pb-4 gap-4 m-3 snap-x snap-mandatory">
                        @foreach ($allOtherPackets as $packet)
                            <div
                                class="bg-white rounded-lg shadow-md overflow-hidden max-w-[200px] snap-center flex-shrink-0">
                                <img src="{{ $packet->image_url }}" alt="{{ $packet->title }}"
                                    class="w-full h-40 object-cover">
                                <div class="p-2">
                                    <h3 class="font-medium text-center">{{ Str::limit($packet->title, 35) }}</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Desktop View (Grid, Paginated) --}}
                @if ($allOtherPackets->isNotEmpty())
                    <div id="desktop-packets-grid" class="hidden md:grid md:grid-cols-3 lg:grid-cols-5 gap-4 m-3">
                        {{-- Content will be populated by JS --}}
                    </div>
                @else
                    <p class="text-gray-500 p-3 hidden md:block">Tidak ada paket lainnya yang tersedia saat ini.</p>
                @endif
            </div>
        </div>

        {{-- Desktop Pagination Controls --}}
        <div class="hidden md:flex justify-center items-center mt-5 gap-8 text-3xl">
            <button id="desktop-prev-btn" class="bg-transparent border-none p-0 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.390382 9.83505C0.140421 9.58501 0 9.24594 0 8.89238C0 8.53883 0.140421 8.19975 0.390382 7.94972L7.93305 0.407051C8.05604 0.279704 8.20317 0.178128 8.36584 0.108249C8.52851 0.0383705 8.70347 0.00158876 8.88051 5.03418e-05C9.05755 -0.00148808 9.23312 0.0322479 9.39699 0.0992891C9.56085 0.16633 9.70972 0.265334 9.83491 0.390525C9.9601 0.515715 10.0591 0.664584 10.1261 0.828446C10.1932 0.992307 10.2269 1.16788 10.2254 1.34492C10.2238 1.52196 10.1871 1.69692 10.1172 1.85959C10.0473 2.02226 9.94573 2.16939 9.81838 2.29238L3.21838 8.89238L9.81838 15.4924C10.0613 15.7439 10.1957 16.0807 10.1926 16.4303C10.1896 16.7798 10.0494 17.1143 9.80214 17.3615C9.55493 17.6087 9.22051 17.7489 8.87092 17.752C8.52132 17.755 8.18452 17.6206 7.93305 17.3777L0.390382 9.83505Z"
                        fill="#373737" />
                </svg>
            </button>
            <span id="desktop-page-indicator" class="font-bold text-base mt-0.75">1 of 1</span>
            <button id="desktop-next-btn" class="bg-transparent border-none p-0 cursor-pointer rotate-180">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="18" viewBox="0 0 11 18" fill="none"
                    class="rotate-180">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.83496 7.9169C10.0849 8.16694 10.2253 8.50602 10.2253 8.85957C10.2253 9.21312 10.0849 9.5522 9.83496 9.80224L2.29229 17.3449C2.1693 17.4722 2.02217 17.5738 1.8595 17.6437C1.69683 17.7136 1.52187 17.7504 1.34483 17.7519C1.16779 17.7534 0.992217 17.7197 0.828356 17.6527C0.664494 17.5856 0.515624 17.4866 0.390433 17.3614C0.265244 17.2362 0.166239 17.0874 0.0991983 16.9235C0.0321569 16.7596 -0.00157833 16.5841 -4.00543e-05 16.407C0.00149822 16.23 0.0382805 16.055 0.108159 15.8924C0.178038 15.7297 0.279613 15.5826 0.40696 15.4596L7.00696 8.85957L0.40696 2.25957C0.164083 2.0081 0.0296907 1.6713 0.0327282 1.3217C0.0357656 0.972105 0.175991 0.637688 0.423202 0.390476C0.670413 0.143267 1.00483 0.00304031 1.35443 1.90735e-06C1.70402 -0.0030365 2.04082 0.131357 2.29229 0.374233L9.83496 7.9169Z"
                        fill="#373737" />
                </svg>
            </button>
        </div>
    </div>
    </div>

    @php
        $packetsData = $allOtherPackets->map(function ($packet) {
            return [
                'id' => $packet->id,
                'title' => $packet->title,
                'image_url' => $packet->image_url,
            ];
        });
    @endphp

    @vite('resources/js/grid-pagination.js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const packetsData = {!! json_encode($packetsData) !!};
            if (packetsData.length > 0) {
                new GridPagination(
                    packetsData,
                    'desktop-packets-grid',
                    'desktop-prev-btn',
                    'desktop-next-btn',
                    'desktop-page-indicator',
                    5 // items per page
                );
            }
        });
    </script>
@endsection
