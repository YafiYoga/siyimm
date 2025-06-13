@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 p-6 mt-20 font-[Verdana] min-h-screen bg-gray-100">

    {{-- ======================== Header Page ======================== --}}
    <div class="flex items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">{{ isset($editRegis) ? 'Edit Registrasi Matapelajaran siswa' : 'Registrasi Matapelajaran siswa' }}</h2>
        <img src="/SIYIMM.png" class="h-10 ml-2" alt="SYIMM Logo">
    </div>


    {{-- ======================== Alert Section ======================== --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-green-100 text-green-800 px-4 py-3 border border-green-300 rounded-lg shadow-sm">
            <img src="/check.png" class="w-5 h-5" alt="Success">
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 bg-red-100 text-red-700 px-4 py-3 border border-red-300 rounded-lg shadow-sm">
            <img src="/error.png" class="w-5 h-5" alt="Error">
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ======================== Form Section ======================== --}}
    <div class="bg-white shadow-md rounded-xl p-8 max-w-5xl mx-auto mb-12 border border-gray-200">
        <form 
            action="{{ $editRegis ? route('regis-mapel.update', $editRegis->id) : route('regis-mapel.store') }}" 
            method="POST" 
            class="space-y-6"
            x-data="{ searchKelasMapel: '', searchSiswa: '' }">
            @csrf
            @if($editRegis)
                @method('PUT')
            @endif

            @php
    $kelasMapelFormatted = $kelasMapelList->map(fn($km) => [
        'id' => $km->id,
        'kelas' => $km->kelas->nama_kelas ?? '-',
        'mapel' => $km->mapel->nama_mapel ?? '-',
        'guru' => $km->guru->nama_lengkap ?? '-',
        'label' => ($km->kelas->nama_kelas ?? '-') . ' - ' . ($km->mapel->nama_mapel ?? '-') . ' (' . ($km->guru->nama_lengkap ?? '-') . ')'
    ]);
@endphp

<div 
    x-data="{
        searchKelasMapel: '',
        kelasMapelList: {{ Js::from($kelasMapelFormatted) }},
        selected: {{ Js::from(old('id_kelas_mapel', $editRegis ? [$editRegis->id_kelas_mapel] : [])) }}
    }" 
    class="mb-6"
>
    <label for="id_kelas_mapel" class="block mb-2 text-sm font-semibold text-gray-700">
        Pilih Kelas & Mapel <span class="text-red-500">*</span>
    </label>

    <!-- Pencarian -->
    <input 
        type="text" 
        x-model="searchKelasMapel" 
        placeholder="Cari kelas / mapel / guru..."
        class="mb-3 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
    >

    <!-- Dropdown Scrollable -->
    <div class="border rounded-md h-64 overflow-y-auto bg-white">
        <select 
            name="id_kelas_mapel[]" 
            id="id_kelas_mapel" 
            multiple 
            required
            class="w-full text-sm focus:ring-0 border-none bg-white"
            size="10"
        >
            <!-- Opsi diformat agar readable -->
            <template 
                x-for="item in kelasMapelList.filter(km => km.label.toLowerCase().includes(searchKelasMapel.toLowerCase()))" 
                :key="item.id"
            >
                <option 
                    :value="item.id" 
                    :selected="selected.includes(item.id)"
                    x-html="`
                        <div class='py-1'>
                            <strong>${item.kelas} - ${item.mapel}</strong><br>
                            <span class='text-gray-500 text-xs'>${item.guru}</span>
                        </div>
                    `"
                    class="py-2 px-2 border-b border-gray-100"
                ></option>
            </template>
        </select>
    </div>

        @error('id_kelas_mapel')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>


            @php
        $siswaFormatted = $siswaList->map(fn($siswa) => [
            'id' => $siswa->id,
            'nama' => $siswa->nama_siswa,
            'nisn' => $siswa->nisn,
            'label' => $siswa->nama_siswa . ' (' . $siswa->nisn . ')',
        ]);
    @endphp

    <div 
        x-data="{
            searchSiswa: '',
            siswaList: {{ Js::from($siswaFormatted) }},
            selectedSiswa: {{ Js::from(old('id_siswa', [])) }}
        }"
        class="mb-6"
    >
        <label for="id_siswa" class="block mb-2 text-sm font-semibold text-gray-700">
            Pilih Siswa <span class="text-red-500">*</span>
        </label>

        <!-- Input pencarian -->
        <input 
            type="text" 
            x-model="searchSiswa" 
            placeholder="Cari nama atau NISN..."
            class="mb-3 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
        >

        <!-- Scrollable Select -->
        <div class="border rounded-md h-64 overflow-y-auto bg-white">
            <select 
                name="id_siswa[]" 
                id="id_siswa" 
                multiple 
                required
                class="w-full text-sm focus:ring-0 border-none bg-white"
                size="10"
            >
                <template 
                    x-for="s in siswaList.filter(item => item.label.toLowerCase().includes(searchSiswa.toLowerCase()))" 
                    :key="s.id"
                >
                    <option 
                        :value="s.id" 
                        :selected="selectedSiswa.includes(s.id)"
                        x-text="`${s.nama} (${s.nisn})`"
                        class="py-2 px-2 border-b border-gray-100"
                    ></option>
                </template>
            </select>
        </div>

        @error('id_siswa')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

            {{-- Tombol Submit --}}
            <div class="flex justify-center gap-4 pt-4">
                <button 
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md font-semibold shadow transition">
                    {{ $editRegis ? 'Update' : 'Simpan' }}
                </button>
                @if($editRegis)
                    <a 
                        href="{{ route('StaffRegisMapelSiswa') }}" 
                        class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-md font-semibold shadow transition">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white shadow-lg rounded-xl border border-gray-200 p-6 max-w-5xl mx-auto">

    <form method="GET" action="{{ url()->current() }}" class="mb-6">
        <input 
            type="text" 
            name="search" 
            placeholder="Cari kelas / mapel / guru..." 
            value="{{ request('search') }}"
            class="mb-3 w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
        >
        <button type="submit"
            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-semibold transition"
        >
            Cari
        </button>
    </form>

    <h2 class="text-xl font-bold text-gray-800 mb-6">Daftar Registrasi Mapel per Kelas</h2>

    @php
        $search = request('search');

        $filtered = $regisMapel->filter(function($item) use ($search) {
            if (!$search) return true;

            $kelas = strtolower($item->kelasMapel->kelas->nama_kelas ?? '');
            $mapel = strtolower($item->kelasMapel->mapel->nama_mapel ?? '');
            $guru  = strtolower($item->kelasMapel->guru->nama_lengkap ?? '');

            return str_contains($kelas, strtolower($search))
                || str_contains($mapel, strtolower($search))
                || str_contains($guru, strtolower($search));
        });

        $groupedByKelas = $filtered->groupBy(fn($item) => $item->kelasMapel->kelas->nama_kelas ?? 'Kelas Tidak Diketahui');
    @endphp

    @forelse($groupedByKelas as $kelas => $items)
        <div class="mb-10">
            <h3 class="text-lg font-semibold text-green-700 mb-4 border-b border-green-300 pb-2">
                Kelas: {{ $kelas }}
            </h3>

            @php
                $groupedByMapel = $items->groupBy(fn($item) => $item->kelasMapel->mapel->nama_mapel ?? 'Mapel Tidak Diketahui');
            @endphp

            <div class="space-y-6">
                @foreach($groupedByMapel as $mapel => $mapelItems)
                    <div class="bg-gray-50 rounded-md p-4 border border-gray-200">
                        <p class="text-base font-semibold text-gray-800 mb-1">{{ $mapel }}</p>
                        <p class="text-sm text-gray-600 mb-3">
                            Guru: {{ $mapelItems->first()->kelasMapel->guru->nama_lengkap ?? '-' }}
                        </p>

                        <div class="space-y-4">
                            @foreach($mapelItems as $index => $regis)
                                <div class="flex justify-between items-start bg-white p-3 border rounded-md shadow-sm hover:bg-gray-50 transition">
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-700">
                                            {{ $loop->iteration }}. {{ $regis->siswa->nama_siswa ?? '-' }}
                                        </p>
                                        <p class="text-gray-500 text-sm">NISN: {{ $regis->siswa->nisn ?? '-' }}</p>
                                    </div>

                                  <div class="flex gap-2">
                                    <a href="{{ route('StaffRegisMapelSiswa', ['edit' => $regis->id]) }}"
                                    class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-blue-200 rounded-md">
                                        <img src="/edit.png" alt="Edit" class="w-4 h-4">
                                        Edit
                                    </a>

                                    <form id="deleteForm{{ $regis->id }}" action="{{ route('regis-mapel.destroy', $regis->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete({{ $regis->id }})"
                                            class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-red-700 hover:text-white transition duration-200 rounded shadow"
                                            title="Hapus Data">
                                            <img src="/bin.png" alt="Delete" class="w-4 h-4"> Hapus
                                        </button>
                                    </form>
                                </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <p class="text-gray-500">Belum ada data registrasi.</p>
    @endforelse
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