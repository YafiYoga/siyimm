@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')

{{-- Sidebar sesuai peran --}}
@if(in_array(Auth::user()->role, ['staff_sd', 'staff_smp']))
    @include('layouts.SidebarStaff')
@endif

<div class="ml-64 p-6 mt-20">
    <div class="flex items-center gap-2">
        <h2 class="text-2xl font-bold text-green-800">Import Data Siswa SD</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>
</div>

<main class="ml-64 p-8 font-[Verdana] min-h-screen">
    <div class="rounded-xl shadow-xl max-w-3xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg">

            <h2 class="text-3xl text-center font-bold text-green-900 mb-6">Import Excel Data Siswa SD</h2>

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

            <form id="importForm" action="{{ route('ImportDataSiswaStaff') }}" method="POST" enctype="multipart/form-data" class="space-y-6" novalidate>
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
                        <li>Nama Siswa</li>
                        <li>NISN</li>
                        <li>Tempat Lahir</li>
                        <li>Tanggal Lahir (format tanggal Excel)</li>
                        <li>NIK</li>
                        <li>Alamat</li>
                        <li>Asal Sekolah</li>
                        <li>Nama Ayah</li>
                        <li>Nama Ibu</li>
                        <li>Nama Wali</li>
                        <li>No KK</li>
                        <li>Berat Badan</li>
                        <li>Tinggi Badan</li>
                        <li>Lingkar Kepala</li>
                        <li>Jumlah Saudara Kandung</li>
                        <li>Jarak Rumah ke Sekolah</li>
                        <li>Kelas</li>
                        <li>Status</li>
                    </ul>
                    <a href="{{ asset('template/template_siswa.xlsx') }}" class="text-green-700 underline text-sm">
                        Download Template Excel Siswa SD
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
                        <div class="w-full bg-green-200 rounded-full h-5 overflow-hidden shadow-inner mt-4">
                            <div id="progressBar" class="bg-green-600 h-5 rounded-full w-0 transition-all duration-300 ease-in-out"></div>
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

        // Disable tombol submit agar tidak bisa klik ulang
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        // Tampilkan loading notification
        loading.classList.remove('hidden');

        // Reset progress bar ke 0%
        progressBar.style.width = '0%';

        // Animasi progress bar naik perlahan ke 100% selama 10 detik
        let progress = 0;
        const interval = setInterval(() => {
            if(progress >= 100) {
                clearInterval(interval);
            } else {
                progress++;
                progressBar.style.width = progress + '%';
            }
        }, 100);
    });
</script>
@endsection
