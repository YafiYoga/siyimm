@extends('layouts.MainGuru')

@section('content')
<h1>Rekap Hafalan Quran Siswa - Semester {{ $semester }}, Tahun Ajaran {{ $tahunAjaran }}</h1>

<table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr>
            <th class="border border-gray-300 px-4 py-2">Nama Siswa</th>
            <th class="border border-gray-300 px-4 py-2">Surat</th>
            <th class="border border-gray-300 px-4 py-2">Ayat Dari</th>
            <th class="border border-gray-300 px-4 py-2">Ayat Sampai</th>
            <th class="border border-gray-300 px-4 py-2">Tanggal Setor</th>
            <th class="border border-gray-300 px-4 py-2">Penilai</th>
            <th class="border border-gray-300 px-4 py-2">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $hafalan)
        <tr>
            <td class="border border-gray-300 px-4 py-2">{{ $hafalan->siswa->nama_siswa ?? 'N/A' }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $hafalan->surat }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $hafalan->ayat_dari }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $hafalan->ayat_sampai }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($hafalan->tanggal_setor)->format('d-m-Y') }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $hafalan->penilai }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $hafalan->keterangan_hafalan }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="border border-gray-300 px-4 py-2 text-center">Data hafalan tidak ditemukan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
