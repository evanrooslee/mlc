@extends('layouts.admin-dashboard')

@section('title', 'Data Siswa')

@section('content')
    <div>
        <h2 class="text-xl font-medium mb-4">Daftar Siswa</h2>
        @livewire('admin.tabel-data-siswa')
    </div>
@endsection

