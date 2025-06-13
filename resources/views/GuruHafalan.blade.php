@extends('layouts.MainGuru')

@section('content')
<style>
    .select2-dropdown-tailwind {
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        border: 1px solid #d1d5db;
        font-size: 0.875rem;
    }

    .select2-selection-tailwind {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem;
        font-size: 0.875rem;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        min-height: 2.5rem;
        display: flex;
        align-items: center;
    }

    .select2-results__option {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }

    .select2-results__option--highlighted {
        background-color: #a7f3d0 !important; /* emerald-100 */
        color: #065f46 !important; /* emerald-800 */
    }
</style>

<div class="p-4 font-[Verdana] max-w-full">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-3 mb-8">
        <h1 class="text-2xl font-bold text-emerald-600">Manajemen Hafalan Siswa</h1>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>

    {{-- Alert Messages --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded border border-red-300">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white border border-green-700 rounded-md shadow-md flex items-center gap-2">
            <img src="/check.png" alt="Success" class="w-5 h-5" />
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('info'))
        <div class="mb-6 p-4 bg-blue-100 text-blue-700 rounded border border-blue-300">
            {{ session('info') }}
        </div>
    @endif

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


   {{-- Form Tambah / Edit Hafalan Quran --}}
<div class="bg-white shadow-lg rounded-md p-6 max-w-4xl mx-auto mb-12">
    <form action="{{ isset($editMode) ? route('guru.hafalan.update', $editMode->id) : route('guru.hafalan.store') }}"
        method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf
        @if(isset($editMode)) @method('PUT') @endif

        @php $shownOptions = []; @endphp

        <div>
            <label for="id_regis_mapel_siswa" class="block mb-2 text-sm font-semibold text-gray-700">Nama Siswa</label>
            <select name="id_regis_mapel_siswa" id="id_regis_mapel_siswa"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                required>
                <option value="" disabled selected>-- Pilih Siswa --</option>
                @foreach($siswa as $s)
                    @foreach($s->regisMapelSiswas as $regis)
                        @php
                            $kelasNama = $regis->kelasMapel->kelas->nama_kelas ?? 'Kelas tidak tersedia';
                            $key = $s->nama_siswa . '_' . $kelasNama;
                        @endphp
                        @if(!in_array($key, $shownOptions))
                            <option value="{{ $regis->id }}"
                                {{ old('id_regis_mapel_siswa', $editMode->id_regis_mapel_siswa ?? '') == $regis->id ? 'selected' : '' }}>
                                {{ $s->nama_siswa }} - Kelas: {{ $kelasNama }}
                            </option>
                            @php $shownOptions[] = $key; @endphp
                        @endif
                    @endforeach
                @endforeach
            </select>
        </div>

        <div>
            <label for="id_surat" class="block mb-2 text-sm font-semibold text-gray-700">Surat</label>
            @if($surats->isEmpty())
                <p class="text-red-500 text-sm mb-2">Data surat tidak tersedia.</p>
            @endif
            <select name="id_surat" id="id_surat"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                required>
                <option value="" disabled selected>-- Pilih Surat --</option>
                @foreach($surats as $surat)
                    <option value="{{ $surat->id }}"
                        {{ old('id_surat', $editMode->id_surat ?? '') == $surat->id ? 'selected' : '' }}>
                        {{ $surat->nama_surat ?? $surat->nama ?? 'Nama surat tidak tersedia' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="ayat_dari" class="block mb-2 text-sm font-semibold text-gray-700">Ayat Dari</label>
            <input type="number" id="ayat_dari" name="ayat_dari" min="1"
                value="{{ old('ayat_dari', $editMode->ayat_dari ?? '') }}"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                required>
        </div>

        <div>
            <label for="ayat_sampai" class="block mb-2 text-sm font-semibold text-gray-700">Ayat Sampai</label>
            <input type="number" id="ayat_sampai" name="ayat_sampai" min="1"
                value="{{ old('ayat_sampai', $editMode->ayat_sampai ?? '') }}"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                required>
        </div>

        <div>
            <label for="tgl_setor" class="block mb-2 text-sm font-semibold text-gray-700">Tanggal Setor</label>
            <input type="date" id="tgl_setor" name="tgl_setor"
                value="{{ old('tgl_setor', isset($editMode) ? \Carbon\Carbon::parse($editMode->tgl_setor)->format('Y-m-d') : '') }}"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                required>
        </div>

        <div>
            <label for="keterangan" class="block mb-2 text-sm font-semibold text-gray-700">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="3"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500">{{ old('keterangan', $editMode->keterangan ?? '') }}</textarea>
        </div>

        <div class="md:col-span-2 flex gap-4 justify-start mt-4">
            <button type="submit"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-2 rounded-lg text-sm shadow transition duration-300">
                {{ isset($editMode) ? 'Perbarui Hafalan' : 'Tambah Hafalan' }}
            </button>
            @if(isset($editMode))
                <a href="{{ route('GuruHafalan') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm shadow transition duration-300">
                    Batal
                </a>
            @endif
        </div>
    </form>
</div>

   {{-- Daftar Hafalan --}}
<div class="bg-white p-6 rounded-xl shadow-md max-w-6xl mx-auto overflow-auto max-h-[600px] border border-gray-200">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Daftar Hafalan Siswa</h2>

    {{-- Filter, Tombol & Search --}}
{{-- Filter, Tombol & Search --}}
<form id="filterForm" method="GET" action="{{ route('GuruHafalan') }}"
    class="bg-emerald-50 p-4 rounded-lg mb-6 shadow-inner">
    
    {{-- Baris Filter --}}
    <div class="flex flex-wrap md:flex-nowrap gap-4 mb-4">
        {{-- Bulan --}}
        <div class="flex flex-col w-full md:w-auto min-w-[150px]">
            <label for="filterBulan" class="font-semibold text-gray-700 mb-1">Bulan</label>
            <select name="bulan" id="filterBulan"
                class="border border-gray-300 rounded-lg p-2 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500">
                @foreach(range(1,12) as $b)
                    <option value="{{ str_pad($b, 2, '0', STR_PAD_LEFT) }}"
                        {{ ($bulan == str_pad($b, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tahun --}}
        <div class="flex flex-col w-full md:w-auto min-w-[150px]">
            <label for="filterTahun" class="font-semibold text-gray-700 mb-1">Tahun</label>
            <select name="tahun" id="filterTahun"
                class="border border-gray-300 rounded-lg p-2 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500">
                @for($y = date('Y') - 5; $y <= date('Y') + 1; $y++)
                    <option value="{{ $y }}" {{ ($tahun == $y) ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        {{-- Kelas --}}
        <div class="flex flex-col w-full md:w-auto min-w-[150px]">
            <label for="filterKelas" class="font-semibold text-gray-700 mb-1">Kelas</label>
            <select name="nama_kelas" id="filterKelas"
                class="border border-gray-300 rounded-lg p-2 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->nama_kelas }}"
                        {{ (request('nama_kelas') == $kelas->nama_kelas) ? 'selected' : '' }}>
                        {{ $kelas->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Surat --}}
        <div class="flex flex-col w-full md:w-auto min-w-[150px]">
            <label for="filterSurat" class="font-semibold text-gray-700 mb-1">Surat</label>
            <select name="nama_surat" id="filterSurat"
                class="border border-gray-300 rounded-lg p-2 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500">
                <option value="">-- Semua Surat --</option>
                @foreach($surats as $surat)
                    <option value="{{ $surat->nama_surat }}"
                        {{ request('nama_surat') == $surat->nama_surat ? 'selected' : '' }}>
                        {{ $surat->nama_surat }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Tombol Filter dan Reset --}}
    <div class="flex flex-wrap md:flex-nowrap justify-between items-center gap-4">
        <div class="flex gap-2">
            <button type="submit"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm shadow transition duration-300 whitespace-nowrap">
                Filter
            </button>

            <a href="{{ route('GuruHafalan') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded-lg text-sm shadow transition duration-300 whitespace-nowrap">
                Reset
            </a>
        </div>

        {{-- Search Field --}}
        <div class="w-full md:w-auto">
            <input
                id="search"
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama siswa..."
                class="w-full md:w-64 border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                onkeyup="filterTable()"
                aria-label="Cari nama siswa"
            >
        </div>
    </div>
</form>


    {{-- Tabel Hafalan --}}
    @if($hafalanGrouped->isEmpty())
        <p class="text-gray-600 text-center py-12 italic">Belum ada data hafalan siswa.</p>
    @else
        <table id="hafalanPerSiswaTable" class="w-full table-auto border-collapse text-sm rounded-xl overflow-hidden">
            <thead class="bg-emerald-100 text-emerald-800 text-left sticky top-0 z-10">
                <tr>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Nama Siswa</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Kelas</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Hafalan (Surat : Ayat : Tgl Setor)</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($hafalanGrouped as $siswaId => $hafalans)
                    @php
                        $firstHafalan = $hafalans->first();
                        $namaSiswa = optional($firstHafalan?->regisMapelSiswa?->siswa)->nama_siswa ?? '-';
                        $namaKelas = optional($firstHafalan?->regisMapelSiswa?->kelasMapel?->kelas)->nama_kelas ?? '-';
                    @endphp
                    <tr class="hover:bg-emerald-50 transition duration-200" data-nama="{{ strtolower($namaSiswa) }}">
                        <td class="px-5 py-3 align-top border-b border-gray-200">{{ $namaSiswa }}</td>
                        <td class="px-5 py-3 align-top border-b border-gray-200">{{ $namaKelas }}</td>
                        <td class="px-5 py-3 align-top border-b border-gray-200 max-w-[350px] whitespace-normal">
                            @foreach($hafalans as $hafalan)
                                <div class="mb-2 border-b border-dotted border-gray-300 pb-1">
                                    <strong>Surat:</strong> {{ $hafalan->surat->nama_surat ?? 'Tidak ada surat' }}<br>
                                    <strong>Ayat:</strong> {{ $hafalan->ayat_dari }} - {{ $hafalan->ayat_sampai }}<br>
                                    <strong>Tanggal Setor:</strong> {{ \Carbon\Carbon::parse($hafalan->tgl_setor)->format('d-m-Y') }}
                                </div>
                            @endforeach
                        </td>
                        <td class="px-5 py-3 align-top border-b border-gray-200 text-center">
                            <div class="flex flex-col gap-3 items-center">
                                @foreach($hafalans as $hafalan)
                                    <div class="flex gap-3 justify-center mb-1">
                                        <a href="{{ route('GuruHafalan', ['edit' => $hafalan->id]) }}"
                                           class="flex items-center gap-1 px-3 py-1 text-xs text-white bg-blue-600 hover:bg-blue-700 rounded-full shadow transition"
                                           title="Edit Hafalan {{ $hafalan->surat->nama_surat ?? 'Surat' }}">
                                            <img src="/edit.png" alt="Edit" class="w-3 h-3"> Edit
                                        </a>

                                        <form id="deleteHafalanForm{{ $hafalan->id }}" action="{{ route('guru.hafalan.destroy', $hafalan->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDeleteHafalan('{{ $hafalan->id }}')"
                                                    class="flex items-center gap-1 px-3 py-1 text-xs text-white bg-red-600 hover:bg-red-700 rounded-full shadow transition"
                                                    title="Hapus Hafalan {{ $hafalan->surat->nama_surat ?? 'Surat' }}">
                                                <img src="/bin.png" alt="Delete" class="w-3 h-3"> Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- JavaScript --}}
<script>
    function confirmDeleteHafalan(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data hafalan yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteHafalanForm' + id).submit();
            }
        });
    }

    function filterTable() {
        const input = document.getElementById("search");
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");

        rows.forEach(row => {
            const namaCell = row.querySelector("td:nth-child(1)");
            if (namaCell) {
                const nama = namaCell.textContent.toLowerCase();
                row.style.display = nama.includes(filter) ? "" : "none";
            }
        });
    }
</script>
@endsection