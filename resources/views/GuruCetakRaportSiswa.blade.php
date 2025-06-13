@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-bold text-emerald-600 mb-4">Cetak Raport</h1>

    <div class="bg-white rounded-xl shadow-md p-6 space-y-4">
        <form action="{{ route('guru.raport.cetak') }}" method="GET" target="_blank" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                <select name="kelas" class="w-full mt-1 p-2 border rounded-md focus:ring-2 focus:ring-emerald-500">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($kelasList as $kelas)
                    <option value="{{ $kelas }}">{{ $kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Semester</label>
                <select name="semester" class="w-full mt-1 p-2 border rounded-md focus:ring-2 focus:ring-emerald-500">
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 transition">
                    Cetak Raport
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
