@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 p-8 font-[Verdana] mt-20 mb-20 ">
    <h2 class="text-3xl font-bold mb-10 text-green-800">Dashboard Staff</h2>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
    @endif

    {{-- Total Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white border-l-8 border-green-500 shadow-md rounded-lg p-6 flex items-center space-x-4">
            {{-- Icon Siswa --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z" />
            </svg>
            <div>
                <p class="text-sm text-gray-500">Total Siswa</p>
                <h3 class="text-2xl font-bold text-green-700">{{ $totalSiswa }}</h3>
            </div>
        </div>

        <div class="bg-white border-l-8 border-blue-500 shadow-md rounded-lg p-6 flex items-center space-x-4">
            {{-- Icon Pegawai --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M9 12h6M12 4a4 4 0 110 8 4 4 0 010-8z" />
            </svg>
            <div>
                <p class="text-sm text-gray-500">Total Pegawai</p>
                <h3 class="text-2xl font-bold text-blue-700">{{ $totalPegawai }}</h3>
            </div>
        </div>
    </div>

    {{-- Navigation Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <a href="{{ route('siswa.index') }}" class="bg-white border border-gray-200 shadow hover:shadow-lg hover:scale-[1.02] transition-all rounded-xl p-6 flex items-center gap-4">
            {{-- Icon Data Siswa --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5a12.083 12.083 0 01-6.16-10.922L12 14z" />
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-green-700">Data Siswa</h3>
                <p class="text-sm text-gray-600">Lihat seluruh data siswa</p>
            </div>
        </a>

        <a href="{{ route('TambahSiswaStaff') }}" class="bg-white border border-gray-200 shadow hover:shadow-lg hover:scale-[1.02] transition-all rounded-xl p-6 flex items-center gap-4">
            {{-- Icon Tambah Siswa --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-green-700">Tambah Siswa</h3>
                <p class="text-sm text-gray-600">Input data siswa baru</p>
            </div>
        </a>

        <a href="{{ route('pegawai.index') }}" class="bg-white border border-gray-200 shadow hover:shadow-lg hover:scale-[1.02] transition-all rounded-xl p-6 flex items-center gap-4">
            {{-- Icon Data Pegawai --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M9 12h6M12 4a4 4 0 110 8 4 4 0 010-8z" />
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-blue-700">Data Pegawai</h3>
                <p class="text-sm text-gray-600">Lihat seluruh data pegawai</p>
            </div>
        </a>

        <a href="{{ route('TambahPegawaiStaff') }}" class="bg-white border border-gray-200 shadow hover:shadow-lg hover:scale-[1.02] transition-all rounded-xl p-6 flex items-center gap-4">
            {{-- Icon Tambah Pegawai --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-blue-700">Tambah Pegawai</h3>
                <p class="text-sm text-gray-600">Input data pegawai baru</p>
            </div>
        </a>
    </div>

    {{-- Daftar Pengumuman --}}
    <div class="bg-white shadow-lg border border-gray-200 rounded-md p-6 font-[Verdana] text-sm max-w-6xl mx-auto">
        <h2 class="text-xl font-bold text-green-800 mb-4 flex items-center gap-2">
            {{-- Icon Megaphone --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Daftar Pengumuman
        </h2>

        @if ($pengumumen->isEmpty())
            <p class="text-gray-600">Belum ada pengumuman.</p>
        @else
            <div class="space-y-4">
                @foreach ($pengumumen as $item)
                    <div class="border border-gray-300 rounded-md p-4  bg-gray-50 hover:bg-green-50 transition-all duration-200">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            {{-- Icon Pengumuman --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5l-7 7h10l-7 7" />
                            </svg>
                            {{ $item->judul }}
                        </h3>
                        <p class="text-xs italic text-gray-500 mb-2">Ditujukan: {{ ucfirst(str_replace('_', ' ', $item->ditujukan_kepada)) }}</p>
                        <p class="text-gray-700">{{ $item->isi }}</p>
                        <p class="text-xs text-gray-500 mt-3">Dibuat pada: {{ $item->created_at->format('d M Y H:i') }}</p>

                        
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
