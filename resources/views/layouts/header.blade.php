<header class="bg-white shadow-sm">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <img src="{{ asset('images/mlc-logo-colored.png') }}" alt="MLC Logo" class="h-10">
            </div>
            <div class="flex items-center space-x-4">
                <button onclick="consultationAdmin()" class="text-[#01a8dc] border border-[#01a8dc] px-6 py-2 rounded-full text-sm font-medium">
                    Konsultasi Admin
                </button>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.pembayaran') }}">
                            <button
                                class="bg-gradient-to-r from-[#0080A8] to-[#01A8DC] hover:bg-blue-700 text-white px-6 py-2 rounded-full text-sm font-bold shadow-[0px_6px_30px_0px_rgba(42,147,232,0.25)]">
                                Admin Dashboard
                            </button>
                        </a>
                    @else
                        <a href="{{ route('user.profile') }}">
                            <button
                                class="bg-gradient-to-r from-[#0080A8] to-[#01A8DC] hover:bg-blue-700 text-white px-6 py-2 rounded-full text-sm font-bold shadow-[0px_6px_30px_0px_rgba(42,147,232,0.25)]">
                                Dashboard
                            </button>
                        </a>
                    @endif
                @endauth
                @guest
                    <a href="{{ route('login') }}">
                        <button
                            class="bg-gradient-to-r from-[#0080A8] to-[#01A8DC] hover:bg-blue-700 text-white px-6 py-2 rounded-full text-sm font-bold shadow-[0px_6px_30px_0px_rgba(42,147,232,0.25)]">
                            Log In
                        </button>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</header>
