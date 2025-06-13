@extends('layouts.MainLembaga')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md max-w-6xl mx-auto overflow-auto max-h-[600px] border border-gray-200 font-[Verdana]">

    <div class="flex items-center gap-3 mb-6">
        <h2 class="text-3xl font-extrabold text-gray-900">Monitoring Nilai Siswa</h2>
        <img src="/SIYIMM.png" class="h-12" alt="SYIMM Logo">
    </div>

    {{-- Filter Form --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">

        <div>
            <label for="tahun_ajaran" class="block mb-1 font-semibold text-gray-700">Tahun Ajaran</label>
            <select name="tahun_ajaran" id="tahun_ajaran" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua</option>
                @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta }}" @selected(request('tahun_ajaran') == $ta)>{{ $ta }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="semester" class="block mb-1 font-semibold text-gray-700">Semester</label>
            <select name="semester" id="semester" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua</option>
                @foreach(['Ganjil', 'Genap'] as $sem)
                    <option value="{{ $sem }}" @selected(request('semester') == $sem)>{{ $sem }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="kelas" class="block mb-1 font-semibold text-gray-700">Kelas</label>
            <select name="kelas" id="kelas" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" @selected(request('kelas') == $kelas)>{{ $kelas }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="mapel" class="block mb-1 font-semibold text-gray-700">Mata Pelajaran</label>
            <select name="mapel" id="mapel" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua</option>
                @foreach($mapelList as $mapel)
                    <option value="{{ $mapel }}" @selected(request('mapel') == $mapel)>{{ $mapel }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded shadow hover:bg-emerald-700 transition">
            Filter
        </button>
        <a href="{{ route('lembaga.nilai_siswa', ['id' => $id]) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded shadow hover:bg-gray-400 transition">
    Reset
</a>
<a href="{{ route('lembaga.cetak_nilai_siswa', ['id' => $id] + request()->query()) }}" 
   target="_blank"
   class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
    Cetak Data
</a>
    </form>

    {{-- Tampilkan data seperti sebelumnya --}}
    @if (empty($groupedData))
        <div class="text-center mt-32 text-gray-600 text-lg font-medium italic">
            Belum ada data nilai siswa yang tersedia.
        </div>
    @else
        <div class="space-y-6">
            @foreach($groupedData as $ta => $semesters)
                <details class="group border border-gray-300 rounded-lg shadow-sm bg-white">
                    <summary
                        class="cursor-pointer select-none flex justify-between items-center px-5 py-3 font-semibold text-emerald-800 group-open:rounded-t-lg group-open:border-b group-open:border-gray-300"
                    >
                        <span>Tahun Ajaran: {{ $ta }}</span>
                        <svg
                            class="w-5 h-5 text-emerald-600 transition-transform duration-300 group-open:rotate-180"
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

                    <div class="px-6 py-4 space-y-5 bg-emerald-50 rounded-b-lg">
                        @foreach($semesters as $semester => $kelasList)
                            <details class="group border border-gray-200 rounded-md bg-white">
                                <summary
                                    class="cursor-pointer select-none flex justify-between items-center px-4 py-2 font-semibold text-emerald-700 group-open:rounded-t-md group-open:border-b group-open:border-gray-300"
                                >
                                    <span>Semester: {{ $semester }}</span>
                                    <svg
                                        class="w-4 h-4 text-emerald-600 transition-transform duration-300 group-open:rotate-180"
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

                                <div class="px-4 py-3 space-y-4">
                                    @foreach($kelasList as $kelas => $mapels)
                                        <details class="group border border-gray-300 rounded-md bg-white">
                                            <summary
                                                class="cursor-pointer select-none flex justify-between items-center px-3 py-1.5 font-semibold text-emerald-800 group-open:rounded-t-md group-open:border-b group-open:border-gray-300"
                                            >
                                                <span>Kelas: {{ $kelas }}</span>
                                                <svg
                                                    class="w-4 h-4 text-emerald-700 transition-transform duration-300 group-open:rotate-180"
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

                                            <div class="px-3 py-2 space-y-4">
                                                @foreach($mapels as $mapel => $siswaList)
                                                    <div class="rounded-md shadow-sm border border-gray-200 p-4 bg-white">
                                                        <h5
                                                            class="font-semibold mb-3 text-gray-800 text-lg border-b border-gray-300 pb-1"
                                                        >
                                                            {{ $mapel }}
                                                        </h5>

                                                        <div class="overflow-auto max-h-72">
                                                            <table
                                                                class="w-full text-sm text-left text-gray-700 border-collapse rounded-md overflow-hidden"
                                                            >
                                                                <thead
                                                                    class="sticky top-0 bg-emerald-100 text-emerald-800 font-semibold border-b border-emerald-300"
                                                                >
                                                                    <tr>
                                                                        <th class="px-3 py-2 border-r border-emerald-300 w-12 text-center">No</th>
                                                                        <th class="px-3 py-2 border-r border-emerald-300">Nama</th>
                                                                        <th class="px-3 py-2 border-r border-emerald-300 text-center w-20">Tugas</th>
                                                                        <th class="px-3 py-2 border-r border-emerald-300 text-center w-20">UTS</th>
                                                                        <th class="px-3 py-2 border-r border-emerald-300 text-center w-20">UAS</th>
                                                                        <th class="px-3 py-2 text-center w-24">Rata-rata</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($siswaList as $i => $siswa)
                                                                        <tr class="border-b border-emerald-200 hover:bg-emerald-50">
                                                                            <td class="px-3 py-2 border-r border-emerald-300 text-center">
                                                                                {{ $i + 1 }}
                                                                            </td>
                                                                            <td class="px-3 py-2 border-r border-emerald-300">
                                                                                {{ $siswa['nama_siswa'] }}
                                                                            </td>
                                                                            <td class="px-3 py-2 border-r border-emerald-300 text-center">
                                                                                {{ $siswa['nilai_tugas'] ?? '-' }}
                                                                            </td>
                                                                            <td class="px-3 py-2 border-r border-emerald-300 text-center">
                                                                                {{ $siswa['nilai_uts'] ?? '-' }}
                                                                            </td>
                                                                            <td class="px-3 py-2 border-r border-emerald-300 text-center">
                                                                                {{ $siswa['nilai_uas'] ?? '-' }}
                                                                            </td>
                                                                            <td class="px-3 py-2 text-center font-semibold text-emerald-700">
                                                                                {{ $siswa['nilai_akhir'] ?? '-' }}
                                                                            </td>
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
                </details>
            @endforeach
        </div>
    @endif
</div>
@endsection

