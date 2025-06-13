@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 p-6 mt-20 font-[Verdana]" x-data="{ showForm: {{ $editSurat ? 'true' : 'false' }} }">
    <div class="flex items-center gap-2 mb-5 justify-between">
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-gray-800">Master Surat</h2>
            <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
        </div>
        @if(!$editSurat)
        <button @click="showForm = !showForm"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md shadow-md text-sm">
            Tambah Master Surat
        </button>
        @endif
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-600 text-white border border-green-700 rounded-md shadow-md flex items-center gap-2">
        <img src="/check.png" alt="Success" class="w-5 h-5">
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-600 text-white border border-red-700 rounded-md shadow-md flex items-center gap-2">
        <img src="/error.png" alt="Error" class="w-5 h-5">
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Form Tambah/Edit --}}
    <div x-show="showForm" x-transition class="bg-white shadow-lg border border-gray-200 rounded-md p-6 max-w-3xl mx-auto mb-10 text-sm">
        <form action="{{ $editSurat ? route('StaffMasterSuratSiswa', ['edit' => $editSurat->id]) : route('StaffMasterSuratSiswa') }}" method="POST" class="mb-6">
            @csrf
            @if($editSurat)
                @method('PUT')
            @endif

            <div class="mb-4">
                <label for="nama_surat" class="block mb-1 font-semibold text-gray-700">Nama Surat</label>
                <input type="text" id="nama_surat" name="nama_surat" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
                    value="{{ old('nama_surat', $editSurat->nama_surat ?? '') }}">
                @error('nama_surat')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="jumlah_ayat" class="block mb-1 font-semibold text-gray-700">Jumlah Ayat</label>
                <input type="number" id="jumlah_ayat" name="jumlah_ayat" min="1" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
                    value="{{ old('jumlah_ayat', $editSurat->jumlah_ayat ?? '') }}">
                @error('jumlah_ayat')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-center gap-3 mt-6">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium transition-all shadow-md">
                    {{ $editSurat ? 'Update' : 'Simpan' }}
                </button>
                @if($editSurat)
                    <a href="{{ route('StaffMasterSuratSiswa') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-md font-medium transition-all shadow-md">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Form Search --}}
    <form method="GET" action="{{ route('StaffMasterSuratSiswa') }}" class="mb-6 max-w-3xl mx-auto">
        <input
            type="text"
            name="search"
            placeholder="Cari nama surat..."
            value="{{ request('search') }}"
            class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
        >
    </form>

    {{-- Daftar Surat --}}
    <div class="bg-white shadow-lg border border-gray-200 rounded-md p-6 max-w-5xl mx-auto text-sm">
        <h2 class="text-xl font-bold text-green-800 mb-4">Daftar Master Surat</h2>

        @if($suratList->isEmpty())
            <p class="text-gray-600">Belum ada data surat.</p>
        @else
            <div class="space-y-4">
                @foreach($suratList as $surat)
                    <div class="border border-gray-300 rounded-md p-4 relative bg-gray-50 hover:bg-green-50 transition-all duration-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-lg font-semibold text-gray-800">{{ $surat->nama_surat }}</span>
                                <span class="ml-4 text-gray-600">Jumlah Ayat: {{ $surat->jumlah_ayat }}</span>
                            </div>

                            {{-- Aksi --}}
                            <div class="flex gap-2">
                                <a href="{{ route('StaffMasterSuratSiswa', ['edit' => $surat->id]) }}"
                                    class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-blue-700 rounded shadow">
                                    <img src="/edit.png" alt="Edit" class="w-4 h-4"> Edit
                                </a>

                                <form id="deleteForm{{ $surat->id }}" action="{{ route('master-surat.destroy', $surat->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="confirmDelete({{ $surat->id }})"
                                        class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-red-700 hover:text-white transition duration-200 rounded shadow">
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

{{-- SweetAlert for delete --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
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

{{-- Alpine.js for toggle --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
