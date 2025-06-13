@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')

{{-- Sidebar sesuai peran --}}
@if(in_array(Auth::user()->role, ['staff_sd', 'staff_smp']))
    @include('layouts.SidebarStaff')
@endif

<div class="ml-64 p-6 mt-20">
    <div class="flex items-center gap-2">
        <h2 class="text-2xl font-bold text-green-800">Import Data Pegawai</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>
</div>

<main class="ml-64 p-8 font-[Verdana] min-h-screen">
    <div class="rounded-xl shadow-xl max-w-3xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg">

            <h2 class="text-3xl text-center font-bold text-green-900 mb-6">Import Excel Data Pegawai</h2>

            {{-- Pesan sukses --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>

                @if(session('imported'))
                    <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded">
                        <strong>Jumlah data berhasil diimpor:</strong> {{ session('imported') }}
                    </div>
                @endif

                @if(session('duplicates') > 0)
                    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded">
                        <strong>Duplikat ditemukan:</strong> {{ session('duplicates') }} data tidak diimpor karena NIY sudah ada.
                    </div>
                @endif
            @endif

            {{-- Pesan error --}}
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form id="importForm" action="{{ route('pegawai.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6" novalidate>
                @csrf

                <div>
                    <label for="file" class="block mb-2 text-lg font-semibold text-gray-800">Pilih File Excel</label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls" 
                           class="w-full border border-gray-300 px-4 py-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" required>
                    @error('file')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <p class="text-sm text-gray-700 mb-2">* Pastikan format Excel sesuai dengan template berikut:</p>
                    <ul class="list-disc list-inside text-xs text-gray-600 leading-relaxed mb-2">
                        <li>NIY (unik)</li>
                        <li>Unit Kerja</li>
                        <li>Nama Lengkap</li>
                        <li>Nama Panggilan</li>
                        <li>Jenis Kelamin (L/P)</li>
                        <li>Tempat, Tanggal Lahir</li>
                        <li>Alamat</li>
                        <li>No Telepon</li>
                        <li>Email</li>
                        <li>TMT (tanggal, format Excel date)</li>
                        <li>Tugas Kepegawaian</li>
                        <li>Tugas Pokok</li>
                        <li>Status Pernikahan</li>
                        <li>Nama Pasangan</li>
                        <li>Nama Anak</li>
                        <li>Nama Ayah</li>
                        <li>Nama Ibu</li>
                        <li>Pendidikan Terakhir</li>
                        <li>URL Foto</li>
                        <li>Role (opsional)</li>
                    </ul>
                    <a href="{{ asset('template/template_pegawai.xlsx') }}" class="text-green-700 underline text-sm">
                        Download Template Excel Pegawai
                    </a>
                </div>

                <div class="flex flex-col items-center gap-4">
                    <button id="submitBtn" type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full transition-all duration-300">
                        Import Data
                    </button>

                    <div id="loadingNotification" 
                         class="w-full max-w-md hidden flex-col items-center gap-3 p-6 rounded-lg bg-green-50 border border-green-300 shadow-inner"
                         aria-live="polite" role="status">

                        <div class="flex items-center gap-4 w-full">
                            <!-- Spinner -->
                            <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>

                            <span class="text-green-700 font-semibold text-lg">Sedang memproses data, mohon tunggu...</span>
                        </div>

                        <!-- Progress bar container -->
                        <div class="w-full bg-green-200 rounded-full h-3 mt-3">
                            <div id="progressBar" class="bg-green-600 h-3 rounded-full transition-all duration-1000 ease-in-out" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</main>

<script>
    document.getElementById('importForm').addEventListener('submit', function(e) {
        const loading = document.getElementById('loadingNotification');
        const btn = document.getElementById('submitBtn');
        const progressBar = document.getElementById('progressBar');

        btn.disabled = true;
        loading.classList.remove('hidden');

        // Animate progress bar from 0% to 80% over 3 seconds
        progressBar.style.width = '0%';
        setTimeout(() => {
            progressBar.style.width = '80%';
        }, 50);
    });
</script>
@endsection
