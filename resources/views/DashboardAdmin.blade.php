@extends('layouts.MainAdmin')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarAdmin')

@php
    $noResults = $noResults ?? false;
@endphp

<div class="ml-64 p-6 mt-16 min-h-screen bg-gray-50">
    {{-- Welcome Section --}}
    <div class="flex items-center gap-4 mb-10">
        <h2 class="text-4xl font-extrabold text-gray-900">Selamat Datang di halaman Super Admin</h2>
        <img src="/SIYIMM.png" class="h-14" alt="SYIMM Logo">
    </div>

    {{-- Total Users Card --}}
    <div class="max-w-sm bg-gradient-to-r from-green-600 to-green-400 rounded-xl p-6 shadow-xl hover:scale-105 transform transition-transform duration-300 mb-10">
        <div class="flex items-center gap-3 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6v-4a4 4 0 00-8 0v4h8zM7 8v2a2 2 0 002 2h6a2 2 0 002-2V8a4 4 0 00-8 0z" />
            </svg>
            <h3 class="text-white font-semibold text-xl">Total Akun Pengguna</h3>
        </div>
        <img src="/SIYIMM2.png" class="h-10 mt-1 mb-3" alt="SYIMM Logo">
        <div class="text-5xl font-extrabold text-white">{{ $counter }}</div>
        <p class="italic text-white text-sm mt-1">Total akun yang telah anda daftarkan</p>
    </div>

    {{-- User List Section --}}
    <div class="bg-white shadow-lg rounded-xl p-8">
        <div class="flex items-center gap-3 mb-8">
            <h3 class="font-bold text-gray-900 text-2xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h11M9 21V3M16 21h5M16 3v18" />
                </svg>
                Berikut adalah akun pengguna yang telah anda tambahkan
            </h3>
            <img src="/SIYIMM2.png" class="h-7" alt="SYIMM Logo">
        </div>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('users.cariadmin') }}" class="flex flex-wrap gap-5 items-center justify-end bg-gray-50 p-5 rounded-md shadow border border-gray-200 mb-10">
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
            <a href="{{ route('users.cariadmin') }}"
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

        {{-- User Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-4 text-left font-sans">
                <thead class="bg-green-600 text-white uppercase text-sm tracking-wide rounded-lg">
                    <tr>
                        <th class="p-4 rounded-tl-lg border-l border-green-700">#</th>
                        <th class="p-4">Nama Pengguna</th>
                        <th class="p-4">Username</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Akun Ditambahkan</th>
                        <th class="p-4 rounded-tr-lg text-center border-r border-green-700">Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                    <tr class="bg-white rounded-lg shadow-sm border-b border-green-300 hover:bg-green-50 transition-colors duration-200">
                        <td class="p-4 border-l border-green-400 text-center font-semibold">{{ $loop->iteration }}</td>

                        {{-- Nama Pengguna --}}
                        <td class="p-4 font-semibold text-gray-900">
                            {{ $user->pegawai?->nama_lengkap ?: ($user->walimurid?->siswa?->nama_siswa ?: '-') }}

                        </td>

                        {{-- Username + Foto --}}
                        <td class="p-4 flex items-center gap-3 border-y border-gray-200">
                            @if ($user->pegawai?->foto)
                                <img src="{{ asset('storage/' . $user->pegawai->foto) }}" 
                                    alt="Foto Pegawai {{ $user->username }}" 
                                    class="w-9 h-9 rounded-full object-cover border-2 border-green-500 shadow-sm">
                            @elseif ($user->walimurid?->siswa?->foto)
                                <img src="{{ asset('storage/foto_siswa/' . $user->walimurid->siswa->foto) }}" 
                                    alt="Foto Wali Murid {{ $user->username }}" 
                                    class="w-9 h-9 rounded-full object-cover border-2 border-blue-500 shadow-sm">
                            @else
                                <img src="{{ asset('user.png') }}" alt="Default Foto" 
                                    class="w-9 h-9 rounded-full object-cover border-2 border-gray-300 shadow-sm">
                            @endif
                            <span class="text-gray-700 font-medium text-sm">{{ $user->username }}</span>
                        </td>

                        {{-- Status --}}
                        <td class="p-4 text-center">
                            @if ($user->isDeleted == 0)
                                <span class="inline-block bg-green-500 text-white text-xs px-4 py-1 rounded-full font-semibold shadow-sm">Aktif</span>
                            @else
                                <span class="inline-block bg-red-500 text-white text-xs px-4 py-1 rounded-full font-semibold shadow-sm">Tidak Aktif</span>
                            @endif
                        </td>

                        {{-- Tanggal --}}
                        <td class="p-4 text-center">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-4 py-1 rounded-full font-semibold">
                                {{ $user->created_at->format('d M Y') }}
                            </span>
                        </td>

                        {{-- Role --}}
                        <td class="p-4 text-center text-xs">
                            <span class="inline-block bg-gray-200 text-gray-800 px-4 py-1 rounded-full font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-8 flex justify-center">
            {{ $users->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>


@endsection
