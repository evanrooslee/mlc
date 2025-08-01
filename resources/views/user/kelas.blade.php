@extends('layouts.user-layout')
@section('title', 'MLC - Kelas')
@section('content')
    <div class="relative">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="absolute top-4 right-4 rounded-full px-3 py-2 text-red-600 border border-red-600 flex items-center gap-2">
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

        <div class="flex items-center mb-24">
            <div class="w-32 h-32 rounded-full overflow-hidden mr-6 shadow-lg">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=01A8DC&color=fff&size=128"
                    alt="User Avatar" class="w-full h-full object-cover">
            </div>
            <div>
                <p class="text-lg text-gray-600 mb-2">Hello,</p>
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ Auth::user()->name }}
                </h1>
            </div>
        </div>

        <div class="space-y-8">
            {{-- Paket Aktif --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Paket Aktif</h2>
                @if ($activePackets->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($activePackets as $packet)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <img src="{{ $packet->image_url }}" alt="{{ $packet->title }}"
                                    class="w-full h-40 object-cover">
                                <div class="p-4">
                                    <h3 class="font-bold text-lg">{{ $packet->title }}</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Anda belum memiliki paket aktif.</p>
                @endif
            </div>

            {{-- Paket Lainnya --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-800 border-b border-gray-400 pb-2 mb-4">Paket Lainnya</h2>
                @if ($otherPackets->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($otherPackets as $packet)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                <img src="{{ $packet->image_url }}" alt="{{ $packet->title }}"
                                    class="w-full h-40 object-cover">
                                <div class="p-4">
                                    <h3 class="font-bold text-lg">{{ $packet->title }}</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-center mt-10 gap-8 text-3xl">
                        @if ($otherPackets->onFirstPage())
                            <span class="text-gray-400">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_397_1283)">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.0574 16.9427C10.8074 16.6927 10.667 16.3536 10.667 16.0001C10.667 15.6465 10.8074 15.3074 11.0574 15.0574L18.6 7.51472C18.723 7.38737 18.8702 7.28579 19.0328 7.21592C19.1955 7.14604 19.3705 7.10925 19.5475 7.10772C19.7245 7.10618 19.9001 7.13991 20.064 7.20696C20.2278 7.274 20.3767 7.373 20.5019 7.49819C20.6271 7.62338 20.7261 7.77225 20.7931 7.93611C20.8602 8.09997 20.8939 8.27555 20.8924 8.45259C20.8908 8.62962 20.8541 8.80458 20.7842 8.96726C20.7143 9.12993 20.6127 9.27706 20.4854 9.40005L13.8854 16.0001L20.4854 22.6001C20.7283 22.8515 20.8626 23.1883 20.8596 23.5379C20.8566 23.8875 20.7163 24.2219 20.4691 24.4691C20.2219 24.7164 19.8875 24.8566 19.5379 24.8596C19.1883 24.8627 18.8515 24.7283 18.6 24.4854L11.0574 16.9427Z"
                                            fill="#373737" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_397_1283">
                                            <rect width="32" height="32" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $otherPackets->previousPageUrl() }}" class="text-[#01A8DC] hover:text-[#0195c9]">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_397_1283)">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.0574 16.9427C10.8074 16.6927 10.667 16.3536 10.667 16.0001C10.667 15.6465 10.8074 15.3074 11.0574 15.0574L18.6 7.51472C18.723 7.38737 18.8702 7.28579 19.0328 7.21592C19.1955 7.14604 19.3705 7.10925 19.5475 7.10772C19.7245 7.10618 19.9001 7.13991 20.064 7.20696C20.2278 7.274 20.3767 7.373 20.5019 7.49819C20.6271 7.62338 20.7261 7.77225 20.7931 7.93611C20.8602 8.09997 20.8939 8.27555 20.8924 8.45259C20.8908 8.62962 20.8541 8.80458 20.7842 8.96726C20.7143 9.12993 20.6127 9.27706 20.4854 9.40005L13.8854 16.0001L20.4854 22.6001C20.7283 22.8515 20.8626 23.1883 20.8596 23.5379C20.8566 23.8875 20.7163 24.2219 20.4691 24.4691C20.2219 24.7164 19.8875 24.8566 19.5379 24.8596C19.1883 24.8627 18.8515 24.7283 18.6 24.4854L11.0574 16.9427Z"
                                            fill="#01A8DC" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_397_1283">
                                            <rect width="32" height="32" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                        @endif
                        <span class="text-gray-700 font-bold text-base mt-0.75">{{ $otherPackets->currentPage() }} of
                            {{ $otherPackets->lastPage() }}</span>
                        @if ($otherPackets->hasMorePages())
                            <a href="{{ $otherPackets->nextPageUrl() }}"
                                class="text-[#01A8DC] hover:text-[#0195c9] rotate-180">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_397_1283)">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.0574 16.9427C10.8074 16.6927 10.667 16.3536 10.667 16.0001C10.667 15.6465 10.8074 15.3074 11.0574 15.0574L18.6 7.51472C18.723 7.38737 18.8702 7.28579 19.0328 7.21592C19.1955 7.14604 19.3705 7.10925 19.5475 7.10772C19.7245 7.10618 19.9001 7.13991 20.064 7.20696C20.2278 7.274 20.3767 7.373 20.5019 7.49819C20.6271 7.62338 20.7261 7.77225 20.7931 7.93611C20.8602 8.09997 20.8939 8.27555 20.8924 8.45259C20.8908 8.62962 20.8541 8.80458 20.7842 8.96726C20.7143 9.12993 20.6127 9.27706 20.4854 9.40005L13.8854 16.0001L20.4854 22.6001C20.7283 22.8515 20.8626 23.1883 20.8596 23.5379C20.8566 23.8875 20.7163 24.2219 20.4691 24.4691C20.2219 24.7164 19.8875 24.8566 19.5379 24.8596C19.1883 24.8627 18.8515 24.7283 18.6 24.4854L11.0574 16.9427Z"
                                            fill="#01A8DC" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_397_1283">
                                            <rect width="32" height="32" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                        @else
                            <span class="text-gray-400 rotate-180">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_397_1283)">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.0574 16.9427C10.8074 16.6927 10.667 16.3536 10.667 16.0001C10.667 15.6465 10.8074 15.3074 11.0574 15.0574L18.6 7.51472C18.723 7.38737 18.8702 7.28579 19.0328 7.21592C19.1955 7.14604 19.3705 7.10925 19.5475 7.10772C19.7245 7.10618 19.9001 7.13991 20.064 7.20696C20.2278 7.274 20.3767 7.373 20.5019 7.49819C20.6271 7.62338 20.7261 7.77225 20.7931 7.93611C20.8602 8.09997 20.8939 8.27555 20.8924 8.45259C20.8908 8.62962 20.8541 8.80458 20.7842 8.96726C20.7143 9.12993 20.6127 9.27706 20.4854 9.40005L13.8854 16.0001L20.4854 22.6001C20.7283 22.8515 20.8626 23.1883 20.8596 23.5379C20.8566 23.8875 20.7163 24.2219 20.4691 24.4691C20.2219 24.7164 19.8875 24.8566 19.5379 24.8596C19.1883 24.8627 18.8515 24.7283 18.6 24.4854L11.0574 16.9427Z"
                                            fill="#373737" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_397_1283">
                                            <rect width="32" height="32" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500">Tidak ada paket lainnya yang tersedia saat ini.</p>
                @endif
            </div>
        </div>

    </div>
@endsection
