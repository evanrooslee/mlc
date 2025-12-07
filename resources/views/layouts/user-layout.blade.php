<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('mlc-logo.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Nunito:wght@200..1000&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">

    <!-- Font loading for user layout -->
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
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body class="flex flex-col md:flex-row font-quicksand min-h-screen">
    <div
        class="hidden md:flex bg-[#ffffff] w-[184px] flex-shrink-0 border-r border-[#c9c9c9] flex-col items-center pt-4 min-h-screen sticky top-0">
        <a href="{{ route('home') }}" class="h-[43px] w-[154px] mb-8">
            <img alt="MLC LOGO" src="{{ asset('images/mlc-logo-colored.png') }}" class="block max-w-none size-full" />
        </a>
        <div class="w-full">
            <a href="{{ route('user.profile') }}"
                class="flex items-center py-3 px-3 {{ request()->routeIs('user.profile') ? 'bg-[#BEF2FF] inset-shadow-[2px_2px_4px_rgba(0,0,0,0.25)]' : 'bg-white' }}">
                <svg class="mr-1.5" width="22" height="22" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9 3C9.79565 3 10.5587 3.31607 11.1213 3.87868C11.6839 4.44129 12 5.20435 12 6C12 6.79565 11.6839 7.55871 11.1213 8.12132C10.5587 8.68393 9.79565 9 9 9C8.20435 9 7.44129 8.68393 6.87868 8.12132C6.31607 7.55871 6 6.79565 6 6C6 5.20435 6.31607 4.44129 6.87868 3.87868C7.44129 3.31607 8.20435 3 9 3ZM9 10.5C12.315 10.5 15 11.8425 15 13.5V15H3V13.5C3 11.8425 5.685 10.5 9 10.5Z"
                        fill="black" />
                </svg>
                <span
                    class="font-['Quicksand:Regular',_sans-serif] font-normal text-[#414141] text-[16px] text-center text-nowrap leading-[normal]">Profil</span>
            </a>
            <a href="{{ route('user.kelas') }}"
                class="flex items-center py-3 px-3 {{ request()->routeIs('user.kelas') ? 'bg-[#BEF2FF] inset-shadow-[2px_2px_4px_rgba(0,0,0,0.25)]' : 'bg-white' }}">
                <svg width="20" height="20" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_2171_305)">
                        <path
                            d="M16.9762 14.3888C17.1192 14.2774 17.2332 14.1332 17.3084 13.9684C17.3837 13.8036 17.418 13.6231 17.4085 13.4421C17.399 13.2612 17.346 13.0852 17.2538 12.9292C17.1617 12.7732 17.0333 12.6417 16.8795 12.546L16.875 5.99625L15.75 6.75V12.5437C15.5977 12.6393 15.4706 12.7699 15.3792 12.9247C15.2878 13.0794 15.2348 13.2539 15.2246 13.4333C15.2145 13.6128 15.2475 13.7921 15.3209 13.9561C15.3943 14.1202 15.506 14.2643 15.6465 14.3764L15.2212 14.9411C14.843 15.4223 14.6335 16.0144 14.625 16.6264V18.0011H15.5419C15.7621 18.0011 15.9761 17.9276 16.1499 17.7923C16.3237 17.657 16.4474 17.4676 16.5015 17.2541L16.8739 15.7511V18.0011H17.9989V16.6399C17.9891 16.025 17.7781 15.4303 17.3981 14.9468L16.9762 14.3888ZM9 0L0 4.5L9 10.125L18 4.5L9 0Z"
                            fill="#414141" />
                        <path
                            d="M9 11.25L3.375 7.50378V9.42753C3.375 10.4513 6.6825 13.5 9 13.5C11.3175 13.5 14.625 10.4513 14.625 9.42753V7.50378L9 11.25Z"
                            fill="#414141" />
                    </g>
                    <defs>
                        <clipPath id="clip0_2171_305">
                            <rect width="18" height="18" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
                <span class="font-['Quicksand',_sans-serif] text-[16px] text-[#414141] ml-2">Kelas</span>
            </a>
        </div>
    </div>
    <div class="flex-grow flex flex-col w-full md:w-auto mb-16 md:mb-0">
        <header class="w-full border-b border-[#c9c9c9] flex items-center px-4 md:pl-6 md:pr-12 py-2.5 justify-between">
            <a href="{{ route('home') }}" class=" flex flex-row gap-3 items-center justify-start">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none"
                    class="bg-[#01a8dc] rounded-full">
                    <g clip-path="url(#clip0_397_1337)">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.0574 16.9427C10.8074 16.6927 10.667 16.3536 10.667 16.0001C10.667 15.6465 10.8074 15.3074 11.0574 15.0574L18.6 7.51472C18.723 7.38737 18.8702 7.28579 19.0328 7.21592C19.1955 7.14604 19.3705 7.10925 19.5475 7.10772C19.7245 7.10618 19.9001 7.13991 20.064 7.20696C20.2278 7.274 20.3767 7.373 20.5019 7.49819C20.6271 7.62338 20.7261 7.77225 20.7931 7.93611C20.8602 8.09997 20.8939 8.27555 20.8924 8.45259C20.8908 8.62962 20.8541 8.80458 20.7842 8.96726C20.7143 9.12993 20.6127 9.27706 20.4854 9.40005L13.8854 16.0001L20.4854 22.6001C20.7283 22.8515 20.8626 23.1883 20.8596 23.5379C20.8566 23.8875 20.7163 24.2219 20.4691 24.4691C20.2219 24.7164 19.8875 24.8566 19.5379 24.8596C19.1883 24.8627 18.8515 24.7283 18.6 24.4854L11.0574 16.9427Z"
                            fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0_397_1337">
                            <rect width="32" height="32" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
                <div class="text-[20px] text-center text-nowrap">
                    <p class="block font-medium leading-[normal]">Dashboard</p>
                </div>
            </a>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="md:hidden rounded-full px-3 py-2 text-red-600 border border-red-600 flex items-center gap-2 self-end text-sm font-bold">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
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
        </header>
        <main class="flex-grow">
            @yield('content')
        </main>
    </div>

    {{-- Bottom Navigation for Mobile --}}
    <div
        class="fixed bottom-0 left-0 w-full bg-white border-t border-[#c9c9c9] flex justify-around items-center p-2 md:hidden z-50">
        <a href="{{ route('user.profile') }}"
            class="flex flex-col items-center p-2 rounded-lg {{ request()->routeIs('user.profile') ? 'text-[#01a8dc]' : 'text-[#414141]' }}">
            <svg width="24" height="24" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9 3C9.79565 3 10.5587 3.31607 11.1213 3.87868C11.6839 4.44129 12 5.20435 12 6C12 6.79565 11.6839 7.55871 11.1213 8.12132C10.5587 8.68393 9.79565 9 9 9C8.20435 9 7.44129 8.68393 6.87868 8.12132C6.31607 7.55871 6 6.79565 6 6C6 5.20435 6.31607 4.44129 6.87868 3.87868C7.44129 3.31607 8.20435 3 9 3ZM9 10.5C12.315 10.5 15 11.8425 15 13.5V15H3V13.5C3 11.8425 5.685 10.5 9 10.5Z"
                    fill="currentColor" />
            </svg>
            <span class="text-xs mt-1">Profil</span>
        </a>
        <a href="{{ route('user.kelas') }}"
            class="flex flex-col items-center p-2 rounded-lg {{ request()->routeIs('user.kelas') ? 'text-[#01a8dc]' : 'text-[#414141]' }}">
            <svg width="24" height="24" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M16.9762 14.3888C17.1192 14.2774 17.2332 14.1332 17.3084 13.9684C17.3837 13.8036 17.418 13.6231 17.4085 13.4421C17.399 13.2612 17.346 13.0852 17.2538 12.9292C17.1617 12.7732 17.0333 12.6417 16.8795 12.546L16.875 5.99625L15.75 6.75V12.5437C15.5977 12.6393 15.4706 12.7699 15.3792 12.9247C15.2878 13.0794 15.2348 13.2539 15.2246 13.4333C15.2145 13.6128 15.2475 13.7921 15.3209 13.9561C15.3943 14.1202 15.506 14.2643 15.6465 14.3764L15.2212 14.9411C14.843 15.4223 14.6335 16.0144 14.625 16.6264V18.0011H15.5419C15.7621 18.0011 15.9761 17.9276 16.1499 17.7923C16.3237 17.657 16.4474 17.4676 16.5015 17.2541L16.8739 15.7511V18.0011H17.9989V16.6399C17.9891 16.025 17.7781 15.4303 17.3981 14.9468L16.9762 14.3888ZM9 0L0 4.5L9 10.125L18 4.5L9 0Z"
                    fill="currentColor" />
                <path
                    d="M9 11.25L3.375 7.50378V9.42753C3.375 10.4513 6.6825 13.5 9 13.5C11.3175 13.5 14.625 10.4513 14.625 9.42753V7.50378L9 11.25Z"
                    fill="currentColor" />
            </svg>
            <span class="text-xs mt-1">Kelas</span>
        </a>
    </div>
</body>

</html>
