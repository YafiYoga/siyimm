@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 p-6 mt-20 font-sans">
    <div class="flex items-center gap-3">
        <h2 class="text-3xl font-bold text-green-700">Tambah Siswa SD</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>
</div>

<main class="ml-64 px-8 py-6 font-sans bg-gray-100 min-h-screen">
    <div class="bg-white p-10 rounded-2xl shadow-lg max-w-4xl mx-auto">
        <h2 class="text-3xl text-center font-bold text-green-800 mb-8">Form Tambah Siswa SD</h2>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Terjadi kesalahan:</strong>
                <ul class="list-disc pl-5 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @php
                $inputClass = "w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition";
                $labelClass = "block font-semibold text-gray-700 mb-1";
            @endphp

            {{-- Nama Lengkap --}}
            <div>
                <label for="nama_siswa" class="{{ $labelClass }}">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                <input type="text" name="nama_siswa" id="nama_siswa" class="{{ $inputClass }}" value="{{ old('nama_siswa') }}" required>
            </div>

            {{-- NISN & NIK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nisn" class="{{ $labelClass }}">NISN <span class="text-red-500">*</span></label>
                    <input type="number" name="nisn" id="nisn" class="{{ $inputClass }}" value="{{ old('nisn') }}" required>
                </div>
                <div>
                    <label for="nik" class="{{ $labelClass }}">NIK <span class="text-red-500">*</span></label>
                    <input type="number" name="nik" id="nik" class="{{ $inputClass }}" value="{{ old('nik') }}" required>
                </div>
            </div>

            {{-- Tempat & Tanggal Lahir --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tempat_lahir" class="{{ $labelClass }}">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="{{ $inputClass }}" value="{{ old('tempat_lahir') }}" required>
                </div>
                <div>
                    <label for="tanggal_lahir" class="{{ $labelClass }}">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="{{ $inputClass }}" value="{{ old('tanggal_lahir') }}" required>
                </div>
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="{{ $labelClass }}">Alamat <span class="text-red-500">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" class="{{ $inputClass }}" required>{{ old('alamat') }}</textarea>
            </div>

            {{-- Asal Sekolah --}}
            <div>
                <label for="asal_sekolah" class="{{ $labelClass }}">Asal Sekolah <span class="text-red-500">*</span></label>
                <input type="text" name="asal_sekolah" id="asal_sekolah" class="{{ $inputClass }}" value="{{ old('asal_sekolah') }}" required>
            </div>

            {{-- Tinggal Dengan --}}
            <div>
                <label for="tinggal_dengan" class="{{ $labelClass }}">Tinggal Dengan <span class="text-red-500">*</span></label>
                <select name="tinggal_dengan" id="tinggal_dengan" class="{{ $inputClass }}" required>
                    <option value="">-- Pilih --</option>
                    <option value="orang_tua">Orang Tua</option>
                    <option value="wali">Wali</option>
                </select>
            </div>

            {{-- Ayah & Ibu --}}
            <div id="form_ayah_ibu" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                <div>
                    <label for="nama_ayah" class="{{ $labelClass }}">Nama Ayah</label>
                    <input type="text" name="nama_ayah" id="nama_ayah" class="{{ $inputClass }}" value="{{ old('nama_ayah') }}">
                </div>
                <div>
                    <label for="nama_ibu" class="{{ $labelClass }}">Nama Ibu</label>
                    <input type="text" name="nama_ibu" id="nama_ibu" class="{{ $inputClass }}" value="{{ old('nama_ibu') }}">
                </div>
            </div>

            {{-- Nama Wali --}}
            <div id="form_wali" class="hidden">
                <label for="nama_wali" class="{{ $labelClass }}">Nama Wali</label>
                <input type="text" name="nama_wali" id="nama_wali" class="{{ $inputClass }}" value="{{ old('nama_wali') }}">
            </div>

            {{-- KK & Kesehatan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="no_kk" class="{{ $labelClass }}">No. KK <span class="text-red-500">*</span></label>
                    <input type="text" name="no_kk" id="no_kk" class="{{ $inputClass }}" value="{{ old('no_kk') }}" required>
                </div>
                <div>
                    <label for="berat_badan" class="{{ $labelClass }}">Berat Badan (kg) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.1" name="berat_badan" id="berat_badan" class="{{ $inputClass }}" value="{{ old('berat_badan') }}" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tinggi_badan" class="{{ $labelClass }}">Tinggi Badan (cm) <span class="text-red-500">*</span></label>
                    <input type="number" name="tinggi_badan" id="tinggi_badan" class="{{ $inputClass }}" value="{{ old('tinggi_badan') }}" required>
                </div>
                <div>
                    <label for="lingkar_kepala" class="{{ $labelClass }}">Lingkar Kepala (cm) <span class="text-red-500">*</span></label>
                    <input type="number" name="lingkar_kepala" id="lingkar_kepala" class="{{ $inputClass }}" value="{{ old('lingkar_kepala') }}" required>
                </div>
            </div>

            {{-- Saudara & Jarak --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="jumlah_saudara_kandung" class="{{ $labelClass }}">Jumlah Saudara Kandung <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_saudara_kandung" id="jumlah_saudara_kandung" class="{{ $inputClass }}" value="{{ old('jumlah_saudara_kandung') }}" required>
                </div>
                <div>
                    <label for="jarak_rumah_ke_sekolah" class="{{ $labelClass }}">Jarak Rumah ke Sekolah (km) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="jarak_rumah_ke_sekolah" id="jarak_rumah_ke_sekolah" class="{{ $inputClass }}" value="{{ old('jarak_rumah_ke_sekolah') }}" required>
                </div>
            </div>

            {{-- Kelas --}}
            <div>
    <label for="lembaga" class="{{ $labelClass }}">Lembaga <span class="text-red-500">*</span></label>
    <select name="lembaga" id="lembaga" class="{{ $inputClass }}" required>
        <option value="">-- Pilih Lembaga --</option>
        @foreach (\App\Models\Siswa::LEMBAGA_OPTIONS as $lembaga)
            <option value="{{ $lembaga }}" {{ old('lembaga') == $lembaga ? 'selected' : '' }}>
                {{ $lembaga }}
            </option>
        @endforeach
    </select>
</div>


            {{-- Status --}}
            <div>
                <label for="status" class="{{ $labelClass }}">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" class="{{ $inputClass }}" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Lulus" {{ old('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="Pindah" {{ old('status') == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                </select>
            </div>

            {{-- Foto --}}
            <div>
                <label for="foto" class="{{ $labelClass }}">Foto <span class="text-red-500">*</span></label>
                <input type="file" name="foto" id="foto" class="{{ $inputClass }}" required>
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="{{ $labelClass }}">Role <span class="text-red-500">*</span></label>
                <select name="role" id="role" class="{{ $inputClass }}" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="walimurid_sd" {{ old('role') == 'walimurid_sd' ? 'selected' : '' }}>Wali Murid SD</option>
                    <option value="walimurid_smp" {{ old('role') == 'walimurid_smp' ? 'selected' : '' }}>Wali Murid SMP</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('siswa.index') }}" class="px-6 py-2 rounded-lg border border-gray-400 text-gray-700 hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 shadow-md transition">Simpan</button>
            </div>
        </form>
    </div>
</main>



{{-- Script Interaktif --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tinggalDengan = document.getElementById('tinggal_dengan');
        const formAyahIbu = document.getElementById('form_ayah_ibu');
        const formWali = document.getElementById('form_wali');

        function toggleForms() {
            if (tinggalDengan.value === 'orang_tua') {
                formAyahIbu.classList.remove('hidden');
                formWali.classList.add('hidden');
            } else if (tinggalDengan.value === 'wali') {
                formWali.classList.remove('hidden');
                formAyahIbu.classList.add('hidden');
            } else {
                formAyahIbu.classList.add('hidden');
                formWali.classList.add('hidden');
            }
        }

        tinggalDengan.addEventListener('change', toggleForms);

        // Trigger on page load
        toggleForms();
    });
</script>
@endsection
