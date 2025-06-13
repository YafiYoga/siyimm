@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 p-6 mt-20 pb-10 font-[Verdana]">
    <div class="flex items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">{{ $editKelasMapel ? 'Edit Kelas Mapel' : 'Tambah Kelas Mapel' }}</h2>
        <img src="/SIYIMM.png" class="h-10 ml-2" alt="Logo SYIMM">
    </div>

    {{-- Alert Section --}}
    @foreach (['success' => 'green', 'error' => 'red'] as $type => $color)
        @if(session($type))
            <div class="mb-6 p-4 bg-{{ $color }}-600 text-white border border-{{ $color }}-700 rounded-md shadow-md flex items-center gap-2">
                <img src="/{{ $type == 'success' ? 'check' : 'error' }}.png" alt="{{ ucfirst($type) }}" class="w-5 h-5">
                <span>{{ session($type) }}</span>
            </div>
        @endif
    @endforeach

    {{-- Form Tambah / Edit --}}
    <div class="bg-white shadow-md border border-gray-200 rounded-md p-6 max-w-3xl mx-auto text-sm mb-10">
        <form action="{{ $editKelasMapel ? route('kelas-mapel.update', $editKelasMapel->id) : route('kelas-mapel.store') }}" method="POST">
            @csrf
            @if($editKelasMapel) @method('PUT') @endif

            {{-- Tahun Ajaran --}}
            <div class="mb-4">
                <label for="id_tahun_ajaran" class="block mb-1 font-semibold text-gray-700">Tahun Ajaran</label>
                <select name="id_tahun_ajaran" id="id_tahun_ajaran" required class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-green-600">
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    @foreach($tahunAjaranList as $tahun)
                        <option value="{{ $tahun->id }}" {{ old('id_tahun_ajaran', $editKelasMapel->id_tahun_ajaran ?? '') == $tahun->id ? 'selected' : '' }}>
                            {{ $tahun->tahun_ajaran }} - {{ $tahun->semester }}
                        </option>
                    @endforeach
                </select>
                @error('id_tahun_ajaran') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kelas --}}
            <div class="mb-4">
                <label for="id_kelas" class="block mb-1 font-semibold text-gray-700">Kelas</label>
                <select name="id_kelas" id="id_kelas" required class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-green-600">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ old('id_kelas', $editKelasMapel->id_kelas ?? '') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas ?? $kelas->kelas }}
                        </option>
                    @endforeach
                </select>
                @error('id_kelas') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Mapel --}}
            <div class="mb-4">
                <label for="id_mapel" class="block mb-1 font-semibold text-gray-700">Mata Pelajaran</label>
                <select name="id_mapel" id="id_mapel" required class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-green-600">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel->id }}" {{ old('id_mapel', $editKelasMapel->id_mapel ?? '') == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel ?? $mapel->mapel }}
                        </option>
                    @endforeach
                </select>
                @error('id_mapel') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Guru --}}
            <div class="mb-4">
                <label for="id_guru" class="block mb-1 font-semibold text-gray-700">Guru</label>
                <select name="id_guru" id="id_guru" required class="w-full border border-gray-300 px-4 py-2 rounded-md focus:ring-2 focus:ring-green-600">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guruList as $guru)
                        <option value="{{ $guru->id }}" {{ old('id_guru', $editKelasMapel->id_guru ?? '') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
                @error('id_guru') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-center gap-3 mt-6">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium transition shadow-md">
                    {{ $editKelasMapel ? 'Update' : 'Simpan' }}
                </button>
                @if($editKelasMapel)
                    <a href="{{ route('StaffKelasMapelSiswa') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-md font-medium transition shadow-md">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Form Search --}}
    <form method="GET" action="{{ route('StaffKelasMapelSiswa') }}" class="mb-6 max-w-3xl mx-auto">
        <input
            type="text"
            name="search"
            placeholder="Cari Kelas..."
            value="{{ request('search') }}"
            class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
        >
    </form>

    
    {{-- Daftar Kelas Mapel --}}
<div class="bg-white shadow-md border border-gray-200 rounded-md p-6 max-w-6xl mx-auto text-sm">
    <h3 class="font-semibold text-gray-700 mb-5">Daftar Kelas Mapel</h3>

    @if($kelasMapel->isEmpty())
        <p class="text-gray-500">Belum ada data Kelas Mapel.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Tahun Ajaran</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Kelas</th>
                        <th class="px-4 py-2 text-left text-gray-700 font-semibold">Mapel & Guru</th>
                        <th class="px-4 py-2 text-center text-gray-700 font-semibold w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php
                        $grouped = $kelasMapel->groupBy(fn($item) => $item->id_tahun_ajaran . '-' . $item->id_kelas);
                    @endphp

                    @foreach($grouped as $group)
                        @php $first = $group->first(); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 align-top">
                                {{ $first->tahunAjaran->tahun_ajaran ?? '-' }} ({{ $first->tahunAjaran->semester ?? '-' }})
                            </td>
                            <td class="px-4 py-3 align-top">
                                {{ $first->kelas->nama_kelas ?? $first->kelas->kelas ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($group as $km)
                                        <li>{{ $km->mapel->nama_mapel ?? '-' }} - {{ $km->guru->nama_lengkap ?? '-' }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-4 py-3 text-center align-top">
                                <div class="flex flex-col gap-2 items-center justify-center">
                                    @foreach($group as $km)
                                        <div class="flex gap-2">
                                        <a href="{{ route('StaffKelasMapelSiswa', ['edit' => $km->id]) }}"
                                        class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-blue-200 rounded-md">
                                            <img src="/edit.png" alt="Edit" class="w-4 h-4">
                                            Edit
                                        </a>

                                        <form id="deleteForm{{ $km->id }}" action="{{ route('kelas-mapel.destroy', $km->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $km->id }})"
                                                class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-red-700 hover:text-white transition duration-200 rounded shadow"
                                                title="Hapus Data">
                                                <img src="/bin.png" alt="Delete" class="w-4 h-4"> Hapus
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
        </div>

        <div class="mt-4">{{ $kelasMapel->links() }}</div>
    @endif
</div>

</div>
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
@endsection
