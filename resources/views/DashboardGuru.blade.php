@extends('layouts.MainGuru')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarGuru')

<div class="  font-[Verdana] ">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-3 mb-8">
        <h2 class="text-2xl font-bold text-emerald-600">Dashboard Guru</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>
    <p class="text-gray-700 mb-10 text-sm">
        Selamat datang, {{ Auth::user()->pegawai->nama_lengkap ?? Auth::user()->username }}!
    </p>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
    @endif

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white border-l-8 border-green-500 shadow-md rounded-lg p-6 flex items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.42A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.92L12 14z"/>
            </svg>
            <div>
                <p class="text-sm text-gray-500">Jumlah Siswa</p>
                <h3 class="text-2xl font-bold text-green-700">{{ $jumlahSiswa ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white border-l-8 border-yellow-500 shadow-md rounded-lg p-6 flex items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h13M4 6h16M4 12h8" />
            </svg>
            <div>
                <p class="text-sm text-gray-500">Nilai Tercatat</p>
                <h3 class="text-2xl font-bold text-yellow-600">{{ $jumlahNilai ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white border-l-8 border-purple-500 shadow-md rounded-lg p-6 flex items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <div>
                <p class="text-sm text-gray-500">Hafalan Disetor</p>
                <h3 class="text-2xl font-bold text-purple-600">{{ $jumlahHafalan ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white border-l-8 border-blue-500 shadow-md rounded-lg p-6 flex items-center space-x-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4-4 4 4m0-8l-4 4-4-4" />
            </svg>
            <div>
                <p class="text-sm text-gray-500">Absensi Dicatat</p>
                <h3 class="text-2xl font-bold text-blue-600">{{ $jumlahAbsensi ?? 0 }}</h3>
            </div>
        </div>
    </div>

    {{-- Navigasi Akses Cepat --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <a href="{{ route('GuruNilai') }}" class="bg-white border border-gray-200 shadow hover:shadow-lg hover:scale-[1.02] transition-all rounded-xl p-6 flex items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h13M4 6h16M4 12h8" />
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-yellow-700">Manajemen Nilai</h3>
                <p class="text-sm text-gray-600">Input & kelola nilai siswa</p>
            </div>
        </a>

        <a href="{{ route('GuruHafalan') }}" class="bg-white border border-gray-200 shadow hover:shadow-lg hover:scale-[1.02] transition-all rounded-xl p-6 flex items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-purple-700">Hafalan Quran</h3>
                <p class="text-sm text-gray-600">Catat hafalan siswa</p>
            </div>
        </a>

        <a href="{{ route('GuruAbsensi') }}" class="bg-white border border-gray-200 shadow hover:shadow-lg hover:scale-[1.02] transition-all rounded-xl p-6 flex items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4-4 4 4m0-8l-4 4-4-4" />
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-blue-700">Absensi Siswa</h3>
                <p class="text-sm text-gray-600">Kelola kehadiran siswa</p>
            </div>
        </a>

      
    </div>
</div>
@endsection
