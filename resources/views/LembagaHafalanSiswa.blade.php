@extends('layouts.MainLembaga')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md max-w-6xl mx-auto overflow-auto max-h-[600px] border border-gray-200 font-[Verdana]">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6 gap-4">
        <h2 class="text-3xl font-extrabold text-gray-900 flex-shrink-0">
            Manajemen Hafalan Quran
        </h2>
        <img src="/SIYIMM.png" class="h-12" alt="SYIMM Logo" />
    </div>

    {{-- Filter Form --}}
   {{-- Filter Form --}}
<form method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
    <div>
        <label for="kelas" class="block mb-1 font-semibold text-gray-700">Kelas</label>
        <select name="kelas" id="kelas" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            <option value="">Semua</option>
            @foreach($kelasList as $kelas)
                <option value="{{ $kelas }}" @selected(request('kelas') == $kelas)>{{ $kelas }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="tahun_ajaran" class="block mb-1 font-semibold text-gray-700">Tahun Ajaran</label>
        <select name="tahun_ajaran" id="tahun_ajaran" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            <option value="">Semua</option>
            @foreach($tahunAjaranList as $tahun)
                <option value="{{ $tahun }}" @selected(request('tahun_ajaran') == $tahun)>{{ $tahun }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="semester" class="block mb-1 font-semibold text-gray-700">Semester</label>
        <select name="semester" id="semester" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            <option value="">Semua</option>
            <option value="Ganjil" @selected(request('semester') == 'Ganjil')>Ganjil</option>
            <option value="Genap" @selected(request('semester') == 'Genap')>Genap</option>
        </select>
    </div>

    <div>
        <label for="nama" class="block mb-1 font-semibold text-gray-700">Cari Nama</label>
        <input type="text" name="nama" id="nama" value="{{ request('nama') }}" placeholder="Nama Siswa"
            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400" />
    </div>

    <div class="col-span-1 md:col-span-2 flex gap-2 mt-2 md:mt-0">
        <button type="submit"
            class="bg-emerald-600 text-white px-5 py-2 rounded shadow hover:bg-emerald-700 transition">
            Filter
        </button>

        <a href="{{ route('lembaga.hafalan_siswa', ['id' => $id]) }}" class="bg-gray-300 text-gray-800 px-5 py-2 rounded shadow hover:bg-gray-400 transition">
                Reset
            </a>
             <a href="{{ route('lembaga.hafalan_cetak', array_merge(['id' => $id], request()->query())) }}" target="_blank" 
   class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
    Cetak Data
</a>

    </div>
</form>


    {{-- Data Hafalan Quran --}}
@php
    $groupedByKelas = collect($rekapHafalan)->groupBy('kelas');
@endphp

@forelse($groupedByKelas as $kelas => $siswaList)
    <details class="group border border-gray-300 rounded-lg shadow-sm bg-white mb-6">
        <summary class="cursor-pointer select-none flex justify-between items-center px-5 py-3 font-semibold text-emerald-800 group-open:rounded-t-lg group-open:border-b group-open:border-gray-300">
            <span class="text-xl font-bold">Kelas {{ $kelas }}</span>
            <span>Jumlah Siswa: {{ $siswaList->count() }}</span>
        </summary>

        <div class="px-6 py-4 bg-emerald-50 rounded-b-lg space-y-4 overflow-x-auto">
            @foreach($siswaList as $rekap)
                <div class="border border-gray-200 rounded-md bg-white p-4 shadow-sm">
                    <h4 class="font-semibold text-emerald-700">{{ $rekap['nama_siswa'] }} <span class="text-sm text-gray-500">({{ $rekap['total_hafalan'] }} hafalan)</span></h4>
                    <table class="mt-2 w-full text-sm border border-gray-200 rounded">
                        <thead class="bg-emerald-100 text-emerald-900 font-semibold">
                            <tr>
                                <th class="px-3 py-2 text-left">Nama Surat</th>
                                <th class="px-3 py-2 text-center">Ayat</th>
                                <th class="px-3 py-2 text-center">Tanggal Setor</th>
                                <th class="px-3 py-2 text-center">Guru</th>
                                <th class="px-3 py-2 text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($rekap['detail_hafalan'] as $detail)
                                <tr>
                                    <td class="px-3 py-2">{{ $detail['nama_surat'] ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center">{{ $detail['ayat_dari'] }} - {{ $detail['ayat_sampai'] }}</td>
                                    <td class="px-3 py-2 text-center">{{ \Carbon\Carbon::parse($detail['tgl_setor'])->format('d-m-Y') }}</td>
                                    <td class="px-3 py-2 text-center">{{ $detail['guru'] ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center">{{ $detail['keterangan'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </details>
@empty
    <div class="text-center mt-32 font-semibold text-emerald-800">
        Data hafalan tidak ditemukan untuk filter yang dipilih.
    </div>
@endforelse


</div>
@endsection
