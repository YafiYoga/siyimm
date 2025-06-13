@extends('layouts.MainYayasan')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md max-w-6xl mx-auto overflow-auto max-h-[600px] border border-gray-200 font-[Verdana]">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <h2 class="text-3xl font-extrabold text-gray-900">
            {{ auth()->user()->role == 'lembaga_sd' ? 'Rekap Absensi Siswa SD' : 'Rekap Absensi Siswa SMP' }}
        </h2>
        <img src="/SIYIMM.png" class="h-12" alt="SYIMM Logo">
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">

        <div>
            <label for="tahun_ajaran" class="block mb-1 font-semibold text-gray-700">Tahun Ajaran</label>
            <select name="tahun_ajaran" id="tahun_ajaran" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua</option>
                @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta }}" @selected(($filters['tahun_ajaran'] ?? '') == $ta)>{{ $ta }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="semester" class="block mb-1 font-semibold text-gray-700">Semester</label>
            <select name="semester" id="semester" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua</option>
                @foreach([1 => 'Ganjil', 2 => 'Genap'] as $key => $label)
                    <option value="{{ $key }}" @selected(($filters['semester'] ?? '') == $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="kelas" class="block mb-1 font-semibold text-gray-700">Kelas</label>
            <select name="kelas" id="kelas" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" @selected(($filters['kelas'] ?? '') == $kelas)>{{ $kelas }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="mapel" class="block mb-1 font-semibold text-gray-700">Mata Pelajaran</label>
            <select name="mapel" id="mapel" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua</option>
                @foreach($mapelList as $mapel)
                    <option value="{{ $mapel }}" @selected(($filters['mapel'] ?? '') == $mapel)>{{ $mapel }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="bulan" class="block mb-1 font-semibold text-gray-700">Bulan</label>
            <input type="month" name="bulan" id="bulan" value="{{ $filters['bulan'] ?? '' }}" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div>
            <label for="tanggal" class="block mb-1 font-semibold text-gray-700">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ $filters['tanggal'] ?? '' }}" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded shadow hover:bg-emerald-700 transition">Filter</button>
            <a href="{{ route('yayasan.absensi_siswa', ['jenjang' => $jenjang]) }}" class="bg-gray-300 text-gray-800 px-5 py-2 rounded shadow hover:bg-gray-400 transition">Reset</a>
            <a 
                 href="{{ route('yayasan.cetak_absensi_siswa', array_merge(
        ['jenjang' => $jenjang],
        request()->only(['tahun_ajaran', 'semester', 'kelas', 'mapel', 'bulan', 'tanggal', 'search'])
    )) }}" 
                target="_blank"
                class="bg-blue-600 text-white px-5 py-2 rounded shadow hover:bg-blue-700 transition">Cetak</a>
        </div>
    </form>

    {{-- Rekap Absensi Per Kelas --}}
   {{-- Rekap Absensi Per Tahun Ajaran, Kelas, dan Siswa --}}
@forelse($rekapAbsensi as $tahunAjaran => $dataTahun)
    <details class="group border border-emerald-300 rounded-lg shadow-md bg-white mb-8">
        <summary class="cursor-pointer select-none flex justify-between items-center px-5 py-3 font-bold text-lg text-emerald-900 bg-emerald-100 group-open:rounded-t-lg group-open:border-b group-open:border-emerald-300">
            <span>Tahun Ajaran: {{ $tahunAjaran }}</span>
            <svg class="w-5 h-5 text-emerald-600 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </summary>

        <div class="px-6 py-4 space-y-6 bg-emerald-50 rounded-b-lg">
            @foreach($dataTahun as $namaKelas => $dataKelas)
                <details class="group border border-gray-300 rounded-md bg-white">
                    <summary class="cursor-pointer select-none flex justify-between items-center px-4 py-2 font-semibold text-emerald-800 group-open:rounded-t-md group-open:border-b group-open:border-gray-200">
                        <span>Kelas {{ $namaKelas }}</span>
                        <svg class="w-4 h-4 text-emerald-600 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>

                    <div class="px-4 py-3 space-y-3 bg-emerald-50">
                        @forelse($dataKelas as $namaSiswa => $dataSiswa)
                            <details class="group border border-gray-200 rounded bg-white">
                                <summary class="cursor-pointer flex justify-between items-center px-4 py-2 font-medium text-emerald-700 group-open:rounded-t group-open:border-b group-open:border-gray-300">
                                    <span>{{ $loop->iteration }}. {{ $namaSiswa }}</span>
                                    <svg class="w-4 h-4 text-emerald-600 transition-transform duration-300 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </summary>
                                <div class="overflow-x-auto p-3">
                                    <table class="min-w-full border border-gray-200 rounded-lg text-sm">
                                        <thead class="bg-emerald-100 text-emerald-900 font-semibold">
                                            <tr>
                                                <th class="px-3 py-2 text-left">Mata Pelajaran</th>
                                                <th class="px-3 py-2 text-center">Hadir</th>
                                                <th class="px-3 py-2 text-center">Izin</th>
                                                <th class="px-3 py-2 text-center">Sakit</th>
                                                <th class="px-3 py-2 text-center">Alpha</th>
                                                <th class="px-3 py-2 text-center">Terlambat</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($dataSiswa['mapel'] as $namaMapel => $rekap)
                                                <tr class="hover:bg-emerald-50">
                                                    <td class="px-3 py-2">{{ $namaMapel }}</td>
                                                    <td class="px-3 py-2 text-center">{{ $rekap['hadir'] }}</td>
                                                    <td class="px-3 py-2 text-center">{{ $rekap['izin'] }}</td>
                                                    <td class="px-3 py-2 text-center">{{ $rekap['sakit'] }}</td>
                                                    <td class="px-3 py-2 text-center">{{ $rekap['alpha'] }}</td>
                                                    <td class="px-3 py-2 text-center">{{ $rekap['terlambat'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        @empty
                            <div class="text-gray-500 italic px-3 py-2">Tidak ada data siswa.</div>
                        @endforelse
                    </div>
                </details>
            @endforeach
        </div>
    </details>
@empty
    <div class="text-center mt-32 font-semibold text-emerald-800">
        Belum ada data absensi siswa.
    </div>
@endforelse


</div>
@endsection
