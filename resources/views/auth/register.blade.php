@extends('layouts.auth')
@section('title', 'Register')
@section('content')
    <div class="min-h-screen flex flex-col bg-[#FAF6F5]">
        <div class="p-6 md:p-12">
            <a href="{{ route('home') }}" class="flex items-center space-x-2 w-fit">
                <div class="w-10 h-10 flex items-center justify-center bg-[#01A8DC] rounded-full mr-3">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <span class="text-black text-xl font-medium">Kembali</span>
            </a>
        </div>

        <div class="flex-1 flex items-center justify-center">
            <div class="w-full max-w-md mx-3 md:mx-0">
                <div class="bg-white p-8 md:py-7.5 md:px-10 rounded-lg shadow-md">
                    <h2 class="text-lg font-medium text-center mb-8">Buat Akun</h2>

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="name" placeholder="Masukkan nama lengkap"
                                class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-md @error('name') border-red-500 @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Aktif</label>
                            <input type="email" name="email" id="email" placeholder="Masukkan email"
                                class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-md @error('email') border-red-500 @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP
                                Siswa</label>
                            <input type="text" name="phone_number" id="phone_number" placeholder="Masukkan nomor hp"
                                class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-md @error('phone_number') border-red-500 @enderror"
                                value="{{ old('phone_number') }}" required>
                            @error('phone_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="parent_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah /
                                Ibu</label>
                            <input type="text" name="parent_name" id="parent_name" placeholder="Masukkan nama ayah/ibu"
                                class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-md @error('parent_name') border-red-500 @enderror"
                                value="{{ old('parent_name') }}" required>
                            @error('parent_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="parents_phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP
                                Ayah/Ibu</label>
                            <input type="text" name="parents_phone_number" id="parents_phone_number"
                                placeholder="Masukkan nomor hp"
                                class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-md @error('parents_phone_number') border-red-500 @enderror"
                                value="{{ old('parents_phone_number') }}" required>
                            @error('parents_phone_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="school" class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah</label>
                            <input type="text" name="school" id="school" placeholder="Masukkan nama sekolah"
                                class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-md @error('school') border-red-500 @enderror"
                                value="{{ old('school') }}" required>
                            @error('school')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                            <select name="grade" id="grade"
                                class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-md @error('grade') border-red-500 @enderror"
                                required>
                                <option value="" selected disabled>Pilih kelas</option>
                                <option value="7" {{ old('grade') == '7' ? 'selected' : '' }}>7</option>
                                <option value="8" {{ old('grade') == '8' ? 'selected' : '' }}>8</option>
                                <option value="9" {{ old('grade') == '9' ? 'selected' : '' }}>9</option>
                                <option value="10" {{ old('grade') == '10' ? 'selected' : '' }}>10</option>
                                <option value="11" {{ old('grade') == '11' ? 'selected' : '' }}>11</option>
                                <option value="12" {{ old('grade') == '12' ? 'selected' : '' }}>12</option>
                            </select>
                            @error('grade')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" id="password" placeholder="Masukkan password"
                                class="w-full px-3 py-2 text-sm md:text-base border border-gray-300 rounded-md @error('password') border-red-500 @enderror"
                                required>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-[#01A8DC] hover:bg-[#29738A] text-white font-bold py-3 px-4 rounded-md transition duration-200">
                            Buat Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
