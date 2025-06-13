<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Cetak Rekap Absensi Siswa</title>
    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 1rem;
        }
        h3 {
            color: #065f46;
            margin-top: 2rem;
            margin-bottom: 0.5rem;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 2rem;
        }
        th, td {
            border: 1px solid #666;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #d1fae5; /* mirip bg-emerald-100 */
            color: #065f46; /* mirip text-emerald-900 */
        }
        .kelas-title {
            font-weight: bold;
            color: #065f46;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>

    <h2>
        {{ auth()->user()->role == 'lembaga_sd' ? 'Rekap Absensi Siswa SD' : 'Rekap Absensi Siswa SMP' }}
    </h2>

    @forelse($rekapAbsensi as $tahunAjaran => $dataTahun)
        <h3>Tahun Ajaran: {{ $tahunAjaran }}</h3>

        @forelse($dataTahun as $namaKelas => $dataKelas)
            <div class="kelas-title">Kelas {{ $namaKelas }}</div>

            @forelse($dataKelas as $namaSiswa => $dataSiswa)
                <div><strong>{{ $loop->iteration }}. {{ $namaSiswa }}</strong></div>
                <table>
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Hadir</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                            <th>Alpha</th>
                            <th>Terlambat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataSiswa['mapel'] as $namaMapel => $rekap)
                            <tr>
                                <td>{{ $namaMapel }}</td>
                                <td>{{ $rekap['hadir'] }}</td>
                                <td>{{ $rekap['izin'] }}</td>
                                <td>{{ $rekap['sakit'] }}</td>
                                <td>{{ $rekap['alpha'] }}</td>
                                <td>{{ $rekap['terlambat'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @empty
                <p><em>Tidak ada data siswa untuk kelas ini.</em></p>
            @endforelse
        @empty
            <p><em>Belum ada data kelas pada tahun ajaran ini.</em></p>
        @endforelse

    @empty
        <p><em>Belum ada data absensi siswa.</em></p>
    @endforelse

    <script>
        // Otomatis print saat halaman terbuka
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>
