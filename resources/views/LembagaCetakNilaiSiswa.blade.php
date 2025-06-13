<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Cetak Nilai Siswa - Yayasan Insan Madani Mulia</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px 30px;
            color: #222;
            background: #fff;
        }
        h1, h2, h3, h4 {
            margin: 0 0 8px 0;
            font-weight: 600;
            color: #2c3e50; /* abu gelap modern */
        }
        h1 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }
        h2 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
            color: #34495e;
        }
        h4 {
            font-size: 14px;
            font-weight: 600;
            color: #7f8c8d;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Section titles */
        .section-header {
            font-size: 16px;
            font-weight: 700;
            color: #2980b9; /* biru lembut */
            border-bottom: 2px solid #2980b9;
            padding-bottom: 4px;
            margin-top: 28px;
            margin-bottom: 12px;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 0 5px rgba(0,0,0,0.05);
            font-size: 14px;
        }
        th, td {
            padding: 10px 12px;
            border: 1px solid #bdc3c7;
            text-align: center;
            color: #2c3e50;
        }
        th {
            background-color: #ecf0f1;
            font-weight: 700;
            letter-spacing: 0.05em;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fbfc;
        }
        tbody tr:hover {
            background-color: #d6eaf8;
        }
        td.name-column {
            text-align: left;
            padding-left: 15px;
            font-weight: 600;
            color: #34495e;
        }

        /* Button */
        button.no-print {
            display: inline-block;
            margin: 30px auto 10px auto;
            padding: 12px 28px;
            background-color: #2980b9;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: block;
        }
        button.no-print:hover {
            background-color: #1c5980;
        }

        /* Print adjustments */
        @media print {
            button.no-print { display: none; }
            body {
                margin: 0;
                font-size: 12pt;
                color: #000;
                background: #fff;
            }
            .section-header {
                color: #000;
                border-color: #000;
            }
            table, th, td {
                border-color: #000;
            }
            tbody tr:hover {
                background-color: transparent;
            }
        }
    </style>
</head>
<body>

    <h1>Yayasan Insan Madani Mulia</h1>
    <h2>Monitoring Nilai Siswa</h2>
    <h4>ID Lembaga: {{ $id }}</h4>

    @foreach($groupedData as $ta => $semesters)
        <div class="section-header">Tahun Ajaran: {{ $ta }}</div>
        @foreach($semesters as $semester => $kelasList)
            <div class="section-header" style="margin-left: 0;">Semester: {{ $semester }}</div>
            @foreach($kelasList as $kelas => $mapels)
                <div class="section-header" style="margin-left: 0;">Kelas: {{ $kelas }}</div>
                @foreach($mapels as $mapel => $siswaList)
                    <div class="section-header" style="margin-left: 0;">Mata Pelajaran: {{ $mapel }}</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 50%;">Nama</th>
                                <th style="width: 15%;">Tugas</th>
                                <th style="width: 15%;">UTS</th>
                                <th style="width: 15%;">UAS</th>
                                <th style="width: 15%;">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswaList as $i => $siswa)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="name-column">{{ $siswa['nama_siswa'] }}</td>
                                    <td>{{ $siswa['nilai_tugas'] ?? '-' }}</td>
                                    <td>{{ $siswa['nilai_uts'] ?? '-' }}</td>
                                    <td>{{ $siswa['nilai_uas'] ?? '-' }}</td>
                                    <td>{{ $siswa['nilai_akhir'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @endforeach
        @endforeach
    @endforeach

    <button onclick="window.print()" class="no-print">Cetak</button>
    
</body>
</html>
