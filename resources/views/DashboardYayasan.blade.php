@extends('layouts.MainYayasan')

@section('content')
@include('layouts.Header')
@include('layouts.SidebarYayasan')

<div class="px-6 py-8 font-[Verdana]">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center gap-3 mb-8">
        <h2 class="text-2xl font-bold text-emerald-600">Dashboard Yayasan</h2>
    </div>

    {{-- Statistik Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white border-l-8 border-blue-500 shadow-md rounded-lg p-6 flex items-center">
            <div class="flex-grow">
                <p class="text-sm text-gray-500">Total Siswa SD</p>
                <h3 class="text-2xl font-bold text-blue-600">{{ $totalSiswaSD }}</h3>
            </div>
            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <div class="bg-white border-l-8 border-green-500 shadow-md rounded-lg p-6 flex items-center">
            <div class="flex-grow">
                <p class="text-sm text-gray-500">Total Siswa SMP</p>
                <h3 class="text-2xl font-bold text-green-600">{{ $totalSiswaSMP }}</h3>
            </div>
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
    </div>

    {{-- Statistik Absensi Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white border-l-8 border-indigo-500 shadow-md rounded-lg p-6 flex items-center">
            <div class="flex-grow">
                <p class="text-sm text-gray-500">Absensi Siswa SD</p>
                <h3 class="text-2xl font-bold text-indigo-600">{{ $totalAbsensiSDSiswa }}</h3>
            </div>
            <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M8 7V3m8 4V3m-9 8h10m-6 4h.01M6 21h12a2 2..."></path>
            </svg>
        </div>
        <div class="bg-white border-l-8 border-purple-500 shadow-md rounded-lg p-6 flex items-center">
            <div class="flex-grow">
                <p class="text-sm text-gray-500">Absensi Siswa SMP</p>
                <h3 class="text-2xl font-bold text-purple-600">{{ $totalAbsensiSMPSiswa }}</h3>
            </div>
            <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M8 7V3m8 4V3m-9 8h10m-6 4h.01M6 21h12a2 2..."></path>
            </svg>
        </div>
    </div>

    {{-- Grafik Perbandingan --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">Perbandingan Siswa & Absensi</h3>
        <canvas id="yayasanChart" class="w-full h-64"></canvas>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('yayasanChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Siswa SD', 'Siswa SMP', 'Absensi SD', 'Absensi SMP'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    {{ $totalSiswaSD }}, 
                    {{ $totalSiswaSMP }}, 
                    {{ $totalAbsensiSDSiswa }}, 
                    {{ $totalAbsensiSMPSiswa }}
                ],
                backgroundColor: ['#3B82F6', '#10B981', '#6366F1', '#8B5CF6']
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
</script>
@endsection
