<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Cetak Data Siswa</title>
    <style>
        /* Font modern dan clean */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 20px;
            line-height: 1.6;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-weight: 700;
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        thead tr {
            background-color: #3498db; /* biru cerah */
            color: white;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #dbe9ff; /* hover biru muda */
        }

        /* Responsive font size for smaller screens */
        @media print {
            a {
                display: none;
            }
            body {
                margin: 0;
                font-size: 12pt;
            }
            table {
                box-shadow: none;
                border-radius: 0;
                width: 100%;
            }
        }

        /* Cetak button style */
        .print-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            margin-top: 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 14px;
        }
        .print-button:hover {
            background-color: #2980b9;
        }

        /* Icon styling */
        .print-button svg {
            width: 18px;
            height: 18px;
            stroke: white;
            stroke-width: 2;
        }
    </style>
</head>
<body>
    <h1>Data Siswa</h1>
   <table class="min-w-full text-sm text-left text-gray-700">
    <thead class="bg-gray-100 font-semibold text-gray-700 sticky top-0">
        <tr>
            <th class="py-2 px-4 border-b border-gray-300">No</th>
            <th class="py-2 px-4 border-b border-gray-300">Foto</th>
            <th class="py-2 px-4 border-b border-gray-300">Nama</th>
            <th class="py-2 px-4 border-b border-gray-300">NISN</th>
            <th class="py-2 px-4 border-b border-gray-300">Tempat, Tgl Lahir</th>
            <th class="py-2 px-4 border-b border-gray-300">Kelas</th>
            <th class="py-2 px-4 border-b border-gray-300">NIK</th>
            <th class="py-2 px-4 border-b border-gray-300">Alamat</th>
            <th class="py-2 px-4 border-b border-gray-300">Asal Sekolah</th>
            <th class="py-2 px-4 border-b border-gray-300">Nama Ayah</th>
            <th class="py-2 px-4 border-b border-gray-300">Nama Ibu</th>
            <th class="py-2 px-4 border-b border-gray-300">Nama Wali</th>
            <th class="py-2 px-4 border-b border-gray-300">No KK</th>
            <th class="py-2 px-4 border-b border-gray-300">Berat Badan</th>
            <th class="py-2 px-4 border-b border-gray-300">Tinggi Badan</th>
            <th class="py-2 px-4 border-b border-gray-300">Lingkar Kepala</th>
            <th class="py-2 px-4 border-b border-gray-300">Jumlah Saudara Kandung</th>
            <th class="py-2 px-4 border-b border-gray-300">Jarak Rumah ke Sekolah</th>
            <th class="py-2 px-4 border-b border-gray-300">Lembaga</th>
            <th class="py-2 px-4 border-b border-gray-300">Status</th>
            <th class="py-2 px-4 border-b border-gray-300 text-center">Jumlah Mapel Diikuti</th>
            <th class="py-2 px-4 border-b border-gray-300 text-center">Jumlah Nilai</th>
        </tr>
    </thead>
    <tbody>
        @forelse($siswa as $index => $item)
            <tr class="hover:bg-green-50">
                <td class="py-2 px-3">{{ $index + 1 }}</td>
                <td class="py-2 px-3 text-center">
                    @if($item->foto)
                        <img src="{{ asset('storage/foto_siswa/' . $item->foto) }}"
                             alt="Foto {{ $item->nama_siswa }}"
                             class="w-12 h-12 object-cover rounded-full border border-green-400 mx-auto" />
                    @else
                        <img src="/user.png" alt="Default Foto"
                             class="w-12 h-12 object-cover rounded-full border border-green-400 mx-auto" />
                    @endif
                </td>
                <td class="py-2 px-3">{{ $item->nama_siswa }}</td>
                <td class="py-2 px-3">{{ $item->nisn }}</td>
                <td class="py-2 px-3">
                    {{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}
                </td>
                <td class="py-2 px-3">
                    @foreach ($item->regisMapelSiswas as $regis)
                        <div>{{ $regis->kelasMapel->kelas->nama_kelas ?? '-' }}</div>
                    @endforeach
                </td>
                <td class="py-2 px-3">{{ $item->nik }}</td>
                <td class="py-2 px-3">{{ $item->alamat }}</td>
                <td class="py-2 px-3">{{ $item->asal_sekolah }}</td>
                <td class="py-2 px-3">{{ $item->nama_ayah }}</td>
                <td class="py-2 px-3">{{ $item->nama_ibu }}</td>
                <td class="py-2 px-3">{{ $item->nama_wali }}</td>
                <td class="py-2 px-3">{{ $item->no_kk }}</td>
                <td class="py-2 px-3">{{ $item->berat_badan }}</td>
                <td class="py-2 px-3">{{ $item->tinggi_badan }}</td>
                <td class="py-2 px-3">{{ $item->lingkar_kepala }}</td>
                <td class="py-2 px-3">{{ $item->jumlah_saudara_kandung }}</td>
                <td class="py-2 px-3">{{ $item->jarak_rumah_ke_sekolah }}</td>
                <td class="py-2 px-3">{{ $item->lembaga }}</td>
                <td class="py-2 px-3">{{ ucfirst($item->status) }}</td>
                <td class="py-2 px-3 text-center">{{ $item->regisMapelSiswas->count() }}</td>
                <td class="py-2 px-3 text-center">
                    {{ $item->regisMapelSiswas->filter(fn($r) => $r->nilaiSiswa !== null)->count() }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="21" class="text-center py-4">Data siswa tidak ditemukan.</td>
            </tr>
        @endforelse
    </tbody>
</table>


    <p style="text-align:center;">
        <button onclick="window.print()" class="print-button" aria-label="Cetak Data Siswa">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-6 4v-8" />
            </svg>
            Cetak Data
        </button>
    </p>
</body>
</html>
