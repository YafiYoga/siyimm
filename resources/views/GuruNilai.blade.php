@extends('layouts.MainGuru')

@section('content')
<style>
    /* Custom styling agar Select2 sesuai Tailwind */
    .select2-container--default .select2-selection--single {
        @apply w-full border border-gray-300 rounded-md py-2 px-3 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500;
        display: flex !important;
        align-items: center !important;
        min-height: 2.5rem;
        border-radius: 0.375rem;
    }

    .select2-dropdown {
        border-radius: 0.375rem !important;
        border: 1px solid #d1d5db !important; /* border-gray-300 */
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important;
        font-size: 0.875rem !important;
    }

    .select2-results__option {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.875rem !important;
    }

    .select2-results__option--highlighted {
        background-color: #a7f3d0 !important; /* emerald-100 */
        color: #065f46 !important; /* emerald-800 */
    }
</style>

<div class="p-4 font-[Verdana] max-w-full">
   <div class="flex flex-col md:flex-row items-start md:items-center gap-3 mb-8">
        <h1 class="text-2xl font-bold text-emerald-600">Manajemen Nilai Siswa</h1>
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
        {{-- Form Input / Edit Nilai --}}
    <div class="bg-white shadow-lg rounded-md p-6 max-w-4xl mx-auto">
        <h2 class="text-xl font-semibold mb-5 text-emerald-600">{{ $editMode ? 'Edit Nilai' : 'Tambah Nilai' }}</h2>
        <form method="POST" action="{{ $editMode ? route('guru.nilai.update', $editMode->id) : route('guru.nilai.store') }}">
            @csrf
            @if($editMode)
                @method('PUT')
            @endif
@csrf
@if(isset($editMode)) @method('PUT') @endif

@php $shownOptions = []; @endphp

<div class="mb-5">
    <label for="id_regis_mapel_siswa" class="block mb-2 text-sm font-semibold text-gray-700">
        Nama Siswa - Kelas - Mapel - Tahun Ajaran (Semester)
    </label>
    <select name="id_regis_mapel_siswa" id="id_regis_mapel_siswa"
        class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
        {{ isset($editMode) ? 'disabled' : '' }} required>
        <option value="" disabled {{ old('id_regis_mapel_siswa', $editMode->id_regis_mapel_siswa ?? '') == '' ? 'selected' : '' }}>
            -- Pilih Siswa --
        </option>

        @foreach($regisList as $regis)
            @php
                $siswaNama = $regis->siswa->nama_siswa ?? 'Siswa Tidak Dikenal';
                $kelasNama = $regis->kelasMapel->kelas->nama_kelas ?? 'Kelas tidak tersedia';
                $mapelNama = $regis->kelasMapel->mapel->nama_mapel ?? 'Mapel tidak tersedia';
                $tahunAjaran = $regis->kelasMapel->tahunAjaran->tahun_ajaran ?? '-';
                $semester = $regis->kelasMapel->tahunAjaran->semester ?? '-';
                $key = $siswaNama . '_' . $kelasNama . '_' . $mapelNama . '_' . $tahunAjaran . '_' . $semester;
            @endphp
            @if(!in_array($key, $shownOptions))
                <option value="{{ $regis->id }}"
                    {{ old('id_regis_mapel_siswa', $editMode->id_regis_mapel_siswa ?? '') == $regis->id ? 'selected' : '' }}>
                    {{ $siswaNama }} - Kelas: {{ $kelasNama }} - Mapel: {{ $mapelNama }} - {{ $tahunAjaran }} (Semester {{ $semester }})
                </option>
                @php $shownOptions[] = $key; @endphp
            @endif
        @endforeach
    </select>

    @if($editMode)
        <input type="hidden" name="id_regis_mapel_siswa" value="{{ $editMode->id_regis_mapel_siswa }}">
    @endif
</div>


            <div class="mb-4">
                <label for="nilai_tugas" class="block mb-2 text-sm font-semibold text-gray-700">Nilai Tugas</label>
                <input type="number" step="0.01" min="0" max="100" name="nilai_tugas" id="nilai_tugas"
                    class="border border-gray-300 rounded-lg p-3 w-full text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                    value="{{ old('nilai_tugas', $editMode->nilai_tugas ?? '') }}">
            </div>

            <div class="mb-4">
                <label for="nilai_uts" class="block mb-2 text-sm font-semibold text-gray-700">Nilai UTS</label>
                <input type="number" step="0.01" min="0" max="100" name="nilai_uts" id="nilai_uts"
                    class="border border-gray-300 rounded-lg p-3 w-full text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                    value="{{ old('nilai_uts', $editMode->nilai_uts ?? '') }}">
            </div>

            <div class="mb-6">
                <label for="nilai_uas" class="block mb-2 text-sm font-semibold text-gray-700">Nilai UAS</label>
                <input type="number" step="0.01" min="0" max="100" name="nilai_uas" id="nilai_uas"
                    class="border border-gray-300 rounded-lg p-3 w-full text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                    value="{{ old('nilai_uas', $editMode->nilai_uas ?? '') }}">
            </div>

            <button type="submit" 
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded shadow transition">
                {{ $editMode ? 'Update Nilai' : 'Tambah Nilai' }}
            </button>
        </form>
    </div>

</div>
  


{{-- Tabel Daftar Nilai --}}
{{-- resources/views/GuruNilai.blade.php --}}



    <div class="bg-white p-6 rounded-xl shadow-md max-w-6xl mx-auto overflow-auto max-h-[600px] border border-gray-200">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Daftar Nilai Siswa</h2>
    <form id="filterForm" method="GET" action="{{ route('GuruNilai') }}"
    class="bg-emerald-50 p-4 rounded-lg mb-6 shadow-inner">

    <div class="flex flex-wrap md:flex-nowrap gap-4 mb-4">

        {{-- Filter Semester --}}
        <div class="flex flex-col w-full md:w-auto min-w-[150px]">
            <label for="filter_semester" class="font-semibold text-gray-700 mb-1">Semester</label>
            <select name="filter_semester" id="filter_semester"
                class="border border-gray-300 rounded-lg p-2 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500">
                <option value="">-- Semua Semester --</option>
                <option value="Ganjil" {{ request('filter_semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ request('filter_semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
            </select>
        </div>

        {{-- Filter Tahun Ajaran --}}
        <div class="flex flex-col w-full md:w-auto min-w-[150px]">
            <label for="filter_tahun_ajaran" class="font-semibold text-gray-700 mb-1">Tahun Ajaran</label>
            <select name="filter_tahun_ajaran" id="filter_tahun_ajaran"
                class="border border-gray-300 rounded-lg p-2 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500">
                <option value="">-- Semua Tahun Ajaran --</option>
                @foreach($tahunAjaranList as $tahun)
                    <option value="{{ $tahun }}" {{ request('filter_tahun_ajaran') == $tahun ? 'selected' : '' }}>
                        {{ $tahun }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Nama Kelas --}}
        <div class="flex flex-col w-full md:w-auto min-w-[150px]">
            <label for="nama_kelas" class="font-semibold text-gray-700 mb-1">Nama Kelas</label>
            <select name="nama_kelas" id="nama_kelas"
                class="border border-gray-300 rounded-lg p-2 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->nama_kelas }}" {{ request('nama_kelas') == $kelas->nama_kelas ? 'selected' : '' }}>
                        {{ $kelas->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Nama Mapel --}}
        <div class="flex flex-col w-full md:w-auto min-w-[150px]">
            <label for="nama_mapel" class="font-semibold text-gray-700 mb-1">Nama Mapel</label>
            <select name="nama_mapel" id="nama_mapel"
                class="border border-gray-300 rounded-lg p-2 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500">
                <option value="">-- Semua Mapel --</option>
                @foreach($mapelList as $mapel)
                    <option value="{{ $mapel->nama_mapel }}" {{ request('nama_mapel') == $mapel->nama_mapel ? 'selected' : '' }}>
                        {{ $mapel->nama_mapel }}
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

            <a href="{{ route('GuruNilai') }}"
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
    @if($nilai->isEmpty())
        <p class="text-gray-600 text-center py-12 italic">Data nilai belum tersedia.</p>
    @else
        @php
            $nilaiGrouped = $nilai->groupBy(fn($item) => $item->regisMapelSiswa->siswa->id ?? 'unknown');
        @endphp

        <table id="nilaiTable" class="w-full table-auto border-collapse text-sm rounded-xl overflow-hidden">
            <thead class="bg-emerald-100 text-emerald-800 text-left sticky top-0 z-10">
                <tr>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Nama Siswa</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Kelas</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Mata Pelajaran</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200 text-center">Tugas</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200 text-center">UTS</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200 text-center">UAS</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200 text-center">Rata-rata</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200 text-center">Tahun Ajaran</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200 text-center">Semester</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200 text-center">Aksi</th>
                </tr>
            </thead>
           <tbody>
    @foreach ($nilaiGrouped as $siswaId => $nilaiSiswa)
        @php
            $siswa = $nilaiSiswa->first()->regisMapelSiswa->siswa ?? null;
            $avgNilai = round($nilaiSiswa->avg('nilai_akhir'), 2);
            $namaSiswa = $siswa->nama_siswa ?? 'Siswa Tidak Ditemukan';
            
        @endphp

        {{-- Baris Judul per Siswa --}}
        <tr class="bg-emerald-50">
            <td colspan="10" class="px-5 py-3 font-semibold text-emerald-800">
                {{ $namaSiswa }}
                <span class="text-sm italic text-gray-500 ml-2">Rata-rata: {{ $avgNilai }}</span>
            </td>
        </tr>

        @foreach ($nilaiSiswa as $nilai)
            @php
                $regis = $nilai->regisMapelSiswa;
                $kelas = $regis->kelasMapel->kelas->nama_kelas ?? '-';
                $mapel = $regis->kelasMapel->mapel->nama_mapel ?? '-';
                $tahunAjaran = $regis->kelasMapel->tahunAjaran->tahun_ajaran ?? '-';
                $semester = $regis->kelasMapel->tahunAjaran->semester ?? '-';
            @endphp
            <tr class="hover:bg-gray-50 transition duration-200" data-nama="{{ strtolower($namaSiswa) }}">
                <td class="px-5 py-3 border-b border-gray-200"></td>
                <td class="px-5 py-3 border-b border-gray-200">{{ $kelas }}</td>
                <td class="px-5 py-3 border-b border-gray-200">{{ $mapel }}</td>
                <td class="px-5 py-3 text-center border-b border-gray-200">{{ $nilai->nilai_tugas }}</td>
                <td class="px-5 py-3 text-center border-b border-gray-200">{{ $nilai->nilai_uts }}</td>
                <td class="px-5 py-3 text-center border-b border-gray-200">{{ $nilai->nilai_uas }}</td>
                <td class="px-5 py-3 text-center border-b border-gray-200">{{ $nilai->nilai_akhir }}</td>
                <td class="px-5 py-3 text-center border-b border-gray-200">{{ $tahunAjaran }}</td>
                <td class="px-5 py-3 text-center border-b border-gray-200">{{ $semester }}</td>
                <td class="px-5 py-3 border-b border-gray-200">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('GuruNilai', ['edit' => $nilai->id]) }}"
                           class="flex items-center gap-1 px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-full shadow transition">
                            <img src="/edit.png" alt="Edit" class="w-4 h-4" /> Edit
                        </a>
                        <form id="deleteNilaiForm{{ $nilai->id }}" action="{{ route('guru.nilai.destroy', $nilai->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    onclick="confirmDeleteNilai('{{ $nilai->id }}')"
                                    class="flex items-center gap-1 px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded-full shadow transition">
                                <img src="/bin.png" alt="Delete" class="w-4 h-4" /> Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    @endforeach
</tbody>

        </table>
    @endif
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
    // Konfirmasi hapus nilai
    function confirmDeleteNilai(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data nilai yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteNilaiForm' + id).submit();
            }
        });
    }
     function filterTable() {
    const input = document.getElementById("search");
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll("#nilaiTable tbody tr");

    let showGroup = false;

    rows.forEach((row) => {
        const isGroupHeader = row.querySelector("td[colspan='10']") !== null;

        if (isGroupHeader) {
            // Deteksi nama siswa dari teks dalam baris judul
            const namaText = row.textContent.toLowerCase();
            showGroup = namaText.includes(filter);
            row.style.display = showGroup ? "" : "none";
        } else {
            // Hanya tampilkan baris nilai jika grup header sebelumnya cocok
            row.style.display = showGroup ? "" : "none";
        }
    });
}
    
    $(document).ready(function() {
        $('.select2').select2({
            dropdownCssClass: 'select2-dropdown-tailwind',
            width: 'resolve'
        });
    });
    $(document).ready(function() {
        $('#id_regis_mapel_siswa').select2({
            placeholder: "-- Pilih Siswa --",
            width: '100%'
        });
    });
</script>
@endsection