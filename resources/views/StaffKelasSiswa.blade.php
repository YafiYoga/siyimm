@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 mt-20 p-6 font-[Verdana]">
    <!-- Alert -->
    @foreach (['success' => 'green', 'error' => 'red'] as $type => $color)
        @if(session($type))
            <div class="mb-4 p-4 bg-{{ $color }}-600 text-white rounded-md shadow-md flex items-center gap-2">
                <img src="/{{ $type == 'success' ? 'check' : 'error' }}.png" alt="{{ ucfirst($type) }}" class="w-5 h-5">
                <span>{{ session($type) }}</span>
            </div>
        @endif
    @endforeach

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Kelas</h2>
            <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
        </div>

        @if(!isset($editKelas))
        <button onclick="toggleForm()"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-semibold shadow" id="toggleBtn">
            + Tambah Kelas Baru
        </button>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Daftar Kelas -->
        <div class="bg-white border border-gray-200 rounded-md shadow p-0 text-sm max-h-[600px] overflow-y-auto col-span-2 xl:col-span-{{ isset($editKelas) ? '1' : '2' }}" id="kelasListContainer">
            <div class="sticky top-0 z-10 bg-white  p-6 flex justify-between items-center">
                <h2 class="text-xl font-bold text-green-800">Daftar Kelas</h2>
            </div>

            <div class="p-6 space-y-4">
                @php
                    $groupedKelas = $kelasList->groupBy(fn($kelas) => preg_replace('/[^0-9]/', '', $kelas->nama_kelas));
                @endphp

                @forelse($groupedKelas as $tingkat => $kelasGroup)
                    <div class="bg-gray-50 border border-gray-300 rounded-md p-4 hover:bg-green-50 transition">
                        <div class="mb-2 font-semibold text-gray-700">Kelas {{ $tingkat }}</div>
                        <div class="flex flex-wrap gap-3">
                            @foreach($kelasGroup as $kelas)
                                <div class="bg-white border border-gray-300 rounded shadow px-3 py-1 flex items-center gap-2">
                                    <span class="font-medium text-gray-800">{{ $kelas->nama_kelas }}</span>
                                    <div class="flex gap-2">
                                        <a href="{{ route('StaffKelasSiswa', ['edit' => $kelas->id]) }}"
                                            class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-blue-700 hover:text-white transition rounded shadow">
                                            <img src="/edit.png" alt="Edit" class="w-4 h-4"> Edit
                                        </a>
                                        <form id="deleteForm{{ $kelas->id }}" action="{{ route('kelas.destroy', $kelas->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $kelas->id }})"
                                                class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-red-700 hover:text-white transition rounded shadow">
                                                <img src="/bin.png" alt="Delete" class="w-4 h-4"> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">Belum ada data kelas.</p>
                @endforelse
            </div>
        </div>

        <!-- Form Tambah/Edit -->
        <div id="kelasForm"
            class="bg-white border border-gray-200 rounded-md p-6 shadow-sm text-sm {{ isset($editKelas) ? '' : 'hidden' }}">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                {{ isset($editKelas) ? 'Edit Kelas' : 'Tambah Kelas Baru' }}
            </h3>

            <form action="{{ isset($editKelas) ? route('kelas.update', $editKelas->id) : route('kelas.store') }}" method="POST">
                @csrf
                @if(isset($editKelas)) @method('PUT') @endif

                <div class="mb-4">
                    <label for="nama_kelas" class="block mb-1 font-semibold text-gray-700">Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="nama_kelas"
                        class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring focus:ring-green-500"
                        value="{{ old('nama_kelas', $editKelas->nama_kelas ?? '') }}" required>
                    @error('nama_kelas')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium shadow">
                        {{ isset($editKelas) ? 'Update' : 'Simpan' }}
                    </button>
                    @if(isset($editKelas))
                        <a href="{{ route('StaffKelasSiswa') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-md font-medium shadow">
                            Batal
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data kelas yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }

    function toggleForm() {
        const form = document.getElementById('kelasForm');
        form.classList.toggle('hidden');

        // Ubah lebar daftar kelas
        const list = document.getElementById('kelasListContainer');
        if (form.classList.contains('hidden')) {
            list.classList.remove('xl:col-span-1');
            list.classList.add('xl:col-span-2');
            document.getElementById('toggleBtn').innerText = '+ Tambah Kelas Baru';
        } else {
            list.classList.remove('xl:col-span-2');
            list.classList.add('xl:col-span-1');
            document.getElementById('toggleBtn').innerText = 'Tutup Form';
        }
    }
</script>
@endsection
