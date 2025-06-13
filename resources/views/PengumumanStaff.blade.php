@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-80 flex items-center gap-2 mt-20 mb-5">
    <h2 class="text-2xl font-bold text-gray-800">
        @if(auth()->user()->role == 'staff_smp')
           Kelola Pengumuman SMP
        @elseif(auth()->user()->role == 'staff_sd')
           Kelola Pengumuman SD
        @else
            Kelola Pengumuman
        @endif
    </h2>
    <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
</div>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-600 text-white border border-green-700 rounded-md shadow-md flex items-center gap-2">
            <img src="/check.png" alt="Success" class="w-5 h-5">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Alert Error --}}
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-600 text-white border border-red-700 rounded-md shadow-md flex items-center gap-2">
            <img src="/error.png" alt="Error" class="w-5 h-5">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Form Tambah/Edit --}}
    <div class="bg-white shadow-lg border border-gray-200 rounded-md p-6 max-w-3xl mx-auto mb-20 font-[Verdana] text-sm">
        <h2 class="text-xl font-bold text-center text-green-800 mb-6">
            {{ isset($pengumuman) ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}
        </h2>

        <form method="POST" action="{{ isset($pengumuman) ? route('pengumuman.update', $pengumuman->id) : route('pengumuman.store') }}">
            @csrf
            @if(isset($pengumuman))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Judul Pengumuman</label>
                <input type="text" name="judul" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
                    value="{{ old('judul', $pengumuman->judul ?? '') }}" placeholder="Masukkan judul">
                @error('judul')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">Isi Pengumuman</label>
                <textarea name="isi" rows="4" required
                    class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-600"
                    placeholder="Tulis isi pengumuman">{{ old('isi', $pengumuman->isi ?? '') }}</textarea>
                @error('isi')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-center gap-3 mt-6">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-md font-medium transition-all shadow-md">
                    {{ isset($pengumuman) ? 'Update' : 'Simpan' }}
                </button>
                @if(isset($pengumuman))
                    <a href="{{ route('pengumuman.index') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-md font-medium transition-all shadow-md">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Daftar Pengumuman --}}
    <div class="bg-white shadow-lg border border-gray-200 rounded-md p-6 font-[Verdana] text-sm max-w-5xl mx-auto">
        <h2 class="text-xl font-bold text-green-800 mb-4">Daftar Pengumuman</h2>

        @if ($pengumumen->isEmpty())
            <p class="text-gray-600">Belum ada pengumuman.</p>
        @else
            <div class="space-y-4">
                @foreach ($pengumumen as $item)
                    <div class="border border-gray-300 rounded-md p-4  bg-gray-50 hover:bg-green-50 transition-all duration-200">
                        <h3 class="text-lg font-bold text-gray-800">{{ $item->judul }}</h3>
                        <p class="text-xs italic text-gray-500 mb-2">Ditujukan: {{ ucfirst(str_replace('_', ' ', $item->ditujukan_kepada)) }}</p>
                        <p class="text-gray-700">{{ $item->isi }}</p>
                        <p class="text-xs text-gray-500 mt-3">Dibuat pada: {{ $item->created_at->format('d M Y H:i') }}</p>

                        {{-- Aksi --}}
                        <div class=" flex gap-3 justify-end">
                            {{-- Edit Button --}}
                            <a href="{{ route('pengumuman.edit', $item->id) }}"
                               class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-blue-700 rounded shadow">
                                <img src="/edit.png" alt="Edit" class="w-4 h-4"> Edit
                            </a>

                            {{-- Hapus Button --}}
                            <form action="{{ route('pengumuman.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center gap-1 px-2 py-1 text-sm text-black hover:bg-red-700 rounded shadow">
                                    <img src="/bin.png" alt="Delete" class="w-4 h-4"> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(niy) {
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
                document.getElementById('deleteForm' + niy).submit();
            }
        })
    }
</script>

