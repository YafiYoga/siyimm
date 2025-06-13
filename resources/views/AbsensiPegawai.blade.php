@extends('layouts.MainStaff')

@section('content')
@include('layouts.Header')
@if(in_array(Auth::user()->role, ['staff_sd', 'staff_smp']))
    @include('layouts.SidebarStaff')
@elseif(in_array(Auth::user()->role, ['guru_sd', 'guru_smp']))
    @include('layouts.SidebarGuru')
@elseif(Auth::user()->role === 'admin')
    @include('layouts.SidebarAdmin')
@elseif(in_array(Auth::user()->role, ['lembaga_sd', 'lembaga_smp']))
    @include('layouts.SidebarLembaga')
@endif

<div class="ml-64 p-8 min-h-screen bg-gray-50 font-[Verdana] mt-20">
    <h1 class="text-4xl font-bold text-green-700 mb-8 text-center">Absensi Pegawai</h1>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-4xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Absensi Hari Ini <span class="block text-sm text-gray-500">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        </h2>

        <div class="flex flex-col md:flex-row justify-center gap-6 mb-8">
            <!-- Absen Masuk -->
            <form action="{{ route('absensi.masuk') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="w-full md:w-auto px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-md
                           {{ ($absensiToday && $absensiToday->waktu_masuk) ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700 text-white' }}"
                    @if($absensiToday && $absensiToday->waktu_masuk) disabled @endif
                >
                    {{ $absensiToday && $absensiToday->waktu_masuk ? 'Sudah Absen Masuk' : 'Absen Masuk' }}
                </button>
            </form>

            <!-- Absen Keluar -->
            <form action="{{ route('absensi.keluar') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="w-full md:w-auto px-6 py-3 rounded-xl font-semibold transition-all duration-300 shadow-md
                           {{ (!$absensiToday || !$absensiToday->waktu_masuk || $absensiToday->waktu_keluar) ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-red-600 hover:bg-red-700 text-white' }}"
                    @if(!$absensiToday || !$absensiToday->waktu_masuk || $absensiToday->waktu_keluar) disabled @endif
                >
                    {{ $absensiToday && $absensiToday->waktu_keluar ? 'Sudah Absen Keluar' : 'Absen Keluar' }}
                </button>
            </form>
        </div>

        <hr class="my-6 border-gray-300">

        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Riwayat Absensi</h2>

        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Waktu Masuk</th>
                        <th class="px-4 py-3 text-left">Waktu Keluar</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiAll as $absensi)
                        <tr class="bg-white border-t hover:bg-green-50 transition-all">
                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $absensi->waktu_masuk ? \Carbon\Carbon::parse($absensi->waktu_masuk)->format('H:i:s') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $absensi->waktu_keluar ? \Carbon\Carbon::parse($absensi->waktu_keluar)->format('H:i:s') : '-' }}
                            </td>
                            <td class="px-4 py-3 capitalize">
                                @if($absensi->status == 'hadir')
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">Hadir</span>
                                @elseif($absensi->status == 'tidakhadir')
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">Tidak Hadir</span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-sm font-medium rounded-full">Terlambat</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">Belum ada data absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center">
            {{ $absensiAll->links() }}
        </div>
    </div>
</div>


@endsection
