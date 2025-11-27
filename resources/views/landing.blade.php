@extends('layouts.app')

@php
    // SEO Meta Variables for Home Page
    $metaTitle = 'MLC Online Study - Bimbel Online Matematika & Fisika Terjangkau untuk SMP & SMA';
    $metaDescription =
        'Sulit paham pelajaran di kelas? Belajar matematika dan fisika online dengan cara lebih mudah di MLC! Dapatkan diskon untuk pembelian kelas pertamamu. Tutor berpengalaman, harga terjangkau.';
    $ogImage = asset('images/hero-illustration.png');
    $ogType = 'website';
@endphp

@section('content')

    <!-- Hero Section -->
    <section class="bg-[#FAFAFA] py-5">
        <div class="max-w-7xl mx-auto px-6 flex gap-8 items-center self-stretch justify-between">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-3xl md:text-5xl font-extrabold text-[#180746] leading-tight mb-6 font-nunito">
                    Sulit paham pelajaran di kelas? Saatnya belajar dengan cara yang lebih mudah dipahami di MLC!
                </h1>
                <p class="mb-6 text-md font-nunito">
                    Dapatkan diskon untuk pembelian kelas pertamamu sekarang!
                </p>
                <div class="relative flex items-center">
                    <input type="text" id="phoneNumber" placeholder="Masukkan nomor hp kamu.."
                        class="bg-white border w-3/5 border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 shadow-[0px_2px_4px_0px_rgba(0,0,0,0.25)]">
                    <button type="button" onclick="getDiscount()" id="discountButton"
                        class="text-white absolute left-3/7 bg-linear-96 from-[#32ADCC] to-[#3BCEF3] hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-semibold rounded-full text-md px-6 py-4 text-center shadow-[0px_4px_8px_0px_#6eb9cc]">
                        Dapatkan Diskon
                    </button>
                </div>
                <div id="errorMessage" class="text-red-500 text-sm mt-2 hidden"></div>
            </div>
            <div class="md:w-1/2 flex justify-center items-center">
                <div class="relative w-[627px] h-[527px]">
                    <img src="{{ asset('images/hero-illustration.png') }}"
                        alt="Ilustrasi belajar matematika dan fisika online di MLC Online Study" width="627"
                        fetchpriority="high" class="absolute top-0 left-0 w-full h-full">
                </div>
            </div>
        </div>
    </section>

    <!-- Pilih Paket Section -->
    <section class="py-30 bg-[#FAFAFA]">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-[#2D2933] mb-6">Pilih Paket</h2>
            <p class="font-quicksand text-center font-medium mb-14 text-md">
                MLC menyediakan berbagai paket pembelajaran bahasa online yang diracik dengan kebutuhan kamu.<br>
                Ayo cari yang cocok!
            </p>

            <div class="w-fit mx-auto">
                <!-- Filter Buttons -->
                <div class="flex flex-wrap justify-start gap-2 mb-8">
                    <button
                        class="filter-btn px-4 py-2 rounded-full border border-[#125BC2] bg-white text-[#125BC2] hover:bg-[#4E7EC2] hover:text-white hover:shadow-sm font-quicksand transition-colors duration-200"
                        data-filter-type="subject" data-filter-value="Matematika">Matematika</button>
                    <button
                        class="filter-btn px-4 py-2 rounded-full border border-[#125BC2] bg-white text-[#125BC2] hover:bg-[#4E7EC2] hover:text-white hover:shadow-sm font-quicksand transition-colors duration-200"
                        data-filter-type="subject" data-filter-value="Fisika">Fisika</button>
                    <button
                        class="filter-btn px-4 py-2 rounded-full border border-[#125BC2] bg-white text-[#125BC2] hover:bg-[#4E7EC2] hover:text-white hover:shadow-sm font-quicksand transition-colors duration-200"
                        data-filter-type="subject" data-filter-value="Campuran">Campuran</button>
                    <button
                        class="filter-btn px-4 py-2 rounded-full border border-[#125BC2] bg-white text-[#125BC2] hover:bg-[#4E7EC2] hover:text-white hover:shadow-sm font-quicksand transition-colors duration-200"
                        data-filter-type="grade" data-filter-value="7">Kelas 7</button>
                    <button
                        class="filter-btn px-4 py-2 rounded-full border border-[#125BC2] bg-white text-[#125BC2] hover:bg-[#4E7EC2] hover:text-white hover:shadow-sm font-quicksand transition-colors duration-200"
                        data-filter-type="grade" data-filter-value="8">Kelas 8</button>
                    <button
                        class="filter-btn px-4 py-2 rounded-full border border-[#125BC2] bg-white text-[#125BC2] hover:bg-[#4E7EC2] hover:text-white hover:shadow-sm font-quicksand transition-colors duration-200"
                        data-filter-type="grade" data-filter-value="9">Kelas 9</button>
                    <button
                        class="filter-btn px-4 py-2 rounded-full border border-[#125BC2] bg-white text-[#125BC2] hover:bg-[#4E7EC2] hover:text-white hover:shadow-sm font-quicksand transition-colors duration-200"
                        data-filter-type="grade" data-filter-value="10">Kelas 10</button>
                    <button
                        class="filter-btn px-4 py-2 rounded-full border border-[#125BC2] bg-white text-[#125BC2] hover:bg-[#4E7EC2] hover:text-white hover:shadow-sm font-quicksand transition-colors duration-200"
                        data-filter-type="grade" data-filter-value="11">Kelas 11</button>
                    <button
                        class="filter-btn px-4 py-2 rounded-full border border-[#125BC2] bg-white text-[#125BC2] hover:bg-[#4E7EC2] hover:text-white hover:shadow-sm font-quicksand transition-colors duration-200"
                        data-filter-type="grade" data-filter-value="12">Kelas 12</button>
                </div>

                <!-- Package Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($packets as $packet)
                        <div class="packet-card bg-white shadow-[2px_2px_10px_rgba(0,0,0,0.25)] rounded-xl {{ $packet->type === 'premium' ? 'border-4 border-yellow-400' : 'border border-gray-200' }} w-[261px] h-[531px] flex flex-col relative"
                            data-subject="{{ $packet->subject }}" data-grade="{{ $packet->grade }}">
                            <div class="relative h-48">
                                <img src="{{ $packet->image_url }}"
                                    alt="Paket {{ $packet->type === 'premium' ? 'Premium' : 'Standar' }} {{ $packet->title }} - Bimbel Online {{ $packet->subject }} Kelas {{ $packet->grade }}"
                                    width="261" height="192" loading="lazy" decoding="async"
                                    class="w-full h-full object-cover rounded-t-lg">
                                <h3 title="{{ $packet->title }}"
                                    class="absolute bottom-2 left-2 text-white text-lg font-quicksand font-semibold">
                                    {{ Str::limit($packet->title, 50) }}
                                </h3>
                            </div>
                            <div class="p-5 flex flex-col h-[calc(531px-12rem)]">
                                <div class="flex flex-wrap gap-2 mb-4">
                                    {{-- @if ($packet->type === 'premium')
                                        <span
                                            class="px-2 py-1 bg-white border border-yellow-400 text-yellow-400 text-xs rounded-full font-quicksand font-bold">Premium</span>
                                    @endif --}}
                                    <span
                                        class="px-2 py-1 bg-white border border-black text-black text-xs rounded-full font-quicksand font-bold">Kelas
                                        {{ $packet->grade }}
                                    </span>
                                    <span
                                        class="px-2 py-1 bg-white border border-black text-black text-xs rounded-full font-quicksand font-bold">{{ $packet->subject }}</span>
                                </div>
                                <div class="flex-grow overflow-auto mb-4">
                                    <ul class="text-sm text-gray-600 space-y-2 font-quicksand">
                                        @foreach ($packet->benefits as $benefit)
                                            <li class="flex items-start">
                                                <span class="text-green-500 mr-2 flex-shrink-0">✓</span>
                                                <span class="flex-1">{{ $benefit }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="mt-auto">
                                    <div class="mb-4">
                                        @if ($packet->discount && $packet->discount->percentage > 0)
                                            <span class="text-sm text-[#868686] font-bold line-through mr-2">Rp
                                                {{ number_format($packet->price, 0, ',', '.') }}</span>
                                            <span
                                                class="text-sm text-[#932525] font-bold bg-[#F99F9F] px-1 py-0.5 rounded-lg">{{ $packet->discount->percentage }}%
                                            </span>
                                            @php
                                                $discountedPrice =
                                                    $packet->price -
                                                    ($packet->price * $packet->discount->percentage) / 100;
                                            @endphp
                                            <div class="text-2xl font-quicksand font-bold text-gray-800">
                                                Rp{{ number_format($discountedPrice, 0, ',', '.') }}</div>
                                        @else
                                            <span
                                                class="text-2xl font-quicksand font-bold text-gray-800">Rp{{ number_format($packet->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('beli-paket.show', $packet->slug ?? $packet->id) }}"
                                        class="block w-full {{ $packet->type === 'premium' ? 'bg-yellow-400 hover:bg-yellow-500' : 'bg-blue-500 hover:bg-blue-600' }} text-white py-2 rounded-3xl font-semibold font-quicksand text-center">
                                        Beli Paket
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Carousel Navigation -->
                <div class="flex justify-center items-center mt-8 space-x-4">
                    <button class="w-6 h-6 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 0l-7 7 7 7"></path>
                        </svg>
                    </button>
                    <button class="w-6 h-6 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="flex items-center space-x-2">
                        <button class="w-2 h-2 bg-blue-500 rounded-full"></button>
                        <button class="w-2 h-2 bg-gray-300 rounded-full"></button>
                        <button class="w-2 h-2 bg-gray-300 rounded-full"></button>
                        <button class="w-2 h-2 bg-gray-300 rounded-full"></button>
                    </div>
                    <button class="w-6 h-6 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                    <button class="w-6 h-6 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Kenapa MLC Section -->
    <section
        style="background: radial-gradient(169.93% 43.19% at 50% 50%, rgba(255, 239, 235, 0.25) 0%, rgba(191, 242, 255, 0.25) 100%)">
        <div class="max-w-7xl mx-auto px-6 flex flex-col py-12 gap-12">
            <h2 class="text-3xl font-bold text-center text-[#180746]">Kenapa MLC?</h2>
            <p class="text-center max-w-3xl mx-auto text-lg font-inter">
                MLC Online Study adalah bimbel online yang memberikan pengalaman belajar yang <br> nyaman, mudah dipahami,
                dan ramah di kantong.
            </p>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="flex flex-col bg-white rounded-lg h-full py-6 px-10 shadow-[2px_4px_4px_rgba(0,0,0,0.25)]">
                    <h3 class="text-xl font-bold text-center">Accessible Learning</h3>
                    <div class="flex-1 flex justify-center items-center">
                        <img src="{{ asset('images/accessible-learning.png') }}"
                            alt="Accessible Learning - Belajar dapat diakses siapa saja di mana saja" height="160"
                            loading="lazy" decoding="async">
                    </div>

                    <p class="text-center text-md">
                        Belajar harus bisa diakses siapa saja, di mana saja, tanpa batasan biaya atau lokasi.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="flex flex-col bg-white rounded-lg h-full py-6 px-10 shadow-[2px_4px_4px_rgba(0,0,0,0.25)]">
                    <h3 class="text-xl font-bold text-center">Fun & Friendly Atmosphere</h3>
                    <div class="flex-1 flex justify-center items-center">
                        <img src="{{ asset('images/fun-learning.png') }}"
                            alt="Fun & Friendly Atmosphere - Belajar menyenangkan dengan tutor ramah" height="160"
                            loading="lazy" decoding="async">
                    </div>

                    <p class="text-center text-md">
                        Belajar itu harus menyenangkan, bukan menekan. Tutor kami ramah dan suportif.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="flex flex-col bg-white rounded-lg h-full py-6 px-10 shadow-[2px_4px_4px_rgba(0,0,0,0.25)]">
                    <h3 class="text-xl font-bold text-center">Progress Oriented</h3>
                    <div class="flex-1 flex justify-center items-center">
                        <img src="{{ asset('images/progress-oriented.png') }}"
                            alt="Progress Oriented - Memantau perkembangan siswa untuk mencapai target" height="160"
                            loading="lazy" decoding="async">
                    </div>
                    <p class="text-center text-md">
                        Kami pantau perkembangan siswa dan bantu mereka mencapai target personal.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="flex flex-col bg-white rounded-lg h-full py-6 px-10 shadow-[2px_4px_4px_rgba(0,0,0,0.25)]">
                    <h3 class="text-xl font-bold text-center">Tech-Integrated Learning</h3>
                    <div class="flex-1 flex justify-center items-center">
                        <img src="{{ asset('images/tech-learning.png') }}"
                            alt="Tech-Integrated Learning - Menggunakan LMS, Zoom, dan forum diskusi" height="140"
                            loading="lazy" decoding="async">
                    </div>
                    <p class="text-center text-md">
                        LMS, Zoom, dan forum diskusi digunakan untuk menunjang efektivitas.
                    </p>
                </div>
            </div>

            <div class="text-center">
                <p class="font-inter mb-4 max-w-3xl mx-auto text-lg">
                    Belajar dari rumah tetap bisa efektif dan terarah, tanpa khawatir tertinggal. <br>
                    MLC hadir untuk membantu siswa memahami materi dengan lebih tenang, dengan harga yang masuk akal dan
                    waktu belajar yang fleksibel.
                </p>
            </div>
        </div>
    </section>

    <!-- WhatsApp Admin Section -->
    <section class="py-12 bg-[#FAFAFA]">
        <div
            class="max-w-5xl mx-auto rounded-3xl px-16 py-8 relative overflow-hidden bg-gradient-to-r from-[#A2C0EB] via-[#F3F7FE] to-[#A2C0EB]">
            <div class="absolute bottom-0 left-0 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="144" height="106" viewBox="0 0 144 106"
                    fill="none">
                    <path
                        d="M46 5.5C36.5 1 8 0 8 0H-12V115H144C144 115 139 97.5 117.5 96C96 94.5 79.5 66.5 76 56.5C72.5 46.5 72 42.5 72 42.5C72 42.5 70.5 28.5 63 20C55.5 11.5 55.5 10 46 5.5Z"
                        fill="#CADEFA" />
                </svg>
            </div>
            <div class="flex justify-between items-center px-8">
                <div class="flex flex-col gap-4">
                    <p class="text-#373737 font-medium text-sm">Ingin tau lebih banyak tentang MLC?</p>
                    <h2 class="text-xl font-semibold text-black">Chat WhatsApp Admin sekarang!</h2>
                </div>
                <button onclick="consultationAdmin()"
                    class="bg-[#01A8DC] hover:bg-[#29738A] text-white px-4 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 shadow-lg">
                    Konsultasi Admin
                    <img src="{{ asset('images/ic_baseline-whatsapp.svg') }}" alt="WhatsApp" class="w-6 h-6">
                </button>
            </div>
            <div class="absolute top-0 right-0 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="65" height="112" viewBox="0 0 65 112"
                    fill="none">
                    <path
                        d="M2.35968 -8.92574C-3.14035 -10.4257 1.35968 11.0743 11.8597 30.5743C22.3597 50.0743 38.8597 31.5743 42.8597 47.0743C46.8597 62.5743 27.8597 67.5742 28.8597 89.5742C29.8597 111.574 51.3597 111.574 51.3597 111.574H76.3597V-8.92574C76.3597 -8.92574 7.85972 -7.42574 2.35968 -8.92574Z"
                        fill="#CADEFA" />
                </svg>
            </div>
        </div>
    </section>

    <!-- Banner Section -->
    <section class="py-22 bg-[#FAFAFA]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <div class="h-full">
                    <div class="relative h-40 rounded-lg overflow-hidden"
                        style="background-image: url('{{ asset('images/mlc-regular-class.png') }}')">
                        <!-- Frosted glass overlay -->
                        <div class="absolute inset-0 bg-white/20 backdrop-blur-[1px]"></div>
                        <div class="absolute inset-0 p-4 flex flex-col">
                            <h3 class="text-sm font-bold font-quicksand leading-tight text-black">
                                MLC Regular Class
                            </h3>
                            <p class="text-sm font-quicksand text-black mt-6.5 flex-1">
                                Kelas online rutin dengan jadwal terstruktur.
                            </p>
                            <div class="text-2xl">📚</div>
                        </div>
                    </div>
                </div>

                <div class="h-full">
                    <div class="relative h-40 rounded-lg overflow-hidden"
                        style="background-image: url('{{ asset('images/mlc-try-out-center.png') }}')">
                        <!-- Frosted glass overlay -->
                        <div class="absolute inset-0 bg-white/20 backdrop-blur-[1px]"></div>
                        <div class="absolute inset-0 p-4 flex flex-col">
                            <h3 class="text-sm font-bold font-quicksand leading-tight text-black">
                                MLC Try Out Center
                            </h3>
                            <p class="text-sm font-quicksand text-black mt-6.5 flex-1">
                                Try Out mingguan untuk evaluasi kesiapan ujian.
                            </p>
                            <div class="text-2xl">🔍</div>
                        </div>
                    </div>
                </div>

                <div class="h-full">
                    <div class="relative h-40 rounded-lg overflow-hidden"
                        style="background-image: url('{{ asset('images/mlc-forum-diskusi.png') }}')">
                        <!-- Frosted glass overlay -->
                        <div class="absolute inset-0 bg-white/20 backdrop-blur-[1px]"></div>
                        <div class="absolute inset-0 p-4 flex flex-col">
                            <h3 class="text-sm font-bold font-quicksand leading-tight text-black">
                                MLC Forum Diskusi
                            </h3>
                            <p class="text-sm font-quicksand text-black mt-6.5 flex-1">
                                Forum tanya-jawab soal dengan tutor dan teman.
                            </p>
                            <div class="text-2xl">💬</div>
                        </div>
                    </div>
                </div>

                <div class="h-full">
                    <div class="relative h-40 rounded-lg overflow-hidden"
                        style="background-image: url('{{ asset('images/mlc-counseling.png') }}')">
                        <!-- Frosted glass overlay -->
                        <div class="absolute inset-0 bg-white/20 backdrop-blur-[1px]"></div>
                        <div class="absolute inset-0 p-4 flex flex-col">
                            <h3 class="text-sm font-bold font-quicksand leading-tight text-black">
                                MLC Counseling
                            </h3>
                            <p class="text-sm font-quicksand text-black mt-6.5 flex-1">
                                Konsultasi gratis tentang jurusan dan strategi belajar.
                            </p>
                            <div class="text-2xl">🎓</div>
                        </div>
                    </div>
                </div>

                <div class="h-full">
                    <div class="relative h-40 rounded-lg overflow-hidden"
                        style="background-image: url('{{ asset('images/mlc-learning-management.png') }}')">
                        <!-- Frosted glass overlay -->
                        <div class="absolute inset-0 bg-white/20 backdrop-blur-[1px]"></div>
                        <div class="absolute inset-0 p-4 flex flex-col">
                            <h3 class="text-sm font-bold font-quicksand leading-tight text-black">
                                MLC Learning Management System
                            </h3>
                            <p class="text-sm font-quicksand text-black mt-2 flex-1">
                                Akses materi, rekaman kelas, dan progress belajar.
                            </p>
                            <div class="text-2xl">📁</div>
                        </div>
                    </div>
                </div>

                <div class="h-full">
                    <div class="relative h-40 rounded-lg overflow-hidden"
                        style="background-image: url('{{ asset('images/mlc-free-trial-class.png') }}')">
                        <!-- Frosted glass overlay -->
                        <div class="absolute inset-0 bg-white/20 backdrop-blur-[1px]"></div>
                        <div class="absolute inset-0 p-4 flex flex-col">
                            <h3 class="text-sm font-bold font-quicksand leading-tight text-black">
                                MLC Free Trial Class
                            </h3>
                            <p class="text-sm font-quicksand text-black mt-6.5 flex-1">
                                Kelas percobaan gratis untuk semua siswa baru.
                            </p>
                            <div class="text-2xl">🎁</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Video Pembelajaran Section -->
    <section class="py-16 bg-[#FAFAFA]" x-data="videoManager()">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Video Pembelajaran</h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-50 justify-center">
                @forelse ($materials as $material)
                    <div x-data="{ showPlayer: false, videoId: '{{ $material->id }}' }"
                        class="bg-white rounded-lg shadow-md overflow-hidden w-[320px] flex-shrink-0 justify-self-center">

                        <div class="relative bg-black w-full h-[568px]">
                            <template x-if="!showPlayer">
                                <button @click="playVideo(videoId); showPlayer = true"
                                    class="absolute inset-0 w-full h-full flex items-center justify-center bg-black bg-opacity-60 transition hover:bg-opacity-70 z-10"
                                    aria-label="Play Video">
                                    <svg class="w-16 h-16 text-white drop-shadow-lg" fill="currentColor"
                                        viewBox="0 0 64 64">
                                        <circle cx="32" cy="32" r="32" fill="rgba(0,0,0,0.4)" />
                                        <polygon points="26,20 50,32 26,44" fill="white" />
                                    </svg>
                                </button>
                            </template>
                            <template x-if="showPlayer">
                                <video class="w-full h-full object-cover" controls autoplay preload="auto"
                                    x-ref="videoPlayer" @play="setCurrentVideo(videoId, $refs.videoPlayer)"
                                    @pause="if (currentVideoId === videoId) currentVideoId = null"
                                    @loadedmetadata="console.log('Video metadata loaded:', $refs.videoPlayer.duration)">
                                    <source src="{{ asset('storage/' . $material->video) }}" type="video/mp4">
                                    <p class="text-white text-center">Your browser does not support the video tag.</p>
                                </video>
                            </template>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-1 text-xl">{{ $material->title }}</h3>
                            <p class="text-sm text-[#7F7F7F] -mt-1 mb-3">{{ $material->subject }}</p>
                            <p class="text-sm text-black">{{ $material->publisher }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-8">
                        <p class="text-gray-500">No video materials available at the moment.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-11">
                <p class="text-gray-600 mb-4 text-2xl font-semibold">
                    "Live as if you were to die tomorrow. Learn as if you were to live forever."
                </p>
                <p class="text-gray-500 text-lg">- Mahatma Gandhi</p>
            </div>
        </div>
    </section>

    <!-- Artikel Section -->
    <section class="py-12 bg-[#FAFAFA]">
        <h2 class="text-3xl font-bold text-center text-[#180746] mb-15 relative z-10">Artikel</h2>
        <div class="max-w-7xl mx-auto px-6 py-6 relative">
            <!-- Decorative elements -->
            <img src="{{ asset('images/decor-book.png') }}" alt="" width="64" height="64"
                loading="lazy" decoding="async"
                class="absolute top-6 left-8 w-16 rotate-[-12deg] opacity-60 pointer-events-none" />
            <img src="{{ asset('images/decor-circle.png') }}" alt="" width="300" height="300"
                loading="lazy" decoding="async"
                class="absolute top-[45%] left-[55%] w-[300px] -translate-x-1/2 -translate-y-1/2 opacity-25 pointer-events-none " />
            <img src="{{ asset('images/decor-star1.png') }}" alt="" width="24" height="24"
                loading="lazy" decoding="async" class="absolute top-4 right-12 w-6 opacity-60 pointer-events-none" />
            <img src="{{ asset('images/decor-book.png') }}" alt="" width="56" height="56"
                loading="lazy" decoding="async"
                class="absolute bottom-8 right-10 w-14 rotate-[15deg] opacity-60 pointer-events-none" />
            <img src="{{ asset('images/decor-star2.png') }}" alt="" width="24" height="24"
                loading="lazy" decoding="async" class="absolute bottom-6 left-10 w-6 opacity-60 pointer-events-none" />



            <div class="flex flex-row items-center gap-16 relative z-10">
                <div class="w-1/2">
                    <div class="space-y-8">
                        @if (isset($articles[0]))
                            <!-- Article Card 1 -->
                            <div
                                class="bg-white rounded-lg shadow-lg overflow-hidden flex items-center h-[121px] relative">
                                @if ($articles[0]->is_starred)
                                    <div class="absolute top-2 right-2 z-10">
                                        <svg class="w-6 h-6 text-yellow-400 fill-current filter drop-shadow-md"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                        </svg>
                                        <div
                                            class="absolute hidden group-hover:block bg-black text-white text-xs rounded py-1 px-2 right-0 bottom-full">
                                            Artikel Unggulan
                                        </div>
                                    </div>
                                @endif
                                <img src="{{ asset('storage/' . $articles[0]->image) }}" alt="Article"
                                    class="w-32 h-full object-cover"
                                    onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';"
                                    loading="lazy">
                                <div class="p-4 flex flex-col justify-between leading-normal">
                                    <h3 class="text-md font-bold text-[#180746] mb-1">
                                        {{ Str::limit(trim($articles[0]->title), 56) }}</h3>
                                    <p class="text-xs text-gray-500 mb-2">{{ $articles[0]->source }}</p>
                                    <a href="{{ $articles[0]->url }}" target="_blank"
                                        class="text-xs text-[#01A8DC] font-bold hover:underline self-end text-center border border-[#01A8DC] rounded-full px-3 py-2.5">Baca
                                        Sekarang</a>
                                </div>
                            </div>
                        @endif

                        @if (isset($articles[2]))
                            <!-- Article Card 3 -->
                            <div
                                class="bg-white rounded-lg shadow-lg overflow-hidden flex items-center h-[121px] relative">
                                @if ($articles[2]->is_starred)
                                    <div class="absolute top-2 right-2 z-10">
                                        <svg class="w-6 h-6 text-yellow-400 fill-current filter drop-shadow-md"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                        </svg>
                                        <div
                                            class="absolute hidden group-hover:block bg-black text-white text-xs rounded py-1 px-2 right-0 bottom-full">
                                            Artikel Unggulan
                                        </div>
                                    </div>
                                @endif
                                <img src="{{ asset('storage/' . $articles[2]->image) }}" alt="Article"
                                    class="w-32 h-full object-cover"
                                    onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';"
                                    loading="lazy">
                                <div class="p-4 flex flex-col justify-between leading-normal">
                                    <h3 class="text-md font-bold text-[#180746] mb-1">
                                        {{ Str::limit(trim($articles[2]->title), 56) }}</h3>
                                    <p class="text-xs text-gray-500 mb-2">{{ $articles[2]->source }}</p>
                                    <a href="{{ $articles[2]->url }}" target="_blank"
                                        class="text-xs text-[#01A8DC] font-bold hover:underline self-end border text-center border-[#01A8DC] rounded-full px-3 py-2.5">Baca
                                        Sekarang</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="w-1/2">
                    @if (isset($articles[1]))
                        <!-- Article Card 2 -->
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden flex items-center h-[121px] relative">
                            @if ($articles[1]->is_starred)
                                <div class="absolute top-2 right-2 z-10">
                                    <svg class="w-6 h-6 text-yellow-400 fill-current filter drop-shadow-md"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                    <div
                                        class="absolute hidden group-hover:block bg-black text-white text-xs rounded py-1 px-2 right-0 bottom-full">
                                        Artikel Unggulan
                                    </div>
                                </div>
                            @endif
                            <img src="{{ asset('storage/' . $articles[1]->image) }}" alt="Article"
                                class="w-32 h-full object-cover"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder.jpg') }}';"
                                loading="lazy">
                            <div class="p-4 flex flex-col justify-between leading-normal">
                                <h3 class="text-md font-bold text-[#180746] mb-1">
                                    {{ Str::limit(trim($articles[1]->title), 50) }}</h3>
                                <p class="text-xs text-gray-500 mb-2">{{ $articles[1]->source }}</p>
                                <a href="{{ $articles[1]->url }}" target="_blank"
                                    class="text-xs text-white bg-[#01A8DC] font-bold hover:bg-blue-700 hover:underline rounded-full px-3 py-2.5 text-center self-end">Baca
                                    Sekarang</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- JS Scripts -->
    <script>
        // Video Manager for Alpine.js
        function videoManager() {
            return {
                currentVideoId: null,
                currentVideoElement: null,

                playVideo(videoId) {
                    // If there's a currently playing video and it's different from the new one
                    if (this.currentVideoId && this.currentVideoId !== videoId && this.currentVideoElement) {
                        this.currentVideoElement.pause();
                        // Reset the previous video's showPlayer state
                        this.resetVideoPlayer(this.currentVideoId);
                    }
                },

                setCurrentVideo(videoId, videoElement) {
                    this.currentVideoId = videoId;
                    this.currentVideoElement = videoElement;
                },

                resetVideoPlayer(videoId) {
                    // Find and reset the video player state
                    const videoCards = document.querySelectorAll('[x-data*="showPlayer"]');
                    videoCards.forEach(card => {
                        const cardData = Alpine.$data(card);
                        if (cardData.videoId === videoId) {
                            cardData.showPlayer = false;
                        }
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const packetCards = document.querySelectorAll('.packet-card');
            const activeFilters = {
                subject: [],
                grade: []
            };

            // Initialize carousel
            const firstButton = document.querySelector(
                '.flex.justify-center.items-center.mt-8.space-x-4 > button:nth-child(1)');
            const prevButton = document.querySelector(
                '.flex.justify-center.items-center.mt-8.space-x-4 > button:nth-child(2)');
            const nextButton = document.querySelector(
                '.flex.justify-center.items-center.mt-8.space-x-4 > button:nth-child(4)');
            const lastButton = document.querySelector(
                '.flex.justify-center.items-center.mt-8.space-x-4 > button:nth-child(5)');
            const dotsContainer = document.querySelector('.flex.items-center.space-x-2');
            let currentIndex = 0;
            const itemsPerPage = 4;

            // Filter functionality
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filterType = this.getAttribute('data-filter-type');
                    const filterValue = this.getAttribute('data-filter-value');
                    const isCurrentlyActive = this.classList.contains('bg-blue-600');

                    // Count currently active buttons
                    const activeButtonsCount = document.querySelectorAll('.filter-btn.bg-blue-600')
                        .length;

                    // If trying to activate a new button and already at max (3), prevent activation
                    if (!isCurrentlyActive && activeButtonsCount >= 3) {
                        // Show a brief visual feedback that max limit is reached
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = 'scale(1)';
                        }, 150);
                        return; // Don't proceed with activation
                    }

                    // Toggle active state visually
                    // Ensure any explicit white background is removed when activating
                    this.classList.toggle('bg-white');
                    this.classList.toggle('bg-blue-600');
                    this.classList.toggle('text-white');
                    this.classList.toggle('border');
                    this.classList.toggle('border-[#125BC2]');
                    this.classList.toggle('text-[#125BC2]');

                    // Update active filters
                    if (this.classList.contains('bg-blue-600')) {
                        // Add to active filters
                        if (!activeFilters[filterType].includes(filterValue)) {
                            activeFilters[filterType].push(filterValue);
                        }
                    } else {
                        // Remove from active filters
                        const index = activeFilters[filterType].indexOf(filterValue);
                        if (index > -1) {
                            activeFilters[filterType].splice(index, 1);
                        }
                    }

                    // Apply filters
                    applyFilters();
                });
            });

            function applyFilters() {
                let visibleCards = 0;

                packetCards.forEach(card => {
                    const cardSubject = card.getAttribute('data-subject');
                    const cardGrade = card.getAttribute('data-grade');
                    let showCard = true;

                    // Check if we need to filter by subject
                    if (activeFilters.subject.length > 0) {
                        if (!activeFilters.subject.includes(cardSubject)) {
                            showCard = false;
                        }
                    }

                    // Check if we need to filter by grade
                    if (activeFilters.grade.length > 0) {
                        if (!activeFilters.grade.includes(cardGrade)) {
                            showCard = false;
                        }
                    }

                    // Show or hide card
                    if (showCard) {
                        card.style.display = '';
                        visibleCards++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Update carousel after filtering
                setupCarousel();

                // Show message if no cards match
                const noResultsMessage = document.getElementById('no-results-message');
                if (visibleCards === 0 && (activeFilters.subject.length > 0 || activeFilters.grade.length > 0)) {
                    if (!noResultsMessage) {
                        const message = document.createElement('p');
                        message.id = 'no-results-message';
                        message.className = 'text-center text-gray-600 col-span-full py-4';
                        message.textContent = 'No packets found for the selected filters.';
                        document.querySelector('.grid.grid-cols-1').appendChild(message);
                    }
                } else if (noResultsMessage) {
                    noResultsMessage.remove();
                }
            }

            function setupCarousel() {
                const visibleCards = Array.from(packetCards).filter(card => card.style.display !== 'none');
                const totalPages = Math.ceil(visibleCards.length / itemsPerPage);

                // Reset current index if it's out of bounds after filtering
                if (currentIndex >= totalPages) {
                    currentIndex = Math.max(0, totalPages - 1);
                }

                function setupDots() {
                    dotsContainer.innerHTML = '';
                    for (let i = 0; i < totalPages; i++) {
                        const dot = document.createElement('button');
                        dot.classList.add('w-2', 'h-2', 'rounded-full');
                        if (i === currentIndex) {
                            dot.classList.add('bg-blue-500');
                        } else {
                            dot.classList.add('bg-gray-300');
                        }
                        dot.addEventListener('click', () => {
                            currentIndex = i;
                            showPage(currentIndex);
                        });
                        dotsContainer.appendChild(dot);
                    }
                }

                function showPage(index) {
                    visibleCards.forEach((card, i) => {
                        const startIndex = index * itemsPerPage;
                        const endIndex = startIndex + itemsPerPage;
                        if (i >= startIndex && i < endIndex) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    const dots = dotsContainer.querySelectorAll('button');
                    dots.forEach((dot, i) => {
                        if (i === index) {
                            dot.classList.add('bg-blue-500');
                            dot.classList.remove('bg-gray-300');
                        } else {
                            dot.classList.remove('bg-blue-500');
                            dot.classList.add('bg-gray-300');
                        }
                    });
                }

                // Hide carousel navigation if no pages
                const carouselNav = document.querySelector('.flex.justify-center.items-center.mt-8.space-x-4');
                if (totalPages <= 1) {
                    carouselNav.style.display = 'none';
                } else {
                    carouselNav.style.display = 'flex';

                    // Set up event listeners
                    firstButton.onclick = () => {
                        currentIndex = 0;
                        showPage(currentIndex);
                    };

                    prevButton.onclick = () => {
                        currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalPages - 1;
                        showPage(currentIndex);
                    };

                    nextButton.onclick = () => {
                        currentIndex = (currentIndex < totalPages - 1) ? currentIndex + 1 : 0;
                        showPage(currentIndex);
                    };

                    lastButton.onclick = () => {
                        currentIndex = totalPages - 1;
                        showPage(currentIndex);
                    };

                    setupDots();
                    showPage(currentIndex);
                }
            }

            // Initialize carousel on page load
            setupCarousel();
        });

        // Nomor WhatsApp
        const whatsappNumber = '62816811020';

        //Message diskon
        const discountMessage =
            'Halo! Saya tertarik dengan promo diskon yang ditawarkan di website. Bisakah saya mendapatkan informasi lebih lanjut tentang penawaran spesial ini?';

        // Message konsultasi
        const consultationMessage =
            'Halo! Saya ingin melakukan konsultasi gratis mengenai program bimbel online MLC. Bisakah kita berdiskusi lebih lanjut?';

        function getDiscount() {
            const phoneInput = document.getElementById('phoneNumber');
            const errorDiv = document.getElementById('errorMessage');
            const discountButton = document.getElementById('discountButton');
            const phoneNumber = phoneInput.value.trim();

            // Clear previous error messages
            errorDiv.classList.add('hidden');
            errorDiv.textContent = '';

            // Validate phone number
            if (!phoneNumber) {
                showError('Nomor HP harus diisi');
                return;
            }

            // Indonesian phone number validation (basic format)
            const phoneRegex = /^(\+62|62|0)[0-9]{8,13}$/;
            if (!phoneRegex.test(phoneNumber)) {
                showError('Format nomor HP tidak valid. Gunakan format Indonesia (contoh: 08123456789)');
                return;
            }

            // Disable button during processing
            const originalText = discountButton.textContent;
            discountButton.disabled = true;
            discountButton.textContent = 'Memproses...';

            // Track the click via AJAX
            trackDiscountClick(phoneNumber)
                .then(() => {
                    // Success or failure, still redirect to WhatsApp
                    redirectToWhatsApp();
                })
                .catch(() => {
                    // Even if tracking fails, still redirect to WhatsApp
                    redirectToWhatsApp();
                })
                .finally(() => {
                    // Re-enable button
                    discountButton.disabled = false;
                    discountButton.textContent = originalText;
                });
        }

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function trackDiscountClick(phoneNumber) {
            return fetch('/track-discount-click', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        phone_number: phoneNumber
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Tracking failed');
                        });
                    }
                    return response.json();
                });
        }

        function redirectToWhatsApp() {
            const encodedMessage = encodeURIComponent(discountMessage);
            const whatsappURL = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
            window.open(whatsappURL, '_blank');
        }

        function consultationAdmin() {
            const encodedMessage = encodeURIComponent(consultationMessage);
            const whatsappURL = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
            window.open(whatsappURL, '_blank');
        }
    </script>

@endsection
