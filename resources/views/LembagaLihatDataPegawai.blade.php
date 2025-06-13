@extends('layouts.MainLembaga')

@section('content')
    <div>

        <div class="flex items-center gap-2 mb-5">
            <h2 class="text-2xl font-bold text-gray-800">Data Pegawai</h2>
            <img src="/SIYIMM.png" class="h-10" alt="SYIMM Logo" />
        </div>

        @if (session('success'))
            <div
                class="mb-4 p-4 bg-green-600 text-white border border-green-700 rounded-md shadow-md flex items-center gap-2">
                <img src="/check.png" alt="Success" class="w-5 h-5" />
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('lembaga.data_pegawai') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end bg-gray-50 p-5 rounded-md shadow border border-gray-200 mb-10">
            {{-- Search --}}
            <div class="relative">
                <label class="block mb-1 text-sm text-gray-600">Cari Pegawai</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau NIY..."
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm w-full" />
                <div class="absolute left-3 top-9 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </div>
            </div>

            {{-- Tugas Pokok --}}
            <div class="relative">
                <label class="block mb-1 text-sm text-gray-600">Tugas Pokok</label>
                <select name="tugas_pokok"
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 w-full">
                    <option value="">Pilih Tugas</option>
                    @foreach ($tugasKepegawaianOptions as $tugas)
                        <option value="{{ $tugas }}" {{ request('tugas_pokok') == $tugas ? 'selected' : '' }}>
                            {{ $tugas }}</option>
                    @endforeach
                </select>
                <div class="absolute left-3 top-9 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13v6a1 1 0 01-2 0v-6L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors duration-300 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13v6a1 1 0 01-2 0v-6L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('lembaga.data_pegawai') }}"
                    class="flex-1 flex items-center justify-center gap-2 bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 transition-colors duration-300 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset
                </a>
                <a href="{{ route('lembaga.cetak_pegawai', request()->query()) }}" target="_blank"
        class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors duration-300 shadow-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-5a2 2 0 00-2-2h-2M7 17H5a2 2 0 01-2-2v-5a2 2 0 012-2h2m10 7v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5m3-7v4" />
        </svg>
        Cetak Data
    </a>
            </div>
        </form>

        {{-- No Results Message --}}
        @if ($noResults ?? false)
            <div
                class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-5 mb-6 rounded-lg text-center font-semibold">
                <p>Mohon Maaf, data yang kamu cari tidak ditemukan.</p>
            </div>
        @endif

        {{-- Data Table --}}
        <div class="overflow-x-auto font-[Verdana] text-sm">
            <table class="w-full min-w-max border border-gray-300 text-center">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="py-2 px-3">#</th>
                        <th class="py-2 px-3">Foto</th>
                        <th class="py-2 px-3">Nama Lengkap</th>
                        <th class="py-2 px-3">Nama Panggilan</th>
                        <th class="py-2 px-3">NIY</th>
                        <th class="py-2 px-3">Unit Kerja</th>
                        <th class="py-2 px-3">Jenis Kelamin</th>
                        <th class="py-2 px-3">Tempat, Tanggal Lahir</th>
                        <th class="py-2 px-3">Alamat</th>
                        <th class="py-2 px-3">No Telepon</th>
                        <th class="py-2 px-3">Email</th>
                        <th class="py-2 px-3">TMT</th>
                        <th class="py-2 px-3">Tugas Kepegawaian</th>
                        <th class="py-2 px-3">Tugas Pokok</th>
                        <th class="py-2 px-3">Status Pernikahan</th>
                        <th class="py-2 px-3">Nama Pasangan</th>
                        <th class="py-2 px-3">Nama Anak</th>
                        <th class="py-2 px-3">Nama Ayah</th>
                        <th class="py-2 px-3">Nama Ibu</th>
                        <th class="py-2 px-3">Pendidikan Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawai as $index => $item)
                        <tr class="hover:bg-green-50">
                            <td class="py-2 px-3">{{ $index + 1 }}</td>
                            <td class="py-2 px-3">
                                <img src="{{ $item->foto ? asset('storage/' . $item->foto) : '/user.png' }}" alt="Foto Pegawai"
                                    class="w-12 h-12 object-cover rounded-full border border-green-400 mx-auto" />
                            </td>
                            <td class="py-2 px-3">{{ $item->nama_lengkap ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->nama_panggilan ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->niy ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->unit_kerja ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->jenis_kelamin ?? '-' }}</td>
                            <td class="py-2 px-3">
                                {{ $item->tempat_lahir ?? '-' }},
                                {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                            </td>
                            <td class="py-2 px-3">{{ $item->alamat ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->no_telepon ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->email ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->tmt ? \Carbon\Carbon::parse($item->tmt)->translatedFormat('d F Y') : '-' }}</td>
                            <td class="py-2 px-3">{{ $item->tugas_kepegawaian ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->tugas_pokok ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->status_pernikahan ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->nama_pasangan ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->nama_anak ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->nama_ayah ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->nama_ibu ?? '-' }}</td>
                            <td class="py-2 px-3">{{ $item->pendidikan_terakhir ?? '-' }}</td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="21" class="text-center py-4 text-gray-500">Data Pegawai tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-5">
            {{ $pegawai->withQueryString()->links() }}
        </div>

    </div>
@endsection
