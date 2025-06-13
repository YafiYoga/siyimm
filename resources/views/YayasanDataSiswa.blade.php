@extends('layouts.MainYayasan')

@section('content')
<div class="px-6 py-8 font-[Verdana]">
    {{-- Header --}}
    <div class="flex items-center gap-2 mb-5">
        <h2 class="text-2xl font-bold text-gray-800">
            Daftar Siswa {{ strtoupper($jenjang) }}
        </h2>
        <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo" />
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-600 text-white rounded-md shadow flex items-center gap-2">
            <img src="/check.png" alt="Success" class="w-5 h-5" />
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter & Cetak --}}
    <form method="GET"
        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-gray-50 p-5 rounded-md shadow mb-10">
        {{-- Filter Kelas --}}
        <div>
            <label for="kelas" class="block mb-1 text-sm text-gray-600">Kelas</label>
            <select name="kelas" id="kelas"
                class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 text-sm">
                <option value="">Semua</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" @selected(request('kelas') == $kelas)>{{ $kelas }}</option>
                @endforeach
            </select>
        </div>

        {{-- Search Nama --}}
        <div class="relative md:col-span-2">
            <label for="search" class="block mb-1 text-sm text-gray-600">Cari Nama</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}"
                placeholder="Masukkan nama siswa..."
                class="pl-10 pr-4 py-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 text-sm w-full" />
            <div class="absolute left-3 top-9 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        {{-- Filter / Reset / Cetak --}}
        <div class="flex flex-col gap-2">
            <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded-md shadow hover:bg-green-700 text-sm">
                Filter
            </button>
            <a href="{{ route('yayasan.data_siswa', ['jenjang' => $jenjang]) }}"
                class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md shadow hover:bg-gray-400 text-sm text-center">
                Reset
            </a>
            <a href="{{ route('yayasan.cetak_siswa', ['jenjang' => $jenjang] + request()->query()) }}"
                target="_blank"
                class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700 text-sm text-center">
                Cetak Data
            </a>
        </div>
    </form>

    {{-- Tabel Siswa --}}
    @if($siswa->isEmpty())
        <div class="text-center py-32 text-gray-500 text-lg italic">
            Tidak ada data siswa.
        </div>
    @else
        <div class="overflow-x-auto font-[Verdana] text-sm">
            <table class="w-full min-w-max border border-gray-300 text-center">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="py-2 px-3">No</th>
                        <th class="py-2 px-3">Foto</th>
                        <th class="py-2 px-3">Nama</th>
                        <th class="py-2 px-3">NISN</th>
                        <th class="py-2 px-3">TTL</th>
                        <th class="py-2 px-3">Kelas</th>
                        <th class="py-2 px-3">NIK</th>
                        <th class="py-2 px-3">Alamat</th>
                        <th class="py-2 px-3">Asal Sekolah</th>
                        <th class="py-2 px-3">Nama Ayah</th>
                        <th class="py-2 px-3">Nama Ibu</th>
                        <th class="py-2 px-3">Nama Wali</th>
                        <th class="py-2 px-3">No KK</th>
                        <th class="py-2 px-3">Berat</th>
                        <th class="py-2 px-3">Tinggi</th>
                        <th class="py-2 px-3">Lingkar Kepala</th>
                        <th class="py-2 px-3">Jumlah Saudara</th>
                        <th class="py-2 px-3">Jarak ke Sekolah</th>
                        <th class="py-2 px-3">Status</th>
                        <th class="py-2 px-3"># Mapel</th>
                        <th class="py-2 px-3"># Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswa as $i => $item)
                        <tr class="hover:bg-green-50">
                            <td class="py-2 px-3">{{ $i + 1 }}</td>
                            <td class="py-2 px-3">
                                <img src="{{ $item->foto ? asset('storage/foto_siswa/' . $item->foto) : '/user.png' }}"
                                    alt="Foto {{ $item->nama_siswa }}"
                                    class="w-12 h-12 rounded-full border border-green-400 mx-auto object-cover" />
                            </td>
                            <td class="py-2 px-3">{{ $item->nama_siswa }}</td>
                            <td class="py-2 px-3">{{ $item->nisn }}</td>
                            <td class="py-2 px-3">{{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                            <td class="py-2 px-3">
                                {{ $item->regisMapelSiswas->first()?->kelasMapel?->kelas?->nama_kelas ?? '-' }}
                            </td>
                            <td class="py-2 px-3">{{ $item->nik }}</td>
                            <td class="py-2 px-3">{{ $item->alamat }}</td>
                            <td class="py-2 px-3">{{ $item->asal_sekolah }}</td>
                            <td class="py-2 px-3">{{ $item->nama_ayah }}</td>
                            <td class="py-2 px-3">{{ $item->nama_ibu }}</td>
                            <td class="py-2 px-3">{{ $item->nama_wali }}</td>
                            <td class="py-2 px-3">{{ $item->no_kk }}</td>
                            <td class="py-2 px-3">{{ $item->berat_badan }} kg</td>
                            <td class="py-2 px-3">{{ $item->tinggi_badan }} cm</td>
                            <td class="py-2 px-3">{{ $item->lingkar_kepala }} cm</td>
                            <td class="py-2 px-3">{{ $item->jumlah_saudara_kandung }}</td>
                            <td class="py-2 px-3">{{ $item->jarak_rumah_ke_sekolah }} km</td>
                            <td class="py-2 px-3">{{ $item->status }}</td>
                            <td class="py-2 px-3">{{ $item->regisMapelSiswas->count() }}</td>
                            <td class="py-2 px-3">{{ $item->regisMapelSiswas->flatMap->nilaiSiswa->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
