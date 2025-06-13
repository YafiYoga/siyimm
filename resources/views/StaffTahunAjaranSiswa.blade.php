@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 p-6 mt-20 font-[Verdana]" x-data="{ showForm: {{ $editTahun ? 'true' : 'false' }} }">
    <div class="flex items-center gap-2 mb-5 justify-between">
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Tahun Ajaran</h2>
            <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
        </div>
        <button
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow transition-all text-sm"
            @click="showForm = !showForm">
            <span x-text="showForm ? 'Tutup Form' : 'Tambah Tahun Ajaran'"></span>
        </button>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-600 text-white border border-green-700 rounded-md shadow-md flex items-center gap-2">
            <img src="/check.png" alt="Success" class="w-5 h-5">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form Tambah/Edit --}}
    <div class="bg-white shadow-lg border border-gray-200 rounded-md p-6 max-w-3xl mx-auto mb-10 text-sm"
         x-show="showForm" x-transition>
        @php
            $userJenjang = auth()->user()->role == 'staff_sd' ? 'SD ISLAM TERPADU INSAN MADANI' : (auth()->user()->role == 'staff_smp' ? 'SMP IT TAHFIDZUL QURAN INSAN MADANI' : null);
            $jenjangOptions = [
                'SD ISLAM TERPADU INSAN MADANI',
                'SMP IT TAHFIDZUL QURAN INSAN MADANI',
            ];
        @endphp

        <form action="{{ $editTahun ? route('tahun-ajaran.update', $editTahun->id) : route('tahun-ajaran.store') }}" method="POST" class="mb-6">
            @csrf
            @if($editTahun) @method('PUT') @endif

            <div class="mb-4">
                <label for="tahun_ajaran" class="block mb-1 font-semibold text-gray-700">Tahun Ajaran</label>
                <input type="text" id="tahun_ajaran" name="tahun_ajaran" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
                    value="{{ old('tahun_ajaran', $editTahun->tahun_ajaran ?? '') }}">
                @error('tahun_ajaran')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="semester" class="block mb-1 font-semibold text-gray-700">Semester</label>
                <select id="semester" name="semester" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600">
                    <option value="Ganjil" {{ old('semester', $editTahun->semester ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="Genap" {{ old('semester', $editTahun->semester ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                </select>
                @error('semester')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jenjang" class="block mb-1 font-semibold text-gray-700">Jenjang</label>
                @if($userJenjang)
                    <select disabled
                        class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100 cursor-not-allowed">
                        <option>{{ $userJenjang }}</option>
                    </select>
                    <input type="hidden" name="jenjang" value="{{ $userJenjang }}">
                @else
                    <select name="jenjang" id="jenjang" required
                        class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600">
                        <option value="">-- Pilih Jenjang --</option>
                        @foreach($jenjangOptions as $option)
                            <option value="{{ $option }}" {{ old('jenjang', $editTahun->jenjang ?? '') == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                @endif
                @error('jenjang')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="aktif_saat_ini" value="1"
                        class="form-checkbox text-green-600"
                        {{ old('aktif_saat_ini', $editTahun->aktif_saat_ini ?? false) ? 'checked' : '' }}>
                    <span class="ml-2 select-none text-gray-700">Aktif Saat Ini</span>
                </label>
            </div>

            <div class="flex justify-center gap-3 mt-6">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium transition-all shadow-md">
                    {{ $editTahun ? 'Update' : 'Simpan' }}
                </button>
                @if($editTahun)
                    <a href="{{ route('StaffTahunAjaranSiswa') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-md font-medium transition-all shadow-md">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('StaffTahunAjaranSiswa') }}" class="mb-6 max-w-3xl mx-auto">
        <input type="text" name="search" placeholder="Cari Tahun Ajaran..." value="{{ request('search') }}"
            class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600">
    </form>

    {{-- List Tahun Ajaran --}}
    <div class="bg-white shadow-lg border border-gray-200 rounded-md p-6 max-w-5xl mx-auto text-sm">
        <h2 class="text-xl font-bold text-green-800 mb-4">Daftar Tahun Ajaran</h2>

        @if($tahunList->isEmpty())
            <p class="text-gray-600">Belum ada data tahun ajaran.</p>
        @else
            <div class="space-y-4">
                @foreach($tahunList as $tahun)
                    <div class="border border-gray-300 rounded-md p-4 bg-gray-50 hover:bg-green-50 transition-all duration-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-lg font-semibold text-gray-800">{{ $tahun->tahun_ajaran }}</span>
                                <span class="ml-4 text-gray-600">Semester: {{ $tahun->semester }}</span>
                                <span class="ml-4 text-gray-600 italic">{{ $tahun->jenjang }}</span>
                                <span class="ml-4 font-semibold {{ $tahun->aktif_saat_ini ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $tahun->aktif_saat_ini ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('StaffTahunAjaranSiswa', ['edit' => $tahun->id]) }}"
                                    class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-blue-700 hover:text-white transition duration-200 rounded shadow"
                                    title="Edit Tahun Ajaran">
                                    <img src="/edit.png" alt="Edit" class="w-4 h-4"> Edit
                                </a>
                                <form id="deleteForm{{ $tahun->id }}" action="{{ route('tahun-ajaran.destroy', $tahun->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="confirmDelete({{ $tahun->id }})"
                                        class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-red-700 hover:text-white transition duration-200 rounded shadow"
                                        title="Hapus Tahun Ajaran">
                                        <img src="/bin.png" alt="Delete" class="w-4 h-4"> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- SweetAlert Delete --}}
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data tahun ajaran yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }
</script>
@endsection
