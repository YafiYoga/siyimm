@extends('layouts.MainGuru')

@section('content')
<h1>Rekap Absensi Siswa - Semester {{ $semester }}, Tahun Ajaran {{ $tahunAjaran }}</h1>

<table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr>
            <th class="border border-gray-300 px-4 py-2">Nama Siswa</th>
            <th class="border border-gray-300 px-4 py-2">Tanggal</th>
            <th class="border border-gray-300 px-4 py-2">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $absen)
        <tr>
            <td class="border border-gray-300 px-4 py-2">{{ $absen->siswa->nama_siswa ?? 'N/A' }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}</td>
            <td class="border border-gray-300 px-4 py-2 capitalize">{{ $absen->status }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="border border-gray-300 px-4 py-2 text-center">Data absensi tidak ditemukan.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
