@extends('layouts.MainAdmin')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarAdmin')

@php
    $noResults = $noResults ?? false;
@endphp

<div class="ml-64 p-6 mt-15 font-[Verdana]">
    <div class="flex items-center gap-2 mb-5">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang di Halaman Lihat Akun Yayasan Super Admin</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-600 text-white border border-green-700 rounded-md shadow-md flex items-center gap-2">
            <img src="/check.png" alt="Success" class="w-5 h-5">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-md p-6">

        {{-- Modal Delete --}}
        <div id="confirmDeleteModal" class="absolute top-20 left-1/2 transform -translate-x-1/2 bg-white p-6 rounded-xl shadow-lg w-96 text-center font-[Verdana] z-40 hidden">
            <h3 class="text-lg font-bold text-red-600 mb-2">Yakin ingin menghapus akun ini?</h3>
            <p class="text-sm text-gray-600 mb-4">Tindakan ini tidak bisa dibatalkan.</p>
            <div class="flex justify-center gap-4">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Batal</button>
                <form id="confirmDeleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">Hapus</button>
                </form>
            </div>
        </div>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('users.caripengguna') }}" class="flex flex-wrap gap-5 items-center justify-end bg-gray-50 p-5 rounded-md shadow border border-gray-200 mb-10">
            {{-- Search --}}
            <div class="relative w-full sm:w-auto flex items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengguna..."
                    class="pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm w-64" />
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </div>
            </div>

            {{-- Status --}}
            <div class="relative w-full sm:w-auto flex items-center">
                <select name="status"
                    class="pl-12 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 w-48">
                    <option value="">Status</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Aktif</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Role --}}
            <div class="relative w-full sm:w-auto flex items-center">
                <select name="role"
                    class="pl-12 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 w-56">
                    <option value="">Level</option>
                    @foreach ([
                        'admin' => 'Admin',
                        'yayasan' => 'Yayasan',
                        'lembaga_sd' => 'Lembaga SD',
                        'lembaga_smp' => 'Lembaga SMP',
                        'staff_sd' => 'Staff SD',
                        'staff_smp' => 'Staff SMP',
                        'guru_sd' => 'Guru SD',
                        'guru_smp' => 'Guru SMP',
                        'walimurid_sd' => 'Wali Murid SD',
                        'walimurid_smp' => 'Wali Murid SMP'
                    ] as $key => $label)
                        <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4 4 0 0112 14a4 4 0 016.879 3.804M12 12v.01M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>

            {{-- Filter Button --}}
            <button type="submit"
                class="flex items-center gap-2 bg-green-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-300 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13v6a1 1 0 01-2 0v-6L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                Filter
            </button>

            {{-- Reset Button --}}
            <a href="{{ route('users.caripengguna') }}"
                class="flex items-center gap-2 bg-gray-300 text-gray-800 px-5 py-2 rounded-lg text-sm hover:bg-gray-400 transition-colors duration-300 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Reset
            </a>
        </form>

        {{-- No Results Message --}}
        @if($noResults)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-5 mb-6 rounded-lg text-center font-semibold">
            <p>Mohon Maaf, data yang kamu cari tidak ditemukan.</p>
        </div>
        @endif

            {{-- Table --}}
            <table class="w-full text-center font-[Verdana] border-separate border-spacing-y-3 mb-8 mt-4">
                <thead class="bg-green-600 text-white text-sm uppercase tracking-wide">
                    <tr>
                        <th class="p-2 rounded-tl-md border-l border-green-600">#</th>
                        <th class="p-2 text-left">Nama Pengguna</th>
                        <th class="p-2 text-left">Username</th>
                        <th class="p-2">Status Akun</th>
                        <th class="p-2">Level</th>
                        <th class="p-2 rounded-tr-md border-r border-green-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    @foreach ($users as $user)
                        <tr class="bg-white rounded-md shadow-sm border-y border-gray-200">
                            <td class="p-3 border-y border-l border-gray-200 align-middle">{{ $loop->iteration }}</td>
                            
                            <td class="p-3 border-y border-gray-200 text-left align-middle font-semibold">
                                {{ $user->pegawai->nama_lengkap ?? $user->walimurid->siswa->nama_siswa ?? '-' }}
                            </td>

                            <td class="p-3 border-y border-gray-200 text-left align-middle">
                                <div class="flex items-center gap-3">
                                    @if ($user->pegawai?->foto)
                                        <img src="{{ asset('storage/' . $user->pegawai->foto) }}" 
                                            alt="Foto Pegawai {{ $user->username }}" 
                                            class="w-8 h-8 rounded-full object-cover border border-green-500">
                                    @elseif ($user->walimurid?->siswa?->foto)
                                        <img src="{{ asset('storage/foto_siswa/' . $user->walimurid->siswa->foto) }}" 
                                            alt="Foto Wali Murid {{ $user->username }}" 
                                            class="w-8 h-8 rounded-full object-cover border border-blue-500">
                                    @else
                                        <img src="{{ asset('user.png') }}" alt="Default Foto" 
                                            class="w-8 h-8 rounded-full object-cover border border-gray-300">
                                    @endif
                                    <span class="text-sm font-medium text-gray-800">{{ $user->username }}</span>
                                </div>
                            </td>

                            <td class="p-3 border-y border-gray-200 align-middle">
                                @if($user->isDeleted == 0)
                                    <span class="inline-block bg-green-600 text-white text-xs px-3 py-1 rounded-full">Aktif</span>
                                @else
                                    <span class="inline-block bg-red-600 text-white text-xs px-3 py-1 rounded-full">Nonaktif</span>
                                @endif
                            </td>

                            <td class="p-3 border-y border-gray-200 align-middle">
                                <span class="inline-block bg-gray-200 text-gray-800 text-xs px-3 py-1 rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>

                            <td class="p-3 border-y border-r border-gray-200 align-middle">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                                        <img src="/edit.png" alt="Edit" class="w-5 h-5"> Edit
                                    </a>
                                    <button type="button" onclick="openModal('{{ route('users.destroy', $user->id) }}')" class="text-red-600 hover:text-red-700 text-sm flex items-center gap-1">
                                        <img src="/bin.png" alt="Delete" class="w-5 h-5"> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                
            {{-- Pagination --}}
            <div class="mt-4 flex justify-center gap-2">
                {{ $users->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    
</div>



<script>
    function openModal(actionUrl) {
        const modal = document.getElementById('confirmDeleteModal');
        const form = document.getElementById('confirmDeleteForm');
        form.action = actionUrl;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('confirmDeleteModal').classList.add('hidden');
    }
</script>


@endsection
