@extends('layouts.admin-dashboard')

@section('title', 'Pembayaran')

@section('content')
    <div>
        <h2 class="text-xl font-medium mb-4">Daftar Status Pembayaran Siswa</h2>
        @livewire('admin.tabel-pembayaran')
    </div>
@endsection

