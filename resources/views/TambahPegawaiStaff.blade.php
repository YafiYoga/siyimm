@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarStaff')

<div class="ml-64 p-6 mt-20 font-sans">
    <div class="flex items-center gap-3">
        <h2 class="text-3xl font-bold text-green-700">Tambah Data Pegawai</h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo">
    </div>
</div>

<main class="ml-64 px-8 py-6 font-sans bg-gray-100 min-h-screen">
    <div class="bg-white p-10 rounded-2xl shadow-lg max-w-4xl mx-auto">
        <h2 class="text-3xl text-center font-bold text-green-800 mb-8">Form Tambah Pegawai</h2>

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Gagal!</strong> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('pegawai.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            @php
                $fields = [
                    'niy' => 'NIY',
                    'nama_lengkap' => 'Nama Lengkap',
                    'nama_panggilan' => 'Nama Panggilan',
                    'tempat_tanggal_lahir' => 'Tempat, Tanggal Lahir',
                    'alamat' => 'Alamat',
                    'no_telfon' => 'No Telepon',
                    'unit_kerja' => 'Unit Kerja',
                    'tugas_kepegawaian' => 'Tugas Kepegawaian',
                    'tugas_pokok' => 'Tugas Pokok',
                    'email' => 'Email',
                    'tmt' => 'TMT',
                    'nama_ayah' => 'Nama Ayah',
                    'nama_ibu' => 'Nama Ibu',
                    'pendidikan_terakhir' => 'Pendidikan Terakhir',
                    'pas_foto_url' => 'Link Pas Foto',
                    'foto' => 'Foto Pegawai'
                ];
            @endphp

            {{-- Loop Input --}}
            @foreach ($fields as $name => $label)
                <div>
                    <label for="{{ $name }}" class="block font-medium text-gray-700 mb-1">{{ $label }}</label>
                    @if ($name === 'tmt')
                        <input type="date" name="{{ $name }}" id="{{ $name }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                            value="{{ old($name) }}" required>
                    @elseif ($name === 'foto')
                        <input type="file" name="{{ $name }}" id="{{ $name }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" required>
                        @error('foto')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    @else
                        <input type="{{ $name === 'email' ? 'email' : 'text' }}"
                            name="{{ $name }}" id="{{ $name }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                            placeholder="Masukkan {{ strtolower($label) }}"
                            value="{{ old($name) }}" required
                            @if(in_array($name, ['niy', 'no_telfon'])) pattern="[0-9]+" inputmode="numeric" @endif
                        >
                    @endif
                    @error($name)
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            {{-- Jenis Kelamin --}}
            <div>
                <label for="jenis_kelamin" class="block font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status Pernikahan --}}
            <div>
                <label for="status_pernikahan" class="block font-medium text-gray-700 mb-1">Status Pernikahan</label>
                <select name="status_pernikahan" id="status_pernikahan"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" required>
                    <option value="">Pilih Status</option>
                    <option value="Sudah Menikah" {{ old('status_pernikahan') == 'Sudah Menikah' ? 'selected' : '' }}>Sudah Menikah</option>
                    <option value="Belum Menikah" {{ old('status_pernikahan') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                </select>
                @error('status_pernikahan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Pasangan dan Anak --}}
            <div id="pasangan-anak-fields" style="display: none;">
                <div>
                    <label for="nama_pasangan" class="block font-medium text-gray-700 mb-1">Nama Pasangan</label>
                    <input type="text" name="nama_pasangan" id="nama_pasangan"
                        class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                        value="{{ old('nama_pasangan') }}">
                    @error('nama_pasangan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_anak" class="block font-medium text-gray-700 mb-1">Nama Anak</label>
                    <input type="text" name="nama_anak" id="nama_anak"
                        class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none"
                        value="{{ old('nama_anak') }}">
                    @error('nama_anak')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Role Akun --}}
            <div>
                <label for="role" class="block font-medium text-gray-700 mb-1">Role Akun</label>
                <select name="role" id="role"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none" required>
                    <option value="">Pilih Role</option>
                    @php
                        $roles = Auth::user()->role === 'staff_sd'
                            ? ['guru_sd', 'staff_sd', 'lembaga_sd']
                            : ['guru_smp', 'staff_smp', 'lembaga_smp'];
                    @endphp
                    @foreach ($roles as $r)
                        <option value="{{ $r }}" {{ old('role') == $r ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $r)) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Submit --}}
            <div class="text-center">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-full transition-all duration-300 shadow-md hover:shadow-xl">
                    Simpan Pegawai
                </button>
            </div>
        </form>
    </div>
</main>



{{-- JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('status_pernikahan');
        const pasanganAnakFields = document.getElementById('pasangan-anak-fields');

        function togglePasanganAnak() {
            if (statusSelect.value === 'Sudah Menikah') {
                pasanganAnakFields.style.display = 'block';
            } else {
                pasanganAnakFields.style.display = 'none';
            }
        }

        statusSelect.addEventListener('change', togglePasanganAnak);
        togglePasanganAnak(); // initial run
    });
</script>
@endsection
