@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-md">
            <div class="mb-6">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 flex items-center justify-center bg-[#01A8DC] rounded-full mr-3">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-black text-lg font-normal">Kembali</span>
                </a>
            </div>


            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-center mt-10 mb-10">Log In</h2>

                @if (session('message'))
                    <div class="mb-4 p-3 bg-blue-100 border border-blue-400 text-blue-700 rounded-md">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.authenticate') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                        <input type="text" name="phone_number" id="phone_number" placeholder="Masukkan nomor hp"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md @error('phone_number') border-red-500 @enderror"
                            value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" placeholder="Masukkan password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md @error('password') border-red-500 @enderror"
                            required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="text-right text-xs mb-10">
                        <p>Belum pernah buat akun? <a href="{{ route('register') }}"
                                class="text-blue-500 hover:underline">Buat Akun</a> sekarang</p>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-md transition duration-200 mb-4">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
