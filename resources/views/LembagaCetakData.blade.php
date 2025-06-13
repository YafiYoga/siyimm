@extends('layouts.MainLembaga')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Cetak Data Siswa dan Pegawai</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-2">Cetak Data Siswa</h2>
            <a href="{{ route('lembaga.export_siswa_pdf') }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">Cetak PDF</a>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-2">Cetak Data Pegawai</h2>
            <a href="{{ route('lembaga.export_pegawai_pdf') }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded">Cetak PDF</a>
        </div>
    </div>
</div>
@endsection
