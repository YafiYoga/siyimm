@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 p-6 mt-20 font-sans">
    <div class="flex items-center gap-3">
        <h2 class="text-3xl font-bold text-green-700">Edit Siswa </h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>
</div>

<main class="ml-64 px-8 py-6 font-sans bg-gray-100 min-h-screen">
    <div class="bg-white p-10 rounded-2xl shadow-lg max-w-4xl mx-auto">
        <h2 class="text-3xl text-center font-bold text-green-800 mb-8">Form Edit Data Siswa </h2>

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

        <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            @php
                $inputClass = "w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition";
                $labelClass = "block font-semibold text-gray-700 mb-1";
            @endphp

            {{-- Nama Lengkap --}}
            <div>
                <label for="nama_siswa" class="{{ $labelClass }}">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                <input type="text" name="nama_siswa" id="nama_siswa" class="{{ $inputClass }}" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" required>
            </div>

            {{-- NISN, NIK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nisn" class="{{ $labelClass }}">NISN</label>
                    <input type="number" name="nisn" id="nisn" class="{{ $inputClass }}" value="{{ old('nisn', $siswa->nisn) }}">
                </div>
                <div>
                    <label for="nik" class="{{ $labelClass }}">NIK</label>
                    <input type="number" name="nik" id="nik" class="{{ $inputClass }}" value="{{ old('nik', $siswa->nik) }}">
                </div>
            </div>

            {{-- Tempat, Tanggal Lahir --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="tempat_lahir" class="{{ $labelClass }}">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" id="tempat_lahir" class="{{ $inputClass }}"
                   value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}">
            @error('tempat_lahir')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="tanggal_lahir" class="{{ $labelClass }}">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="{{ $inputClass }}"
                   value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}">
            @error('tanggal_lahir')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="{{ $labelClass }}">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3" class="{{ $inputClass }}">{{ old('alamat', $siswa->alamat) }}</textarea>
            </div>

            {{-- Asal Sekolah, No KK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="asal_sekolah" class="{{ $labelClass }}">Asal Sekolah</label>
                    <input type="text" name="asal_sekolah" id="asal_sekolah" class="{{ $inputClass }}" value="{{ old('asal_sekolah', $siswa->asal_sekolah) }}">
                </div>
                <div>
                    <label for="no_kk" class="{{ $labelClass }}">Nomor KK</label>
                    <input type="text" name="no_kk" id="no_kk" class="{{ $inputClass }}" value="{{ old('no_kk', $siswa->no_kk) }}">
                </div>
            </div>

            {{-- Berat, Tinggi, Lingkar Kepala --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="berat_badan" class="{{ $labelClass }}">Berat Badan (kg)</label>
                    <input type="number" name="berat_badan" id="berat_badan" class="{{ $inputClass }}" value="{{ old('berat_badan', $siswa->berat_badan) }}">
                </div>
                <div>
                    <label for="tinggi_badan" class="{{ $labelClass }}">Tinggi Badan (cm)</label>
                    <input type="number" name="tinggi_badan" id="tinggi_badan" class="{{ $inputClass }}" value="{{ old('tinggi_badan', $siswa->tinggi_badan) }}">
                </div>
                <div>
                    <label for="lingkar_kepala" class="{{ $labelClass }}">Lingkar Kepala (cm)</label>
                    <input type="number" name="lingkar_kepala" id="lingkar_kepala" class="{{ $inputClass }}" value="{{ old('lingkar_kepala', $siswa->lingkar_kepala) }}">
                </div>
            </div>

            {{-- Jumlah Saudara Kandung, Jarak Rumah --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="jumlah_saudara_kandung" class="{{ $labelClass }}">Jumlah Saudara Kandung</label>
                    <input type="number" name="jumlah_saudara_kandung" id="jumlah_saudara_kandung" class="{{ $inputClass }}" value="{{ old('jumlah_saudara_kandung', $siswa->jumlah_saudara_kandung) }}">
                </div>
                <div>
                    <label for="jarak_rumah_ke_sekolah" class="{{ $labelClass }}">Jarak Rumah ke Sekolah (km)</label>
                    <input type="number" step="0.1" name="jarak_rumah_ke_sekolah" id="jarak_rumah_ke_sekolah" class="{{ $inputClass }}" value="{{ old('jarak_rumah_ke_sekolah', $siswa->jarak_rumah_ke_sekolah) }}">
                </div>
            </div>

            {{-- Tinggal Dengan --}}
            <div>
                <label for="tinggal_dengan" class="{{ $labelClass }}">Tinggal Dengan</label>
                <select name="tinggal_dengan" id="tinggal_dengan" class="{{ $inputClass }}" onchange="toggleWaliField()">
                    <option value="">-- Pilih --</option>
                    <option value="orang_tua" {{ old('tinggal_dengan', $siswa->tinggal_dengan) == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                    <option value="wali" {{ old('tinggal_dengan', $siswa->tinggal_dengan) == 'wali' ? 'selected' : '' }}>Wali</option>
                </select>
            </div>

            {{-- Ayah Ibu / Wali --}}
            <div id="form_ayah_ibu" class="grid grid-cols-1 md:grid-cols-2 gap-6 {{ old('tinggal_dengan', $siswa->tinggal_dengan) == 'wali' ? 'hidden' : '' }}">
                <div>
                    <label for="nama_ayah" class="{{ $labelClass }}">Nama Ayah</label>
                    <input type="text" name="nama_ayah" id="nama_ayah" class="{{ $inputClass }}" value="{{ old('nama_ayah', $siswa->nama_ayah) }}">
                </div>
                <div>
                    <label for="nama_ibu" class="{{ $labelClass }}">Nama Ibu</label>
                    <input type="text" name="nama_ibu" id="nama_ibu" class="{{ $inputClass }}" value="{{ old('nama_ibu', $siswa->nama_ibu) }}">
                </div>
            </div>

            <div id="form_nama_wali" class="{{ old('tinggal_dengan', $siswa->tinggal_dengan) == 'wali' ? '' : 'hidden' }}">
                <label for="nama_wali" class="{{ $labelClass }}">Nama Wali</label>
                <input type="text" name="nama_wali" id="nama_wali" class="{{ $inputClass }}" value="{{ old('nama_wali', $siswa->nama_wali) }}">
            </div>

            {{-- Upload Foto --}}
            <div>
                <label for="foto" class="{{ $labelClass }}">Upload Foto</label>
                <input type="file" name="foto" id="foto" accept="image/*" class="{{ $inputClass }}">
                @if ($siswa->foto)
                    <img src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto Siswa" class="mt-4 h-28 rounded">
                @endif
            </div>

            

            {{-- Lembaga (hidden) --}}
            <input type="hidden" name="lembaga" value="{{ old('lembaga', $siswa->lembaga ?? '') }}">

            {{-- Status --}}
            <div>
                <label for="status" class="{{ $labelClass }}">Status</label>
                <select name="status" id="status" class="{{ $inputClass }}">
                    <option value="">-- Pilih Status --</option>
                    <option value="Aktif" {{ old('status', $siswa->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Lulus" {{ old('status', $siswa->status) == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="Pindah" {{ old('status', $siswa->status) == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                </select>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-center">
                <button type="submit" class="bg-green-700 text-white px-10 py-3 rounded-lg hover:bg-green-800 transition font-semibold shadow-lg">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    function toggleWaliField() {
        const tinggalDengan = document.getElementById('tinggal_dengan').value;
        const formAyahIbu = document.getElementById('form_ayah_ibu');
        const formWali = document.getElementById('form_nama_wali');

        if (tinggalDengan === 'wali') {
            formAyahIbu.classList.add('hidden');
            formWali.classList.remove('hidden');
        } else {
            formAyahIbu.classList.remove('hidden');
            formWali.classList.add('hidden');
        }
    }
</script>
@endsection
