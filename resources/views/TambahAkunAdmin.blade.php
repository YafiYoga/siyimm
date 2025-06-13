@extends('layouts.MainAdmin')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarAdmin')

<div class="ml-64 p-6 mt-20">
    <div class="flex items-center gap-2">
        <h2 class="text-2xl font-bold text-green-800">Selamat Datang di halaman Tambah Akun Super Admin</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>
</div>

<main class="ml-64 p-8 font-[Verdana]  min-h-screen">
    <div class="  rounded-xl shadow-xl">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-3xl text-center font-bold text-green-900 mb-6">Tambah Akun Pengguna</h2>

            {{-- Pesan sukses --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Pesan error --}}
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label for="namalengkap" class="block mb-2 text-lg font-semibold text-gray-800">Fullname</label>
                    <input type="text" name="namalengkap" id="namalengkap" placeholder="Input nama pengguna" value="{{ old('namalengkap') }}" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    @error('namalengkap')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="username" class="block mb-2 text-lg font-semibold text-gray-800">Username</label>
                    <input type="text" name="username" id="username" placeholder="Input Username Pengguna" value="{{ old('username') }}" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    @error('username')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block mb-2 text-lg font-semibold text-gray-800">Password</label>
                    <input type="password" name="password" id="password" placeholder="Input Password Pengguna" value="{{ old('password') }}" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-8 mb-6">
                    <div class="w-1/2">
                        <label for="role" class="block mb-2 text-lg font-semibold text-gray-800">Role</label>
                        <select name="role" id="role" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" onchange="toggleNiyInput()">
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="yayasan" {{ old('role') == 'yayasan' ? 'selected' : '' }}>Yayasan</option>
                            <option value="lembaga_sd" {{ old('role') == 'lembaga_sd' ? 'selected' : '' }}>Lembaga SD</option>
                            <option value="lembaga_smp" {{ old('role') == 'lembaga_smp' ? 'selected' : '' }}>Lembaga SMP</option>
                            <option value="staff_sd" {{ old('role') == 'staff_sd' ? 'selected' : '' }}>Staff SD</option>
                            <option value="staff_smp" {{ old('role') == 'staff_smp' ? 'selected' : '' }}>Staff SMP</option>
                            <option value="guru_sd" {{ old('role') == 'guru_sd' ? 'selected' : '' }}>Guru SD</option>
                            <option value="guru_smp" {{ old('role') == 'guru_smp' ? 'selected' : '' }}>Guru SMP</option>
                            <option value="walimurid_sd" {{ old('role') == 'walimurid_sd' ? 'selected' : '' }}>Wali Murid SD</option>
                            <option value="walimurid_smp" {{ old('role') == 'walimurid_smp' ? 'selected' : '' }}>Wali Murid SMP</option>
                        </select>
                        @error('role')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-1/2">
                        <label for="isDeleted" class="block mb-2 text-lg font-semibold text-gray-800">Status</label>
                        <select name="isDeleted" id="isDeleted" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">Pilih Status</option>
                            <option value="0" {{ old('isDeleted') === "0" ? 'selected' : '' }}>Aktif</option>
                            <option value="1" {{ old('isDeleted') === "1" ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('isDeleted')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Input NIY hanya untuk role pegawai --}}
                <div class="mb-6" id="niyInput" style="display: none;">
                    <label for="niy" class="block mb-2 text-lg font-semibold text-gray-800">NIY (Nomor Induk Y)</label>
                    <input type="text" name="niy" id="niy" placeholder="Input NIY jika ada" value="{{ old('niy') }}" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    @error('niy')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="foto" class="block mb-2 text-lg font-semibold text-gray-800">Foto</label>
                    <input type="file" name="foto" id="foto" class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    @error('foto')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center gap-6">
                    <a href="{{ route('DashboardAdmin') }}" class="px-6 py-3 border border-green-500 text-green-600 rounded-full hover:bg-green-100 transition-all duration-300">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-all duration-300">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>



<script>
    // List role yang butuh NIY
    const pegawaiRoles = [
        'admin',
        'yayasan',
        'lembaga_sd',
        'lembaga_smp',
        'staff_sd',
        'staff_smp',
        'guru_sd',
        'guru_smp'
    ];

    function toggleNiyInput() {
        const roleSelect = document.getElementById('role');
        const niyInput = document.getElementById('niyInput');
        if (pegawaiRoles.includes(roleSelect.value)) {
            niyInput.style.display = 'block';
        } else {
            niyInput.style.display = 'none';
        }
    }

    // Jalankan saat load halaman, untuk kasus old value (validasi error)
    document.addEventListener('DOMContentLoaded', () => {
        toggleNiyInput();
    });
</script>
@endsection
