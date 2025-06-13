<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Cetak Rekap Hafalan Quran</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            color: #333;
            margin: 20px;
            line-height: 1.6;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 8px;
        }

        h1 {
            color: #2c3e50;
            font-weight: 700;
            font-size: 24px;
        }

        h2 {
            font-size: 18px;
            font-weight: 600;
            color: #555;
        }

        .info {
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
            color: #444;
        }

        .siswa-section {
            margin-bottom: 50px;
            page-break-after: always;
        }

        .siswa-header h3 {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 4px;
        }

        .siswa-header p {
            font-size: 13px;
            color: #555;
            margin: 3px 0;
        }

        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 10px;
        }

        thead tr {
            background-color: #3498db;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        th, td {
            padding: 10px 14px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #dbe9ff;
        }

        .no-data {
            font-style: italic;
            color: #999;
            margin-top: 10px;
        }

        @media print {
            body {
                margin: 0;
                font-size: 12pt;
            }

            .no-print {
                display: none !important;
            }

            table {
                box-shadow: none;
                border-radius: 0;
            }
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 40px;
            color: #555;
        }

        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-button button {
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }

        .print-button button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak -->
    <div class="print-button no-print">
        <button onclick="window.print()">Cetak PDF</button>
    </div>

    <!-- Judul -->
    <h1>Rekap Hafalan Quran</h1>
<h2>{{ $lembaga ?? '-' }}</h2>

<!-- Info Kelas & Semester -->
<div class="info">
    <strong>Kelas:</strong> {{ $kelas ?? '-' }} &nbsp;|&nbsp;
    <strong>Tahun Ajaran:</strong> {{ $tahun_ajaran ?? '-' }} &nbsp;|&nbsp;
    <strong>Semester:</strong> {{ $semester ?? '-' }}
</div>


    <!-- Rekap per Siswa -->
    @forelse ($rekapHafalan as $item)
        <div class="siswa-section">
            <div class="siswa-header">
                <h3>{{ $item['nama_siswa'] }}</h3>
                <p>
                    <strong>Kelas:</strong> {{ $item['kelas'] }} &nbsp;|&nbsp;
                    <strong>Total Hafalan:</strong> {{ $item['total_hafalan'] }} &nbsp;|&nbsp;
                    <strong>Terakhir Setor:</strong> {{ $item['tanggal_setor'] ?? '-' }}
                </p>
            </div>

            @if (count($item['detail_hafalan']) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Surat</th>
                            <th>Ayat Dari</th>
                            <th>Ayat Sampai</th>
                            <th>Tanggal Setor</th>
                            <th>Guru</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item['detail_hafalan'] as $i => $detail)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $detail['nama_surat'] }}</td>
                                <td>{{ $detail['ayat_dari'] }}</td>
                                <td>{{ $detail['ayat_sampai'] }}</td>
                                <td>{{ $detail['tgl_setor'] }}</td>
                                <td>{{ $detail['guru'] }}</td>
                                <td>{{ $detail['keterangan'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">Belum ada hafalan yang disetor.</p>
            @endif
        </div>
    @empty
        <p class="no-data">Tidak ada data hafalan yang ditemukan untuk filter ini.</p>
    @endforelse

    <!-- Footer -->
    <div class="no-print footer">
        Dicetak pada {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}
    </div>

</body>
</html>
