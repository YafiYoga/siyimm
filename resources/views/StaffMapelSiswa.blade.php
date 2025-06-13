@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 p-6 mt-20 font-[Verdana]">

    {{-- Header --}}
    <div class="flex items-center gap-2 mb-5">
        <h2 class="text-2xl font-bold text-gray-800">{{ $editMapel ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-600 text-white border border-green-700 rounded-md shadow-md flex items-center gap-2">
            <img src="/check.png" alt="Success" class="w-5 h-5">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-600 text-white border border-red-700 rounded-md shadow-md flex items-center gap-2">
            <img src="/error.png" alt="Error" class="w-5 h-5">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Tombol Tampilkan Form --}}
    @if(!$editMapel)
    <button id="toggleFormBtn"
        class="mb-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium shadow-md">
        Tambah Mapel
    </button>
    @endif

    {{-- Form Tambah/Edit --}}
    <div id="mapelFormContainer" class="{{ $editMapel ? '' : 'hidden' }} bg-white shadow-lg border border-gray-200 rounded-md p-6 max-w-3xl mx-auto mb-10 text-sm">
        <form action="{{ $editMapel ? route('mapel.update', $editMapel->id) : route('mapel.store') }}" method="POST">
            @csrf
            @if($editMapel)
                @method('PUT')
            @endif

            <div class="mb-4">
                <label for="nama_mapel" class="block mb-1 font-semibold text-gray-700">Nama Mata Pelajaran</label>
                <input type="text" id="nama_mapel" name="nama_mapel" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
                    value="{{ old('nama_mapel', $editMapel->nama_mapel ?? '') }}">
                @error('nama_mapel')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-700">Jenjang</label>
                @php
                    $jenjang = auth()->user()->role === 'staff_sd' ? 'SD ISLAM TERPADU INSAN MADANI' :
                              (auth()->user()->role === 'staff_smp' ? 'SMP IT TAHFIDZUL QURAN INSAN MADANI' : '-');
                @endphp
                <input type="text" value="{{ $jenjang }}" disabled
                    class="w-full border border-gray-300 px-4 py-2 rounded-md bg-gray-100 cursor-not-allowed">
            </div>

            <div class="flex justify-center gap-3 mt-6">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium transition-all shadow-md">
                    {{ $editMapel ? 'Update' : 'Simpan' }}
                </button>
                @if($editMapel)
                    <a href="{{ route('StaffMapelSIswa') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-md font-medium transition-all shadow-md">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Pencarian --}}
    <div class="max-w-5xl mx-auto mb-4">
        <form method="GET" action="{{ route('StaffMapelSIswa') }}">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari mapel..."
                class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600" />
        </form>
    </div>

    {{-- Daftar Mapel --}}
    <div class="bg-white shadow-lg border border-gray-200 rounded-md p-6 max-w-5xl mx-auto text-sm">
        <h2 class="text-xl font-bold text-green-800 mb-4">Daftar Mata Pelajaran</h2>

        @if($mapelList->isEmpty())
            <p class="text-gray-600">Belum ada data mata pelajaran.</p>
        @else
            <div class="space-y-4">
                @foreach($mapelList as $mapel)
                    <div class="border border-gray-300 rounded-md p-4 bg-gray-50 hover:bg-green-50 transition-all">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-700">{{ $mapelList->firstItem() + $loop->index }}.</span>
                                <span class="text-lg font-semibold text-gray-800">{{ $mapel->nama_mapel }}</span>
                            </div>

                            {{-- Aksi --}}
                            <div class="flex gap-2">
                                <a href="{{ route('StaffMapelSIswa', ['edit' => $mapel->id]) }}"
                                    class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-blue-200 rounded-md">
                                    <img src="/edit.png" alt="Edit" class="w-4 h-4">
                                    Edit
                                </a>

                                <form id="deleteForm{{ $mapel->id }}" action="{{ route('mapel.destroy', $mapel->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        onclick="confirmDelete({{ $mapel->id }})"
                                        class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-red-700 hover:text-white transition duration-200 rounded shadow"
                                        title="Hapus Mapel">
                                        <img src="/bin.png" alt="Delete" class="w-4 h-4"> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="text-gray-600 text-sm mt-1">Jenjang: {{ $mapel->jenjang }}</div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $mapelList->links() }}
            </div>
        @endif
    </div>

</div>

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

    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleFormBtn');
        const formContainer = document.getElementById('mapelFormContainer');

        if (toggleBtn && formContainer) {
            toggleBtn.addEventListener('click', function () {
                formContainer.classList.toggle('hidden');
                if (!formContainer.classList.contains('hidden')) {
                    formContainer.scrollIntoView({ behavior: 'smooth' });
                }
            });
        }
    });
</script>

@endsection
