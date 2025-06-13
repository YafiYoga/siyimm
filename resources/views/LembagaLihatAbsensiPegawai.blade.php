@extends('layouts.MainLembaga')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md max-w-6xl mx-auto overflow-auto max-h-[600px] border border-gray-200 font-[Verdana]">

    <h1 class="text-3xl font-extrabold mb-6 text-center text-green-700 flex items-center justify-center gap-3">
        Rekap Absensi Pegawai
        <img src="/SIYIMM.png" class="h-12" alt="SIYIMM Logo">
    </h1>

    {{-- Filter Tahun dan Bulan --}}
<form method="GET" action="{{ route('lembaga.absensi_pegawai') }}"
    class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end bg-gray-50 p-5 rounded-md shadow border border-gray-200 mb-10">

    {{-- Tahun --}}
    <div class="relative">
        <label for="tahun" class="block mb-1 text-sm text-gray-600 font-semibold">Tahun</label>
        <select name="tahun" id="tahun"
            class="pl-3 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 w-full">
            @for ($y = now()->year; $y >= 2020; $y--)
                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <div class="absolute left-3 top-9 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z" />
            </svg>
        </div>
    </div>

    {{-- Bulan --}}
    <div class="relative">
        <label for="bulan" class="block mb-1 text-sm text-gray-600 font-semibold">Bulan</label>
        <select name="bulan" id="bulan"
            class="pl-3 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 w-full">
            <option value="">Semua Bulan</option>
            @foreach (range(1, 12) as $m)
                <option value="{{ sprintf('%02d', $m) }}" {{ request('bulan') == sprintf('%02d', $m) ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
        <div class="absolute left-3 top-9 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13v6a1 1 0 01-2 0v-6L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="flex gap-2 justify-center md:justify-start">
        <button type="submit"
            class="flex-1 flex items-center justify-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-300 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z" />
            </svg>
            Terapkan Filter
        </button>

        <a href="{{ route('lembaga.absensi_pegawai') }}"
            class="flex-1 flex items-center justify-center gap-2 bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 transition-colors duration-300 shadow-md"
            title="Reset Filter">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Reset
        </a>

        <a href="{{ route('lembaga.cetak_absensi_pegawai', ['tahun' => request('tahun'), 'bulan' => request('bulan')]) }}" target="_blank"
            class="flex-1 flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors duration-300 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 17h2a2 2 0 002-2v-5a2 2 0 00-2-2h-2M7 17H5a2 2 0 01-2-2v-5a2 2 0 012-2h2m10 7v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5m3-7v4" />
            </svg>
            Cetak Rekap Absensi
        </a>
    </div>
</form>



    @if(empty($grouped))
        <p class="text-center text-gray-500 italic mt-20 text-lg">Tidak ada data absensi untuk periode ini.</p>
    @else
        <div class="space-y-8">
            @foreach($grouped as $tahun => $bulanGroup)
                <details class="group border border-gray-300 rounded-lg shadow-sm bg-green-50">
                    <summary
                        class="cursor-pointer select-none flex justify-between items-center px-6 py-4 font-semibold text-green-800 group-open:rounded-t-lg group-open:border-b group-open:border-gray-300"
                    >
                        <span>Tahun: {{ $tahun }}</span>
                        <svg
                            class="w-6 h-6 text-green-600 transition-transform duration-300 group-open:rotate-180"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>

                    <div class="px-6 py-4 space-y-6 bg-white rounded-b-lg border border-t-0 border-green-200">
                        @foreach($bulanGroup as $bulan => $tanggalGroup)
                            <details class="group border border-gray-200 rounded-md bg-green-50">
                                <summary
                                    class="cursor-pointer select-none flex justify-between items-center px-5 py-3 font-semibold text-green-700 group-open:rounded-t-md group-open:border-b group-open:border-gray-300"
                                >
                                    <span>Bulan: {{ \Carbon\Carbon::create()->month((int) $bulan)->translatedFormat('F') }} ({{ collect($tanggalGroup)->flatten()->count() }} absensi)</span>
                                    <svg
                                        class="w-5 h-5 text-green-600 transition-transform duration-300 group-open:rotate-180"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </summary>

                                <div class="px-5 py-4 space-y-5 bg-white rounded-b-md border border-t-0 border-green-200">
                                    @foreach($tanggalGroup as $tanggal => $absensis)
                                        <div>
                                            <h3 class="text-lg font-semibold mb-3 text-green-700 border-b border-green-300 pb-1">
                                                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }} ({{ count($absensis) }} absensi)
                                            </h3>
                                            <div class="overflow-auto max-h-72 rounded shadow border border-green-200">
                                                <table class="min-w-full table-auto text-sm border-collapse border border-green-300">
                                                    <thead class="bg-green-100 text-green-800 uppercase font-semibold sticky top-0 z-10">
                                                        <tr>
                                                            <th class="border px-3 py-2 text-center w-10">#</th>
                                                            <th class="border px-3 py-2">Nama Pegawai</th>
                                                            <th class="border px-3 py-2 w-24">NIY</th>
                                                            <th class="border px-3 py-2">Unit Kerja</th>
                                                            <th class="border px-3 py-2 w-48 text-center">Waktu Masuk</th>
                                                            <th class="border px-3 py-2 w-48 text-center">Waktu Keluar</th>
                                                            <th class="border px-3 py-2 w-28 text-center">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($absensis as $index => $absen)
                                                            <tr class="{{ $index % 2 == 0 ? 'bg-green-50' : '' }}">
                                                                <td class="border px-3 py-2 text-center">{{ $index + 1 }}</td>
                                                                <td class="border px-3 py-2">{{ $absen->pegawai->nama_lengkap }}</td>
                                                                <td class="border px-3 py-2 text-center">{{ $absen->pegawai->niy }}</td>
                                                                <td class="border px-3 py-2">{{ $absen->pegawai->unit_kerja }}</td>
                                                                <td class="border px-3 py-2 text-center">
                                                                    {{ $absen->waktu_masuk ? \Carbon\Carbon::parse($absen->waktu_masuk)->translatedFormat('d F Y H:i') : '-' }}
                                                                </td>
                                                                <td class="border px-3 py-2 text-center">
                                                                    {{ $absen->waktu_keluar ? \Carbon\Carbon::parse($absen->waktu_keluar)->translatedFormat('d F Y H:i') : '-' }}
                                                                </td>
                                                                <td class="border px-3 py-2 text-center">{{ ucfirst($absen->status) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    </div>
                </details>
            @endforeach
        </div>
    @endif

</div>
@endsection
