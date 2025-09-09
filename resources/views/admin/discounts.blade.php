@extends('layouts.admin-dashboard')

@section('title', 'Kelola Diskon')

@section('content')
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-medium">Kelola Diskon</h2>
            <button onclick="Livewire.dispatch('openModal', {component: 'admin.components.add-discount-modal'})"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Diskon
            </button>
        </div>
        @livewire('admin.tabel-discount')

        <!-- User Tracking Section -->
        <div class="mt-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-medium">Data Pengguna yang Mengklik Diskon</h2>
                <!-- Export button will be added in task 6 -->
            </div>
            @livewire('admin.tabel-discount-clicks')
        </div>
    </div>
@endsection
