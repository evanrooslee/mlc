<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | Admin Dashboard</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Nunito:wght@200..1000&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">

    <!-- Font loading for admin layout -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Nunito:wght@200..1000&family=Quicksand:wght@300..700&display=swap');

        body {
            font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        .font-quicksand {
            font-family: 'Quicksand', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        .font-inter {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        .font-nunito {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }
    </style>
    {{-- <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
</head>

<body class="bg-white font-quicksand">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 h-screen w-[184px] bg-white border-r border-[rgba(223,223,223,0.8)]">
        <!-- Logo -->
        <a href="{{ route('home') }}">
            <div class="p-4">
                <img src="{{ asset('images/mlc-logo-colored.png') }}" alt="MLC Logo" class="h-[43px] w-[154px]">
            </div>
        </a>

        <!-- Navigation Menu -->
        <nav class="mt-6">
            <a href="{{ route('admin.pembayaran') }}"
                class="flex items-center py-3 px-3 {{ request()->routeIs('admin.pembayaran') ? 'bg-[#BEF2FF]' : 'bg-white' }}">
                <svg width="20" height="20" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12.75 3H5.25C4.42157 3 3.75 3.67157 3.75 4.5V14.25C3.75 15.0784 4.42157 15.75 5.25 15.75H12.75C13.5784 15.75 14.25 15.0784 14.25 14.25V4.5C14.25 3.67157 13.5784 3 12.75 3Z"
                        stroke="#414141" stroke-width="1.5" />
                    <path d="M6.75 6.75H11.25M6.75 9.75H11.25M6.75 12.75H9.75" stroke="#414141" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
                <span class="font-['Quicksand',_sans-serif] text-[16px] text-[#414141] ml-2">Pembayaran</span>
            </a>

            <a href="{{ route('admin.data-siswa') }}"
                class="flex items-center py-3 px-3 {{ request()->routeIs('admin.data-siswa') ? 'bg-[#BEF2FF]' : 'bg-white' }}">
                <svg width="20" height="20" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.25 15.75V13.5H8.25V6H6.75V8.25H1.5V2.25H6.75V4.5H11.25V2.25H16.5V8.25H11.25V6H9.75V12H11.25V9.75H16.5V15.75H11.25Z"
                        fill="black" />
                </svg>
                <span class="font-['Quicksand',_sans-serif] text-[16px] text-[#414141] ml-2">Data Siswa</span>
            </a>

            <a href="{{ route('admin.discounts') }}"
                class="flex items-center py-3 px-3 {{ request()->routeIs('admin.discounts') ? 'bg-[#BEF2FF]' : 'bg-white' }}">
                <svg width="20" height="20" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M3.75 5.25L8.25 0.75L12.75 5.25L17.25 0.75V12L12.75 16.5L8.25 12L3.75 16.5L0.75 13.5V2.25L3.75 5.25Z"
                        stroke="#414141" stroke-width="1.5" />
                    <path d="M6.75 7.125L8.625 9L11.25 6.375" stroke="#414141" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span class="font-['Quicksand',_sans-serif] text-[16px] text-[#414141] ml-2">Diskon</span>
            </a>

            <a href="{{ route('admin.pengaturan') }}"
                class="flex items-center py-3 px-3 {{ request()->routeIs('admin.pengaturan') ? 'bg-[#BEF2FF]' : 'bg-white' }}">
                <svg width="20" height="20" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.22008 15.75C8.04558 15.75 7.89433 15.6928 7.76633 15.5783C7.63783 15.4638 7.55808 15.321 7.52708 15.15L7.32983 13.581C7.09033 13.5085 6.83133 13.395 6.55283 13.2405C6.27483 13.0855 6.03808 12.9195 5.84258 12.7425L4.40633 13.3605C4.24783 13.4305 4.08683 13.438 3.92333 13.383C3.75983 13.328 3.63383 13.2243 3.54533 13.0718L2.73608 11.6775C2.64758 11.525 2.62158 11.3655 2.65808 11.199C2.69458 11.0325 2.78158 10.896 2.91908 10.7895L4.17308 9.852C4.15058 9.716 4.13233 9.57625 4.11833 9.43275C4.10333 9.28875 4.09583 9.149 4.09583 9.0135C4.09583 8.8875 4.10333 8.755 4.11833 8.616C4.13233 8.477 4.15058 8.3205 4.17308 8.1465L2.91908 7.209C2.78158 7.1025 2.69708 6.96375 2.66558 6.79275C2.63408 6.62175 2.66233 6.45975 2.75033 6.30675L3.54533 4.95675C3.63383 4.81325 3.75983 4.71175 3.92333 4.65225C4.08683 4.59275 4.24783 4.598 4.40633 4.668L5.82833 5.271C6.05233 5.0845 6.29483 4.91625 6.55583 4.76625C6.81583 4.61625 7.06908 4.50025 7.31558 4.41825L7.52783 2.84925C7.55833 2.67825 7.63783 2.5355 7.76633 2.421C7.89483 2.3065 8.04608 2.2495 8.22008 2.25H9.78008C9.95458 2.25 10.1058 2.30725 10.2338 2.42175C10.3623 2.53625 10.4421 2.679 10.4731 2.85L10.6703 4.434C10.9578 4.535 11.2118 4.65075 11.4323 4.78125C11.6528 4.91175 11.8801 5.07525 12.1141 5.27175L13.6081 4.66875C13.7671 4.59875 13.9283 4.5935 14.0918 4.653C14.2553 4.7125 14.3811 4.814 14.4691 4.9575L15.2641 6.32175C15.3526 6.47475 15.3786 6.63425 15.3421 6.80025C15.3056 6.96625 15.2186 7.103 15.0811 7.2105L13.7701 8.19C13.8111 8.345 13.8341 8.48725 13.8391 8.61675C13.8441 8.74625 13.8466 8.87375 13.8466 8.99925C13.8466 9.11575 13.8416 9.2385 13.8316 9.3675C13.8221 9.497 13.8001 9.6535 13.7656 9.837L15.0331 10.7895C15.1706 10.896 15.2601 11.0325 15.3016 11.199C15.3431 11.3655 15.3196 11.525 15.2311 11.6775L14.4316 13.0568C14.3436 13.2098 14.2153 13.3135 14.0468 13.368C13.8783 13.423 13.7148 13.4155 13.5563 13.3455L12.1141 12.7275C11.8806 12.924 11.6456 13.0923 11.4091 13.2323C11.1726 13.3723 10.9263 13.4835 10.6703 13.566L10.4723 15.1493C10.4418 15.3203 10.3623 15.463 10.2338 15.5775C10.1053 15.692 9.95408 15.7495 9.78008 15.75H8.22008ZM8.97983 10.875C9.50283 10.875 9.94608 10.6933 10.3096 10.3298C10.6731 9.96625 10.8548 9.523 10.8548 9C10.8548 8.477 10.6731 8.03375 10.3096 7.67025C9.94608 7.30675 9.50283 7.125 8.97983 7.125C8.45383 7.125 8.00983 7.30675 7.64783 7.67025C7.28583 8.03375 7.10483 8.477 7.10483 9C7.10483 9.523 7.28583 9.96625 7.64783 10.3298C8.00983 10.6933 8.45383 10.875 8.97983 10.875Z"
                        fill="black" />
                </svg>
                <span class="font-['Quicksand',_sans-serif] text-[16px] text-[#414141] ml-2">Pengaturan</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="ml-[184px]">
        <!-- Top Header -->
        <header
            class="h-[52px] border-b border-[rgba(223,223,223,0.8)] flex justify-between items-center px-6 pr-12 bg-white">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}"
                    class="flex items-center justify-center w-10 h-10 bg-[#01a8dc] rounded-full hover:bg-[#0080A8] transition-colors">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
                <h1 class="font-['Quicksand',_sans-serif] font-medium text-[20px] text-black">Admin Dashboard</h1>
            </div>

            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center gap-2 px-3 py-1 bg-[#F8F8F8] rounded-full border border-[#BA2B15]">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12.75 5.25L11.6925 6.3075L13.6275 8.25H6V9.75H13.6275L11.6925 11.685L12.75 12.75L16.5 9M3 3.75H9V2.25H3C2.175 2.25 1.5 2.925 1.5 3.75V14.25C1.5 15.075 2.175 15.75 3 15.75H9V14.25H3V3.75Z"
                        fill="#BA2B15" />
                </svg>
                <span class="font-['Quicksand',_sans-serif] text-[16px] text-[#BA2B15]">Keluar</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>
    @livewireScriptConfig
    @livewire('wire-elements-modal')
    @stack('scripts')
</body>

</html>
