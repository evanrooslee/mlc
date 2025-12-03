<header class="bg-white shadow-sm" x-data="{ open: false }">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <img src="{{ asset('images/mlc-logo-colored.png') }}" alt="MLC Logo" class="h-10">
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-4">
                <button onclick="consultationAdmin()"
                    class="text-[#01a8dc] border border-[#01a8dc] px-6 py-2 rounded-full text-sm font-medium hover:bg-gray-50 transition duration-300">
                    Konsultasi Admin
                </button>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.pembayaran') }}">
                            <button
                                class="bg-gradient-to-r from-[#0080A8] to-[#01A8DC] hover:bg-blue-700 text-white px-6 py-2 rounded-full text-sm font-bold shadow-[0px_6px_30px_0px_rgba(42,147,232,0.25)] transition duration-300">
                                Admin Dashboard
                            </button>
                        </a>
                    @else
                        <a href="{{ route('user.profile') }}">
                            <button
                                class="bg-gradient-to-r from-[#0080A8] to-[#01A8DC] hover:bg-blue-700 text-white px-6 py-2 rounded-full text-sm font-bold shadow-[0px_6px_30px_0px_rgba(42,147,232,0.25)] transition duration-300">
                                Dashboard
                            </button>
                        </a>
                    @endif
                @endauth
                @guest
                    <a href="{{ route('login') }}">
                        <button
                            class="bg-gradient-to-r from-[#0080A8] to-[#01A8DC] hover:bg-blue-700 text-white px-6 py-2 rounded-full text-sm font-bold shadow-[0px_6px_30px_0px_rgba(42,147,232,0.25)] transition duration-300">
                            Log In
                        </button>
                    </a>
                @endguest
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open"
                    class="text-gray-500 hover:text-[#01a8dc] focus:outline-none transition duration-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="md:hidden mt-4 pb-4 border-t border-gray-100 pt-4">
            <div class="flex flex-col space-y-4">
                <button onclick="consultationAdmin()"
                    class="w-full text-[#01a8dc] border border-[#01a8dc] px-6 py-2 rounded-full text-sm font-medium hover:bg-gray-50 transition duration-300 text-center">
                    Konsultasi Admin
                </button>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.pembayaran') }}" class="w-full block">
                            <button
                                class="w-full bg-gradient-to-r from-[#0080A8] to-[#01A8DC] hover:bg-blue-700 text-white px-6 py-2 rounded-full text-sm font-bold shadow-[0px_6px_30px_0px_rgba(42,147,232,0.25)] transition duration-300">
                                Admin Dashboard
                            </button>
                        </a>
                    @else
                        <a href="{{ route('user.profile') }}" class="w-full block">
                            <button
                                class="w-full bg-gradient-to-r from-[#0080A8] to-[#01A8DC] hover:bg-blue-700 text-white px-6 py-2 rounded-full text-sm font-bold shadow-[0px_6px_30px_0px_rgba(42,147,232,0.25)] transition duration-300">
                                Dashboard
                            </button>
                        </a>
                    @endif
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="w-full block">
                        <button
                            class="w-full bg-gradient-to-r from-[#0080A8] to-[#01A8DC] hover:bg-blue-700 text-white px-6 py-2 rounded-full text-sm font-bold shadow-[0px_6px_30px_0px_rgba(42,147,232,0.25)] transition duration-300">
                            Log In
                        </button>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</header>
