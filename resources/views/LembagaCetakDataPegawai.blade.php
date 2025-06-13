<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Cetak Data Pegawai</title>
    <style>
        /* Font dan Reset */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            margin: 20px;
            background-color: #f9fafb;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2f855a;
            margin-bottom: 20px;
            font-weight: 700;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        thead {
            background-color: #38a169;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 13px;
        }

        tbody tr:hover {
            background-color: #c6f6d5;
            transition: background-color 0.3s ease;
            cursor: default;
        }

        /* Tombol Cetak */
        .print-btn {
            display: inline-block;
            margin: 30px auto 0;
            padding: 10px 25px;
            background-color: #38a169;
            color: white;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgb(56 161 105 / 0.4);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
        }

        .print-btn:hover {
            background-color: #2f855a;
            box-shadow: 0 6px 10px rgb(47 133 90 / 0.6);
        }

        .print-btn:active {
            background-color: #276749;
            box-shadow: none;
        }

        /* Center tombol */
        .print-container {
            text-align: center;
        }

        /* Hilangkan link saat print */
        @media print {
            .print-container {
                display: none;
            }

            body {
                background-color: white;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <h1>Data Pegawai</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Lengkap</th>
                <th>NIY</th>
                <th>Unit Kerja</th>
                <th>Jenis Kelamin</th>
                <th>Tugas Pokok</th>
                <th>Status Pernikahan</th>
                <!-- Tambahkan kolom lain sesuai kebutuhan -->
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawai as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_lengkap }}</td>
                    <td>{{ $item->niy }}</td>
                    <td>{{ $item->unit_kerja }}</td>
                    <td>{{ $item->jenis_kelamin }}</td>
                    <td>{{ $item->tugas_pokok }}</td>
                    <td>{{ $item->status_pernikahan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="print-container">
        <a href="#" class="print-btn" onclick="window.print();return false;">Klik untuk Cetak</a>
    </div>
</body>

</html>
