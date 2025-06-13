@extends('layouts.MainLembaga')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarLembaga')

<div class="font-[Verdana] px-6 py-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center gap-3 mb-8">
        <h2 class="text-2xl font-bold text-emerald-600">Dashboard Lembaga</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>

    <p class="text-gray-700 mb-10 text-sm">
        Selamat datang, {{ Auth::user()->pegawai->nama_lengkap ?? Auth::user()->username }}!
    </p>

    {{-- Statistik Utama --}}
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
                <p class="text-sm text-gray-500">Jumlah Nilai Tercatat</p>
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

    {{-- Nilai dan Hafalan Terbaru --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Nilai Terbaru</h3>
            <ul class="space-y-2 text-sm text-gray-600 max-h-48 overflow-y-auto">
                @forelse ($nilaiTerbaru as $nilai)
                    <li>
                        {{ $nilai->regisMapelSiswa->siswa->nama_siswa ?? 'Nama siswa tidak ditemukan' }} -
                        <strong>{{ $nilai->mapel->nama_mapel ?? 'Mapel tidak tersedia' }}: {{ $nilai->nilai }}</strong>
                    </li>
                @empty
                    <li>Tidak ada data nilai terbaru.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">Setoran Hafalan Terbaru</h3>
            <ul class="space-y-2 text-sm text-gray-600 max-h-48 overflow-y-auto">
                @forelse ($hafalanTerbaru as $hafalan)
                    <li>
                        {{ $hafalan->regisMapelSiswa->siswa->nama_siswa ?? 'Nama siswa tidak ditemukan' }} -
                        <strong>{{ $hafalan->surat ?? '-' }}: {{ $hafalan->ayat ?? '-' }}</strong>
                    </li>
                @empty
                    <li>Tidak ada data hafalan terbaru.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Cetak Data --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">Cetak Data</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('lembaga.cetak_siswa') }}"
               class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm shadow">
               Cetak Data Siswa
            </a>

            <a href="{{ route('lembaga.cetak_pegawai') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow">
               Cetak Data Pegawai
            </a>

            <a href="{{ route('lembaga.absensi_siswa_cetak', ['id' => Auth::id()]) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm shadow">
               Cetak Absensi Siswa
            </a>

            <a href="{{ route('lembaga.cetak_absensi_pegawai') }}"
               class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-lg text-sm shadow">
               Cetak Absensi Pegawai
            </a>

            <a href="{{ route('lembaga.hafalan_cetak', ['id' => Auth::id()]) }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm shadow">
               Cetak Hafalan Siswa
            </a>

            <a href="{{ route('lembaga.cetak_nilai_siswa', ['id' => Auth::id()]) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm shadow">
               Cetak Nilai Siswa
            </a>
        </div>
    </div>

</div>
@endsection
