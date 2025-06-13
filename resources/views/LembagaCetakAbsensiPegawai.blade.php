<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>
        Cetak Rekap Absensi Pegawai - 
        {{ $tahun }} 
        {{ ($bulan && is_numeric($bulan)) ? \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') : '' }}
    </title>
    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin: 20px;
            color: #064e3b; /* hijau gelap */
        }
        h1, h2, h3 {
            margin-bottom: 0.25em;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1.5em;
        }
        th, td {
            border: 1px solid #4ade80; /* hijau terang */
            padding: 6px 10px;
            text-align: left;
        }
        th {
            background-color: #bbf7d0; /* hijau muda */
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
        @media print {
            body {
                margin: 0.5cm;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <h1>Rekap Absensi Pegawai</h1>
    <h2>
        Periode: {{ $tahun }} 
        {{ ($bulan && is_numeric($bulan)) ? \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') : 'Semua Bulan' }}
    </h2>
    <hr>

    @if(empty($grouped))
        <p>Tidak ada data absensi untuk periode ini.</p>
    @else
        @foreach($grouped as $tahun => $bulanGroup)
            <section>
                <h2>Tahun: {{ $tahun }}</h2>
                @foreach($bulanGroup as $bulan => $tanggalGroup)
                    <article style="margin-left: 20px;">
                        <h3>
                            Bulan: 
                            {{ ($bulan && is_numeric($bulan)) ? \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') : $bulan }} 
                            ({{ collect($tanggalGroup)->flatten()->count() }} absensi)
                        </h3>

                        @foreach($tanggalGroup as $tanggal => $absensis)
                            <div style="margin-left: 20px; margin-bottom: 1em;">
                                <h4>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }} ({{ count($absensis) }} absensi)</h4>
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 30px;">#</th>
                                            <th>Nama Pegawai</th>
                                            <th class="text-center" style="width: 80px;">NIY</th>
                                            <th>Unit Kerja</th>
                                            <th class="text-center" style="width: 140px;">Waktu Masuk</th>
                                            <th class="text-center" style="width: 140px;">Waktu Keluar</th>
                                            <th class="text-center" style="width: 90px;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($absensis as $index => $absen)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $absen->pegawai->nama_lengkap }}</td>
                                                <td class="text-center">{{ $absen->pegawai->niy }}</td>
                                                <td>{{ $absen->pegawai->unit_kerja }}</td>
                                                <td class="text-center">
                                                    {{ $absen->waktu_masuk ? \Carbon\Carbon::parse($absen->waktu_masuk)->translatedFormat('d F Y H:i') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $absen->waktu_keluar ? \Carbon\Carbon::parse($absen->waktu_keluar)->translatedFormat('d F Y H:i') : '-' }}
                                                </td>
                                                <td class="text-center">{{ ucfirst($absen->status) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </article>
                @endforeach
                <div class="page-break"></div>
            </section>
        @endforeach
    @endif

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
