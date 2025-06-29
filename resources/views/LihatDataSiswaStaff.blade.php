@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class=" font-[Verdana] max-w-full">

    <div class="ml-64 p-6 mt-20 font-[Verdana]">
    <div class="flex items-center gap-2 mb-5">
        <h2 class="text-2xl font-bold text-gray-800">
            @if(auth()->user()->role == 'staff_smp')
                Lihat Data Siswa SMP
            @elseif(auth()->user()->role == 'staff_sd')
                Lihat Data Siswa SD
            @else
                Data Siswa
            @endif
        </h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-600 text-white border border-green-700 rounded-md shadow-md flex items-center gap-2">
            <img src="/check.png" alt="Success" class="w-5 h-5">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-md p-4">
        <div class="flex justify-between items-center mb-6">
   <div class="flex justify-between items-center mb-6">
    <div class="flex gap-3">
        {{-- Tombol Tambah Siswa --}}
        <a href="{{ route('TambahSiswaStaff') }}"
           class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition duration-300 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4v16m8-8H4" />
            </svg>
            Tambah Siswa
        </a>

        {{-- Tombol Import Siswa --}}
        <a href="{{ route('ImportDataSiswaStaff') }}"
           class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition duration-300 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 4v16h16V4H4zm4 4h8M8 12h8M8 16h8" />
            </svg>
            Import Siswa
        </a>
    </div>
</div>

</div>


    
       <form method="GET" action="{{ route('siswa.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-gray-50 p-5 rounded-md shadow border border-gray-200 mb-10">

    {{-- Search --}}
    <div class="">
        <label class="block mb-1 text-sm text-gray-600">Cari Siswa</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau NISN..."
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm w-full" />
        <div class="absolute left-3 top-9 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
        </div>
    </div>

    {{-- Status --}}
    <div class="relative">
        <label class="block mb-1 text-sm text-gray-600">Status</label>
        <select name="status"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 w-full">
            <option value="">Pilih Status</option>
            <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Lulus" {{ request('status') === 'Lulus' ? 'selected' : '' }}>Lulus</option>
            <option value="Pindah" {{ request('status') === 'Pindah' ? 'selected' : '' }}>Pindah</option>
        </select>
        <div class="absolute left-3 top-9 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    {{-- Kelas --}}
  <div class="relative">
    <label class="block mb-1 text-sm text-gray-600">Lembaga</label>
    <select name="lembaga"
        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 w-full">
        <option value="">Pilih Lembaga</option>
        @foreach($lembagaOptions as $lembaga)
            <option value="{{ $lembaga }}" {{ request('lembaga') == $lembaga ? 'selected' : '' }}>
                {{ strtoupper($lembaga) }}
            </option>
        @endforeach
    </select>
    <div class="absolute left-3 top-9 text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13v6a1 1 0 01-2 0v-6L3.293 6.707A1 1 0 013 6V4z" />
        </svg>
    </div>
</div>



    {{-- Tombol Filter & Reset --}}
    <div class="flex gap-2">
        <button type="submit"
            class="flex-1 flex items-center justify-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-300 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13v6a1 1 0 01-2 0v-6L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
            Filter
        </button>
        <a href="{{ route('siswa.index') }}"
            class="flex-1 flex items-center justify-center gap-2 bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 transition-colors duration-300 shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Reset
        </a>
    </div>
</form>



{{-- No Results Message --}}
{{-- No Results Message --}}
@if(
    (request('search') || request('status') || request('lembaga')) 
    && $siswa->isEmpty()
)
<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-5 mb-6 rounded-lg text-center font-semibold">
    <p>Mohon Maaf, data yang kamu cari tidak ditemukan.</p>
</div>
@endif


        {{-- Data Table --}}
        <div class="overflow-x-auto font-[Verdana] text-sm">
            <table class="w-full min-w-max border border-gray-300 text-center">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="py-2 px-3">#</th>
                        <th class="py-2 px-3">Foto</th>
                        <th class="py-2 px-3">Nama Siswa</th>
                        <th class="py-2 px-3">NISN</th>
                        <th class="py-2 px-3">Tempat, Tanggal Lahir</th>
                        <th class="py-2 px-3">Kelas</th>
                        <th class="py-2 px-3">Nik</th>
                        <th class="py-2 px-3">Alamat</th>
                        <th class="py-2 px-3">Asal Sekolah</th>
                        <th class="py-2 px-3">Nama Ayah</th>
                        <th class="py-2 px-3">Nama Ibu</th>
                        <th class="py-2 px-3">Nama Wali</th>
                        <th class="py-2 px-3">No KK</th>
                        <th class="py-2 px-3">Berat Badan</th>
                        <th class="py-2 px-3">Tinggi Badan</th>
                        <th class="py-2 px-3">Lingkar Kepala</th>
                        <th class="py-2 px-3">Jmlh Saudara Kandung</th>
                        <th class="py-2 px-3">Jarak Ke Sekolah</th>
                        <th class="py-2 px-3">Status</th>
                        <th class="py-2 px-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $item)
                        <tr class="hover:bg-green-50">
                            <td class="py-2 px-3">{{ $index + 1 }}</td>
                            <td class="py-2 px-3">
                                @if($item->foto)
                                    <img
                                        src="{{ asset('storage/foto_siswa/' . $item->foto) }}"
                                        alt="Foto {{ $item->nama_siswa }}"
                                        class="w-12 h-12 object-cover rounded-full border border-green-400 mx-auto"
                                    />
                                @else
                                    <img src="/user.png" alt="Default Foto" class="w-12 h-12 object-cover rounded-full border border-green-400 mx-auto" />
                                @endif
                            </td>
                            <td class="py-2 px-3">{{ $item->nama_siswa }}</td>
                            <td class="py-2 px-3">{{ $item->nisn }}</td>
                            <td class="py-2 px-3">{{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                            <td class="py-2 px-3">{{ $item->kelas }}</td>
                            <td class="py-2 px-3">{{ $item->nik }}</td>
                            <td class="py-2 px-3">{{ $item->alamat }}</td>
                            <td class="py-2 px-3">{{ $item->asal_sekolah }}</td>
                            <td class="py-2 px-3">{{ $item->nama_ayah }}</td>
                            <td class="py-2 px-3">{{ $item->nama_ibu }}</td>
                            <td class="py-2 px-3">{{ $item->nama_wali }}</td>
                            <td class="py-2 px-3">{{ $item->no_kk }}</td>
                            <td class="py-2 px-3">{{ $item->berat_badan }}</td>
                            <td class="py-2 px-3">{{ $item->tinggi_badan }}</td>
                            <td class="py-2 px-3">{{ $item->lingkar_kepala }}</td>
                            <td class="py-2 px-3">{{ $item->jumlah_saudara_kandung }}</td>
                            <td class="py-2 px-3">{{ $item->jarak_rumah_ke_sekolah }}</td>
                             <td class="py-2 px-3">{{ $item->status }}</td>
                            
                            <td class="py-2 px-3">
                                <div class="flex justify-center gap-2 flex-wrap">
                                    <a href="{{ route('siswa.edit', $item->id) }}"
                                       class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-blue-700 rounded shadow"
                                       title="Edit Data">
                                        <img src="/edit.png" alt="Edit" class="w-4 h-4" /> Edit
                                    </a>

                                    <form id="deleteForm{{ $item->id }}" action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                onclick="confirmDelete('{{ $item->id }}')"
                                                class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-red-700 rounded shadow"
                                                title="Hapus Data">
                                            <img src="/bin.png" alt="Delete" class="w-4 h-4" /> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-center text-gray-500 font-semibold">Data siswa tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $siswa->withQueryString()->links() }}
        </div>

    </div>

</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }
</script>


@endsection
