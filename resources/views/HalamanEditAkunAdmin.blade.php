@extends('layouts.MainAdmin')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarAdmin')

<div class="ml-64 p-6 mt-20">
    <div class="flex items-center gap-2">
        <h2 class="text-2xl font-bold text-green-800">Selamat Datang di halaman Edit Akun Super Admin</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>
</div>

<main class="ml-64 p-8 font-[Verdana] min-h-screen">
    <div class="rounded-xl shadow-xl">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-3xl text-center font-bold text-green-900 mb-6">Edit Akun Pengguna</h2>

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="nama_lengkap" class="block mb-2 text-lg font-semibold text-gray-800">Nama Pengguna</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap"
                       value="{{ old('nama_lengkap', $user->pegawai ? $user->pegawai->nama_lengkap : ($user->walimurid && $user->walimurid->siswa ? $user->walimurid->siswa->nama_siswa : '')) }}"
                        placeholder="Input nama lengkap"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    @error('nama_lengkap')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="username" class="block mb-2 text-lg font-semibold text-gray-800">Username</label>
                    <input type="text" name="username" id="username"
                        value="{{ old('username', $user->username) }}"
                        placeholder="Input Username Pengguna"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    @error('username')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block mb-2 text-lg font-semibold text-gray-800">Password</label>
                    <input type="password" name="password" id="password"
                        placeholder="Input Password Baru (jika ingin diubah)"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-8 mb-6">
                    <div class="w-1/2">
                        <label for="role" class="block mb-2 text-lg font-semibold text-gray-800">Role</label>
                        <select name="role" id="role" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option disabled>Pilih Role</option>
                            @php
                                $roles = ['yayasan', 'admin' , 'lembaga_sd', 'lembaga_smp', 'staff_sd', 'staff_smp', 'guru_sd', 'guru_smp', 'walimurid_sd', 'walimurid_smp'];
                            @endphp
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-1/2">
                        <label for="isDeleted" class="block mb-2 text-lg font-semibold text-gray-800">Status</label>
                        <select name="isDeleted" id="isDeleted" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="0" {{ old('isDeleted', $user->isDeleted) == 0 ? 'selected' : '' }}>Aktif</option>
                            <option value="1" {{ old('isDeleted', $user->isDeleted) == 1 ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('isDeleted')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-center gap-6">
                    <a href="{{ route('LihatAkunPenggunaAdmin') }}" class="px-6 py-3 border border-green-500 text-green-600 rounded-full hover:bg-green-100 transition-all duration-300">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-all duration-300">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@include('layouts.FooterAdmin')
@endsection
