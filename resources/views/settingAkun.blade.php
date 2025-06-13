@extends($layout)

@section('content')

{{-- Header --}}
@include('layouts.Header')

{{-- Sidebar --}}
@include($sidebar)


{{-- Konten Utama --}}
<div class="ml-64 p-6 mt-20">
    <div class="flex items-center gap-2">
        <h2 class="text-2xl font-bold text-green-800">Pengaturan Akun</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>
</div>

<main class="ml-30 p-8 font-[Verdana] min-h-screen">
   
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-2xl mx-auto">

            <h2 class="text-3xl text-center font-bold text-green-900 mb-6">Edit Profil Pengguna</h2>

            {{-- Pesan sukses --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Pesan error --}}
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Foto Profil --}}
                <div class="mb-6">
                    <label for="foto" class="block mb-2 text-lg font-semibold text-gray-800">Foto Profil</label>
                    <div class="flex items-center gap-4">
                        <img 
                            id="fotoPreview"
                            src="{{ $user->foto ? asset('storage/' . $user->foto) : '' }}" 
                            alt="Foto Profil"
                            class="w-20 h-20 rounded-full object-cover border border-gray-300 shadow-sm {{ $user->foto ? '' : 'hidden' }}" 
                        />
                        <div id="fotoPlaceholder" class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-semibold border border-gray-300 {{ $user->foto ? 'hidden' : '' }}">
                            N/A
                        </div>
                        <input
                            type="file"
                            name="foto"
                            id="foto"
                            class="border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                        />
                    </div>
                    @error('foto')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="mb-6">
                    <label for="username" class="block mb-2 text-lg font-semibold text-gray-800">Username</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        value="{{ old('username', $user->username) }}"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                    />
                    @error('username')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-lg font-semibold text-gray-800">Password Baru</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                    />
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block mb-2 text-lg font-semibold text-gray-800">Konfirmasi Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                    />
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-center gap-6">
                    <a href="{{ route(Auth::user()->role == 'admin' ? 'DashboardAdmin' : 'DashboardStaff') }}"
                        class="px-6 py-3 border border-green-500 text-green-600 rounded-full hover:bg-green-100 transition-all duration-300"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-3 bg-green-600 text-white rounded-full hover:bg-green-700 transition-all duration-300"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        
    </div>
</main>

{{-- Script preview foto --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto');
    const fotoPreview = document.getElementById('fotoPreview');
    const fotoPlaceholder = document.getElementById('fotoPlaceholder');

    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                fotoPreview.src = e.target.result;
                fotoPreview.classList.remove('hidden');
                fotoPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            @if($user->foto)
                fotoPreview.src = "{{ asset('storage/' . $user->foto) }}";
                fotoPreview.classList.remove('hidden');
                fotoPlaceholder.classList.add('hidden');
            @else
                fotoPreview.classList.add('hidden');
                fotoPlaceholder.classList.remove('hidden');
            @endif
        }
    });
});
</script>

@endsection
