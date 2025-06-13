@extends('layouts.MainGuru')

@section('content')
<div class="p-6 font-[Verdana] max-w-full max-h-screen overflow-auto bg-gray-50 min-h-screen">

    <div class="flex items-center gap-2 mb-5">
        <h1 class="text-3xl font-extrabold text-emerald-700 tracking-wide">Manajemen Absensi Siswa</h1>
        <img src="/SIYIMM.png" alt="SYIMM Logo" class="h-14 w-auto">
    </div>
      <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (wajib untuk Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- Alert Messages --}}
    @if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 text-red-800 rounded-lg border border-red-300 shadow-sm">
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach ($errors->all() as $error)
            <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-600 text-white border border-emerald-700 rounded-md shadow-md flex items-center gap-3">
        <img src="/check.png" alt="Success" class="w-6 h-6">
        <span class="font-semibold">{{ session('success') }}</span>
    </div>
    @endif

    {{-- resources/views/guru/absensi/form.blade.php --}}
<div class="bg-white shadow-lg rounded-md p-6 max-w-4xl mx-auto mb-12">
    <form action="{{ isset($editMode) ? route('guru.absensi.update', $editMode->id) : route('guru.absensi.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @csrf
        @if(isset($editMode)) @method('PUT') @endif

        {{-- Pilih Kelas --}}
        <div>
            <label for="kelas_id" class="block mb-2 text-sm font-semibold text-gray-700">Pilih Kelas</label>
            <select id="kelas_id" name="kelas_id" class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500" required>
                <option value="" disabled selected>-- Pilih Kelas --</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}"
                        {{ (old('kelas_id', $editMode?->regisMapelSiswa?->kelasMapel?->kelas->id ?? '') == $kelas->id) ? 'selected' : '' }}>
                        {{ $kelas->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Pilih Siswa + Mapel --}}
        <div>
            <label for="id_regis_mapel_siswa" class="block mb-2 text-sm font-semibold text-gray-700">Nama Siswa & Mapel</label>
            <select id="id_regis_mapel_siswa" name="id_regis_mapel_siswa" class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500" required disabled>
                <option value="" disabled selected>-- Pilih Kelas Terlebih Dahulu --</option>
            </select>
        </div>

        {{-- Tanggal --}}
        <div>
            <label for="tanggal" class="block mb-2 text-sm font-semibold text-gray-700">Tanggal</label>
            <input
                id="tanggal"
                type="date"
                name="tanggal"
                value="{{ old('tanggal', $editMode->tanggal ?? '') }}"
                class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500"
                required>
        </div>

        {{-- Status --}}
        <div>
            <label for="status" class="block mb-2 text-sm font-semibold text-gray-700">Status</label>
            <select id="status" name="status" class="w-full border border-gray-300 rounded-lg p-3 text-sm shadow-sm focus:ring-emerald-400 focus:border-emerald-500" required>
                @foreach(['hadir', 'sakit', 'izin', 'alpha'] as $status)
                    <option value="{{ $status }}" {{ old('status', $editMode->status ?? '') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Submit Button --}}
        <div class="md:col-span-2 flex gap-4 justify-start mt-4">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-2 rounded-lg text-sm shadow transition duration-300">
                {{ isset($editMode) ? 'Update' : 'Tambah' }}
            </button>

            @if(isset($editMode))
                <a href="{{ route('GuruAbsensi') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm shadow transition duration-300">
                    Batal
                </a>
            @endif
        </div>
    </form>
</div>






    {{-- Tabel Data Absensi --}}
   <div class="bg-white p-6 rounded-xl shadow-md max-w-6xl mx-auto overflow-auto max-h-[600px] border border-gray-200">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Daftar Absensi Siswa</h2>

    {{-- Filter & Search --}}
<div class="max-w-6xl mx-auto mb-6 bg-emerald-50 p-4 rounded-lg shadow-inner">
    <form id="filterForm" method="GET" action="{{ route('GuruAbsensi') }}">
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
                        <option value="{{ $kelas->nama_kelas }}" {{ (request('nama_kelas') == $kelas->nama_kelas) ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
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

                <a href="{{ route('GuruAbsensi') }}"
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
</div>

    @if($absensiGroupedBySiswa->isEmpty())
        <p class="text-gray-600 text-center py-12 italic">Belum ada data absensi siswa untuk bulan dan tahun terpilih.</p>
    @else
        <table id="absensiTable" class="w-full table-auto border-collapse text-sm rounded-xl overflow-hidden">
            <thead class="bg-emerald-100 text-emerald-800 text-left sticky top-0 z-10">
                <tr>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Nama Siswa</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Kelas</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Mapel</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Tanggal Absensi</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200">Status</th>
                    <th class="px-5 py-3 font-semibold border-b border-emerald-200 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensiGroupedBySiswa as $id_siswa => $absensiList)
                   @php
                   
                    $firstAbsensi = $absensiList->first();

                    $namaSiswa = optional($firstAbsensi?->regisMapelSiswa?->siswa)->nama_siswa ?? '-';
                    $namaKelas = optional($firstAbsensi?->regisMapelSiswa?->kelasMapel?->kelas)->nama_kelas ?? '-';
                    $namaMapel = optional($firstAbsensi?->regisMapelSiswa?->kelasMapel?->mapel)->nama_mapel ?? '-';
                    $tanggalArr = $absensiList->map(fn($a) => \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y'))->toArray();
                    $statusArr = $absensiList->map(fn($a) => ucfirst($a->status))->toArray();
                   
                @endphp

                    <tr class="hover:bg-emerald-50 transition duration-200" data-nama="{{ strtolower($namaSiswa) }}">
                        <td class="px-5 py-3 align-top border-b border-gray-200">{{ $namaSiswa }}</td>
                        <td class="px-5 py-3 align-top border-b border-gray-200">{{ $namaKelas }}</td>
                         <td class="px-5 py-3 align-top border-b border-gray-200">{{ $namaMapel }}</td>
                        <td class="px-5 py-3 align-top border-b border-gray-200">
                            <ul class="list-disc list-inside space-y-1 text-gray-700">
                                @foreach($tanggalArr as $tgl)
                                    <li>{{ $tgl }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-5 py-3 align-top border-b border-gray-200">
                            <ul class="list-disc list-inside space-y-1 capitalize text-gray-700">
                                @foreach($statusArr as $st)
                                    <li>{{ $st }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-5 py-3 align-top border-b border-gray-200">
                            <div class="flex flex-col gap-3">
                                @foreach($absensiList as $absensi)
                                    <div class="flex gap-3">
                                        <a href="{{ route('GuruAbsensi', ['edit' => $absensi->id]) }}"
                                           class="flex items-center gap-1 px-3 py-1 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-full shadow transition"
                                           title="Edit Data">
                                            <img src="/edit.png" alt="Edit" class="w-4 h-4" /> Edit
                                        </a>
                                        <form id="deleteAbsensiForm{{ $absensi->id }}" action="{{ route('guru.absensi.destroy', $absensi->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDeleteAbsensi('{{ $absensi->id }}')"
                                                    class="flex items-center gap-1 px-3 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded-full shadow transition"
                                                    title="Hapus Data">
                                                <img src="/bin.png" alt="Delete" class="w-4 h-4" /> Hapus
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


    {{-- Script untuk filter pencarian --}}
    <script>
    function filterTable() {
        const input = document.getElementById('search');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('absensiTable');
        const trs = table.getElementsByTagName('tr');

        // Skip the first tr (header)
        for (let i = 1; i < trs.length; i++) {
            const tr = trs[i];
            const nama = tr.getAttribute('data-nama');
            if (nama && nama.includes(filter)) {
                tr.style.display = '';
            } else {
                tr.style.display = 'none';
            }
        }
    }
   $(document).ready(function () {
    const select = $('#id_regis_mapel_siswa');

    select.select2({
        placeholder: "-- Pilih Siswa & Mapel --",
        allowClear: true,
        width: '100%',
        dropdownAutoWidth: true,
    });

    // Tambahkan class Tailwind ke elemen Select2 yang telah dirender
    select.on('select2:open', function () {
        $('.select2-dropdown').addClass('rounded-md shadow-md border border-gray-300');
        $('.select2-results__option').addClass('hover:bg-emerald-100 text-sm px-3 py-2');
    });

    // Styling input box (search box & selected)
    $('.select2-selection').addClass('rounded-md border-gray-300 shadow-sm focus:ring focus:ring-emerald-200 text-sm');
    $('.select2-selection__rendered').addClass('pl-2 py-1');
    $('.select2-selection__arrow').addClass('mt-1');
});


    function confirmDeleteAbsensi(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data absensi yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteAbsensiForm' + id).submit();
            }
        });
    }

    $(document).ready(function(){
    function loadSiswa(kelasId, selectedRegisId = null) {
        if(!kelasId) {
            $('#id_regis_mapel_siswa').html('<option value="" disabled selected>-- Pilih Kelas Terlebih Dahulu --</option>');
            $('#id_regis_mapel_siswa').prop('disabled', true);
            return;
        }

        $('#id_regis_mapel_siswa').prop('disabled', true).html('<option>Loading...</option>');

        $.ajax({
            url: '/guru/absensi/get-siswa-by-kelas/' + kelasId,
            type: 'GET',
            success: function(data) {
                let options = '<option value="" disabled selected>-- Pilih Siswa --</option>';
                data.forEach(function(item) {
                    const selected = (selectedRegisId && selectedRegisId == item.id_regis_mapel_siswa) ? 'selected' : '';
                    options += `<option value="${item.id_regis_mapel_siswa}" ${selected}>${item.nama_siswa} - Mapel: ${item.nama_mapel}</option>`;
                });
                $('#id_regis_mapel_siswa').html(options).prop('disabled', false);
            },
            error: function() {
                $('#id_regis_mapel_siswa').html('<option value="" disabled>Error loading data</option>');
            }
        });
    }

    // Load siswa saat halaman edit (jika ada editMode)
    @if(isset($editMode))
        const kelasId = '{{ $editMode->regisMapelSiswa->kelasMapel->kelas->id ?? '' }}';
        const selectedRegis = '{{ $editMode->id_regis_mapel_siswa }}';
        loadSiswa(kelasId, selectedRegis);
    @endif

    // Event saat pilih kelas
    $('#kelas_id').on('change', function() {
        const kelasId = $(this).val();
        loadSiswa(kelasId);
    });
});
    </script>

</div>
@endsection
