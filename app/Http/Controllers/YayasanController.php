<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Pegawai;
use App\Models\AbsensiSiswa;
use App\Models\NilaiSiswa;
use App\Models\HafalanQuranSiswa;
use App\Models\AbsensiPegawai;
use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAjaran;
use App\Models\RegisMapelSiswa;
use App\Models\KelasMapel;
  use Illuminate\Support\Collection;


class YayasanController extends Controller
{
public function dataSiswa(Request $request, $jenjang)
{
    $user = Auth::user();
    if ($user->role !== 'yayasan') {
        abort(403, 'Unauthorized access');
    }

    $jenjang = strtolower($jenjang);
    $lembagaMap = [
        'sd' => Siswa::LEMBAGA_SD,
        'smp' => Siswa::LEMBAGA_SMP,
    ];

    if (!isset($lembagaMap[$jenjang])) {
        abort(404, 'Jenjang tidak valid');
    }

    $lembaga = $lembagaMap[$jenjang];

    // Ambil tahun ajaran aktif berdasarkan jenjang
    $tahunAjaranAktif = TahunAjaran::aktif()
        ->where('jenjang', strtoupper($jenjang))
        ->first();

    // Ambil daftar kelas yang relevan
    $kelasList = Kelas::whereHas('kelasMapels', function ($query) use ($tahunAjaranAktif) {
        if ($tahunAjaranAktif) {
            $query->where('id_tahun_ajaran', $tahunAjaranAktif->id);
        }
    })
    ->whereHas('kelasMapels.regisMapelSiswas.siswa', function ($q) use ($lembaga) {
        $q->where('lembaga', $lembaga);
    })
    ->pluck('nama_kelas')
    ->unique()
    ->sort()
    ->values();

    $query = Siswa::with([
        'regisMapelSiswas.kelasMapel.kelas',
        'regisMapelSiswas.kelasMapel.mapel',
        'regisMapelSiswas.kelasMapel.guru',
        'regisMapelSiswas.kelasMapel.tahunAjaran',
        'regisMapelSiswas.nilaiSiswa',
    ])->where('lembaga', $lembaga);

    // Filter berdasarkan kelas jika ada
    if ($request->filled('kelas')) {
        $kelasFilter = $request->kelas;

        $query->whereHas('regisMapelSiswas.kelasMapel', function ($q) use ($kelasFilter, $tahunAjaranAktif) {
            $q->whereHas('kelas', function ($q2) use ($kelasFilter) {
                $q2->where('nama_kelas', $kelasFilter);
            });

            if ($tahunAjaranAktif) {
                $q->where('id_tahun_ajaran', $tahunAjaranAktif->id);
            }
        });
    } else {
        // Jika tidak filter kelas, tetap filter tahun ajaran aktif
        if ($tahunAjaranAktif) {
            $query->whereHas('regisMapelSiswas.kelasMapel', function ($q) use ($tahunAjaranAktif) {
                $q->where('id_tahun_ajaran', $tahunAjaranAktif->id);
            });
        }
    }

    // Filter berdasarkan nama siswa
    if ($request->filled('search')) {
        $query->where('nama_siswa', 'like', '%' . $request->search . '%');
    }

    $siswa = $query->get();

    return view('YayasanDataSiswa', compact('siswa', 'kelasList', 'jenjang'));
}




public function cetakDataSiswa(Request $request, $jenjang)
{
    $user = Auth::user();
    if ($user->role !== 'yayasan') {
        abort(403, 'Unauthorized access');
    }

    // Mapping jenjang ke lembaga
    $jenjang = strtolower($jenjang);
    $lembagaMap = [
        'sd' => Siswa::LEMBAGA_SD,
        'smp' => Siswa::LEMBAGA_SMP,
    ];

    if (!array_key_exists($jenjang, $lembagaMap)) {
        abort(404, 'Jenjang tidak valid');
    }

    $lembaga = $lembagaMap[$jenjang];

    $tahunAjaranAktif = TahunAjaran::aktif()->where('jenjang', strtoupper($jenjang))->get();

    $query = Siswa::with([
        'regisMapelSiswas.kelasMapel.kelas',
        'regisMapelSiswas.kelasMapel.mapel',
        'regisMapelSiswas.kelasMapel.guru',
        'regisMapelSiswas.kelasMapel.tahunAjaran',
        'regisMapelSiswas.nilaiSiswa',
    ])->where('lembaga', $lembaga);

    if ($request->filled('lembaga')) {
        $query->where('lembaga', $request->lembaga);
    }

    if ($request->filled('kelas')) {
        $kelasFilter = $request->kelas;
        $query->whereHas('regisMapelSiswas.kelasMapel.kelas', function ($q) use ($kelasFilter) {
            $q->where('nama_kelas', $kelasFilter);
        })->whereHas('regisMapelSiswas.kelasMapel', function ($q) use ($tahunAjaranAktif) {
            $q->whereIn('id_tahun_ajaran', $tahunAjaranAktif->pluck('id'));
        });
    } else {
        $query->whereHas('regisMapelSiswas.kelasMapel', function ($q) use ($tahunAjaranAktif) {
            $q->whereIn('id_tahun_ajaran', $tahunAjaranAktif->pluck('id'));
        });
    }

    if ($request->filled('search')) {
        $query->where('nama_siswa', 'like', '%' . $request->search . '%');
    }

    $siswa = $query->get();

    return view('yayasanCetakDataSiswa', compact('siswa', 'jenjang'));
}

public function showNilai(Request $request, $jenjang)
{
    // Validasi jenjang
    if (!in_array($jenjang, ['sd', 'smp'])) {
        abort(404, 'Jenjang tidak ditemukan');
    }

    // Tentukan lembaga dari parameter jenjang
    $lembaga = match($jenjang) {
        'sd' => Siswa::LEMBAGA_SD,
        'smp' => Siswa::LEMBAGA_SMP,
    };

    // Ambil filter dari query parameter
    $filterTA = $request->query('tahun_ajaran');
    $filterSemester = $request->query('semester');
    $filterKelas = $request->query('kelas');
    $filterMapel = $request->query('mapel');

    // Ambil data siswa dengan relasi
    $siswaList = Siswa::with([
        'regisMapelSiswas.nilaiSiswa',
        'regisMapelSiswas.kelasMapel.kelas',
        'regisMapelSiswas.kelasMapel.mapel',
        'regisMapelSiswas.kelasMapel.guru',
        'regisMapelSiswas.kelasMapel.tahunAjaran'
    ])
    ->where('lembaga', $lembaga)
    ->get();

    $groupedData = [];

    foreach ($siswaList as $s) {
        foreach ($s->regisMapelSiswas as $regis) {
            if (
                !$regis->kelasMapel ||
                !$regis->kelasMapel->tahunAjaran ||
                !$regis->kelasMapel->kelas ||
                !$regis->kelasMapel->mapel
            ) {
                continue;
            }

            $ta = $regis->kelasMapel->tahunAjaran->tahun_ajaran;
            $semester = $regis->kelasMapel->tahunAjaran->semester;
            $kelas = $regis->kelasMapel->kelas->nama_kelas;
            $mapel = $regis->kelasMapel->mapel->nama_mapel;

            if ($filterTA && strtolower(trim($filterTA)) !== strtolower(trim($ta))) continue;
            if ($filterSemester && strtolower(trim($filterSemester)) !== strtolower(trim($semester))) continue;
            if ($filterKelas && strtolower(trim($filterKelas)) !== strtolower(trim($kelas))) continue;
            if ($filterMapel && strtolower(trim($filterMapel)) !== strtolower(trim($mapel))) continue;

            $nilai = $regis->nilaiSiswa;

            $groupedData[$ta][$semester][$kelas][$mapel][] = [
                'nama_siswa' => $s->nama_siswa,
                'nilai_tugas' => $nilai->nilai_tugas ?? null,
                'nilai_uts' => $nilai->nilai_uts ?? null,
                'nilai_uas' => $nilai->nilai_uas ?? null,
                'nilai_akhir' => $nilai->nilai_akhir ?? null,
            ];
        }
    }

    // Ambil list filter berdasarkan jenjang (lembaga)
    $tahunAjaranList = TahunAjaran::whereHas('kelasMapel', function ($query) use ($lembaga) {
        $query->whereHas('kelas', function ($q) use ($lembaga) {
            $q->where('jenjang', $lembaga);
        });
    })->pluck('tahun_ajaran')->unique()->toArray();

    $kelasList = Kelas::where('jenjang', $lembaga)
        ->pluck('nama_kelas')->unique()->toArray();

    $mapelList = Mapel::whereHas('kelasMapel', function ($query) use ($lembaga) {
        $query->whereHas('kelas', function ($q) use ($lembaga) {
            $q->where('jenjang', $lembaga);
        });
    })->pluck('nama_mapel')->unique()->toArray();

    return view('YayasanNilaiSiswa', [
        'groupedData' => $groupedData,
        'tahunAjaranList' => $tahunAjaranList,
        'kelasList' => $kelasList,
        'mapelList' => $mapelList,
        'filterTA' => $filterTA,
        'filterSemester' => $filterSemester,
        'filterKelas' => $filterKelas,
        'filterMapel' => $filterMapel,
        'lembaga' => $lembaga,
        'jenjang' => $jenjang, // kirim ke view
    ]);
}

public function cetakNilai($jenjang, Request $request)
{
    if (!in_array($jenjang, ['sd', 'smp'])) {
        abort(404, 'Jenjang tidak ditemukan');
    }

    $tahunAjaran = $request->input('tahun_ajaran');
    $semester = $request->input('semester');
    $kelas = $request->input('kelas');
    $mapel = $request->input('mapel');

    $data = $this->ambilDataNilaiSiswa($jenjang, $tahunAjaran, $semester, $kelas, $mapel);

    return view('LembagaCetakNilaiSiswa', [
        'groupedData' => $data,
        'jenjang' => $jenjang,
    ]);
}

public function ambilDataNilaiSiswa($jenjang, $tahunAjaran = null, $semester = null, $kelas = null, $mapel = null)
{
    if (!in_array($jenjang, ['sd', 'smp'])) {
        abort(404, 'Jenjang tidak ditemukan');
    }

    $lembaga = match($jenjang) {
        'sd' => Siswa::LEMBAGA_SD,
        'smp' => Siswa::LEMBAGA_SMP,
    };

    $siswaList = Siswa::with([
        'regisMapelSiswas.nilaiSiswa',
        'regisMapelSiswas.kelasMapel.kelas',
        'regisMapelSiswas.kelasMapel.mapel',
        'regisMapelSiswas.kelasMapel.guru',
        'regisMapelSiswas.kelasMapel.tahunAjaran'
    ])
    ->where('lembaga', $lembaga)
    ->get();

    $groupedData = [];

    foreach ($siswaList as $s) {
        foreach ($s->regisMapelSiswas as $regis) {
            if (
                !$regis->kelasMapel ||
                !$regis->kelasMapel->tahunAjaran ||
                !$regis->kelasMapel->kelas ||
                !$regis->kelasMapel->mapel
            ) {
                continue;
            }

            $ta = $regis->kelasMapel->tahunAjaran->tahun_ajaran;
            $sem = $regis->kelasMapel->tahunAjaran->semester;
            $kelasNama = $regis->kelasMapel->kelas->nama_kelas;
            $mapelNama = $regis->kelasMapel->mapel->nama_mapel;

            if ($tahunAjaran && strtolower(trim($tahunAjaran)) !== strtolower(trim($ta))) continue;
            if ($semester && strtolower(trim($semester)) !== strtolower(trim($sem))) continue;
            if ($kelas && strtolower(trim($kelas)) !== strtolower(trim($kelasNama))) continue;
            if ($mapel && strtolower(trim($mapel)) !== strtolower(trim($mapelNama))) continue;

            $nilai = $regis->nilaiSiswa;

            $groupedData[$ta][$sem][$kelasNama][$mapelNama][] = [
                'nama_siswa' => $s->nama_siswa,
                'nilai_tugas' => $nilai->nilai_tugas ?? null,
                'nilai_uts' => $nilai->nilai_uts ?? null,
                'nilai_uas' => $nilai->nilai_uas ?? null,
                'nilai_akhir' => $nilai->nilai_akhir ?? null,
            ];
        }
    }

    return $groupedData;
}

public function showAbsensi(Request $request, $jenjang)
{
    $user = Auth::user();
    if ($user->role !== 'yayasan') {
        abort(403, 'Unauthorized access');
    }

    $jenjang = strtolower($jenjang);
    $lembagaMap = [
        'sd' => Siswa::LEMBAGA_SD,
        'smp' => Siswa::LEMBAGA_SMP,
    ];

    if (!isset($lembagaMap[$jenjang])) {
        abort(404, 'Jenjang tidak valid');
    }

    $lembaga = $lembagaMap[$jenjang];

    // Ambil filter dari request
    $tahunAjaranFilter = $request->input('tahun_ajaran');
    $semesterFilter = $request->input('semester');
    $kelasFilter = $request->input('kelas');
    $mapelFilter = $request->input('mapel');
    $tanggalFilter = $request->input('tanggal');
    $bulanFilter = $request->input('bulan');
    $search = $request->input('search');

    $absensiList = AbsensiSiswa::with([
        'regisMapelSiswa.siswa',
        'regisMapelSiswa.kelasMapel.kelas',
        'regisMapelSiswa.kelasMapel.mapel',
        'regisMapelSiswa.kelasMapel.tahunAjaran'
    ])->whereHas('regisMapelSiswa.siswa', function ($query) use ($lembaga) {
        $query->where('lembaga', $lembaga);
    });

    if (!empty($tanggalFilter)) {
        $absensiList->whereDate('tanggal', $tanggalFilter);
    }

    if (!empty($bulanFilter)) {
        $absensiList->where('tanggal', 'like', $bulanFilter . '%');
    }

    if (!empty($tahunAjaranFilter)) {
        $absensiList->whereHas('regisMapelSiswa.kelasMapel.tahunAjaran', function ($q) use ($tahunAjaranFilter) {
            $q->where('tahun_ajaran', $tahunAjaranFilter)->where('is_deleted', false);
        });
    }

    if (!empty($semesterFilter)) {
        $absensiList->whereHas('regisMapelSiswa.kelasMapel.tahunAjaran', function ($q) use ($semesterFilter) {
            $q->where('semester', $semesterFilter);
        });
    }

    if (!empty($kelasFilter)) {
        $absensiList->whereHas('regisMapelSiswa.kelasMapel.kelas', function ($q) use ($kelasFilter) {
            $q->where('nama_kelas', $kelasFilter)->where('is_deleted', false);
        });
    }

    if (!empty($mapelFilter)) {
        $absensiList->whereHas('regisMapelSiswa.kelasMapel.mapel', function ($q) use ($mapelFilter) {
            $q->where('nama_mapel', $mapelFilter)->where('is_deleted', false);
        });
    }

    $absensiList = $absensiList->get();
    $rekapAbsensi = [];

    foreach ($absensiList as $absensi) {
        $siswa = $absensi->regisMapelSiswa->siswa;
        $nama = $siswa->nama_siswa ?? '-';

        if ($search && stripos($nama, $search) === false) {
            continue;
        }

        $kelas = $absensi->regisMapelSiswa->kelasMapel->kelas->nama_kelas ?? '-';
        $mapel = $absensi->regisMapelSiswa->kelasMapel->mapel->nama_mapel ?? '-';
        $tahun = $absensi->regisMapelSiswa->kelasMapel->tahunAjaran->tahun_ajaran ?? '-';

        $status = strtolower($absensi->status);
        if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha', 'terlambat'])) {
            continue;
        }

        if (!isset($rekapAbsensi[$tahun][$kelas][$nama]['mapel'][$mapel])) {
            $rekapAbsensi[$tahun][$kelas][$nama]['mapel'][$mapel] = [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpha' => 0,
                'terlambat' => 0,
            ];
        }

        $rekapAbsensi[$tahun][$kelas][$nama]['mapel'][$mapel][$status]++;
    }

    // Dropdown lists
    $kelasList = Kelas::active()
        ->whereHas('kelasMapels.regisMapelSiswas.siswa', function ($q) use ($lembaga) {
            $q->where('lembaga', $lembaga);
        })
        ->pluck('nama_kelas')->unique()->sort()->values();

    $mapelList = Mapel::active()->where('jenjang', $jenjang)->pluck('nama_mapel')->unique()->sort()->values();

    $tahunAjaranList = TahunAjaran::where('jenjang', strtoupper($jenjang))
        ->where('is_deleted', false)
        ->pluck('tahun_ajaran')
        ->unique()
        ->sort()
        ->values();

    $semesterList = TahunAjaran::where('jenjang', strtoupper($jenjang))
        ->where('is_deleted', false)
        ->pluck('semester')
        ->unique()
        ->sort()
        ->values();

    return view('YayasanAbsensiSiswa', [
        'jenjang' => $jenjang,
        'rekapAbsensi' => $rekapAbsensi,
        'filters' => [
            'kelas' => $kelasFilter,
            'mapel' => $mapelFilter,
            'tanggal' => $tanggalFilter,
            'bulan' => $bulanFilter,
            'tahun_ajaran' => $tahunAjaranFilter,
            'semester' => $semesterFilter,
            'search' => $search,
        ],
        'kelasList' => $kelasList,
        'mapelList' => $mapelList,
        'tahunAjaranList' => $tahunAjaranList,
        'semesterList' => $semesterList,
    ]);
}


public function cetakAbsensiSiswa($jenjang)
{
    $filters = request()->only(['tahun_ajaran', 'semester', 'kelas', 'mapel', 'bulan', 'tanggal']);
    $filters['jenjang'] = $jenjang; // Tambahkan jenjang ke filter
    $rekapAbsensi = $this->getRekapAbsensi($filters);

    return view('YayasanCetakAbsensiSiswa', compact('rekapAbsensi', 'filters', 'jenjang'));
}

private function getRekapAbsensi($filters)
{
    $user = Auth::user();
    $role = $user->role;

    if (!isset($filters['jenjang']) || !in_array($filters['jenjang'], ['sd', 'smp'])) {
        abort(404, 'Jenjang tidak ditemukan');
    }

    $lembagaFilter = $filters['jenjang'] === 'sd' ? Siswa::LEMBAGA_SD : Siswa::LEMBAGA_SMP;

    $absensiList = AbsensiSiswa::with([
        'regisMapelSiswa.siswa',
        'regisMapelSiswa.kelasMapel.kelas',
        'regisMapelSiswa.kelasMapel.mapel',
        'regisMapelSiswa.kelasMapel.tahunAjaran',
    ])->whereHas('regisMapelSiswa.siswa', function ($query) use ($lembagaFilter) {
        $query->where('lembaga', $lembagaFilter);
    });

    if (!empty($filters['tanggal'])) {
        $absensiList->whereDate('tanggal', $filters['tanggal']);
    }
    if (!empty($filters['bulan'])) {
        $absensiList->where('tanggal', 'like', $filters['bulan'] . '%');
    }
    if (!empty($filters['tahun_ajaran'])) {
        $absensiList->whereHas('regisMapelSiswa.kelasMapel.tahunAjaran', function ($q) use ($filters) {
            $q->where('tahun_ajaran', $filters['tahun_ajaran'])->where('is_deleted', false);
        });
    }
    if (!empty($filters['semester'])) {
        $absensiList->whereHas('regisMapelSiswa.kelasMapel.tahunAjaran', function ($q) use ($filters) {
            $q->where('semester', $filters['semester']);
        });
    }
    if (!empty($filters['kelas'])) {
        $absensiList->whereHas('regisMapelSiswa.kelasMapel.kelas', function ($q) use ($filters) {
            $q->where('nama_kelas', $filters['kelas'])->where('is_deleted', false);
        });
    }
    if (!empty($filters['mapel'])) {
        $absensiList->whereHas('regisMapelSiswa.kelasMapel.mapel', function ($q) use ($filters) {
            $q->where('nama_mapel', $filters['mapel'])->where('is_deleted', false);
        });
    }

    $absensiList = $absensiList->get();

    $rekapAbsensi = [];
    foreach ($absensiList as $absensi) {
        $siswa = $absensi->regisMapelSiswa->siswa;
        $kelas = $absensi->regisMapelSiswa->kelasMapel->kelas->nama_kelas ?? '-';
        $mapel = $absensi->regisMapelSiswa->kelasMapel->mapel->nama_mapel ?? '-';
        $tahun = $absensi->regisMapelSiswa->kelasMapel->tahunAjaran->tahun_ajaran ?? '-';
        $nama = $siswa->nama_siswa ?? '-';

        $status = strtolower($absensi->status);
        if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha', 'terlambat'])) {
            continue;
        }

        if (!isset($rekapAbsensi[$tahun][$kelas][$nama]['mapel'][$mapel])) {
            $rekapAbsensi[$tahun][$kelas][$nama]['mapel'][$mapel] = [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpha' => 0,
                'terlambat' => 0,
            ];
        }

        $rekapAbsensi[$tahun][$kelas][$nama]['mapel'][$mapel][$status]++;
    }

    return $rekapAbsensi;
}
public function showHafalan(Request $request, $jenjang)
{
    // Map parameter jenjang ke kode lembaga yang sesuai di tabel siswa
    $jenjangMap = [
        'sd' => Siswa::LEMBAGA_SD,
        'smp' => Siswa::LEMBAGA_SMP,
    ];

    if (!isset($jenjangMap[$jenjang])) {
        abort(404, 'Jenjang tidak ditemukan');
    }

    $lembagaFilter = $jenjangMap[$jenjang];

    $query = Siswa::with([
        'regisMapelSiswas.kelasMapel.kelas',
        'regisMapelSiswas.kelasMapel.tahunAjaran',
        'regisMapelSiswas.hafalanQuranSiswa.surat',
        'regisMapelSiswas.hafalanQuranSiswa.guru',
    ])
    ->where('lembaga', $lembagaFilter);

    // Filter berdasarkan input kelas, tahun ajaran, semester
    if ($request->filled('kelas')) {
        $query->whereHas('regisMapelSiswas.kelasMapel.kelas', function ($q) use ($request) {
            $q->where('nama_kelas', $request->kelas);
        });
    }

    if ($request->filled('tahun_ajaran')) {
        $query->whereHas('regisMapelSiswas.kelasMapel.tahunAjaran', function ($q) use ($request) {
            $q->where('tahun_ajaran', $request->tahun_ajaran);
        });
    }

    if ($request->filled('semester')) {
        $query->whereHas('regisMapelSiswas.kelasMapel.tahunAjaran', function ($q) use ($request) {
            $q->where('semester', $request->semester);
        });
    }

    // Filter Search Nama Siswa
    if ($request->filled('nama')) {
        $query->where('nama_siswa', 'like', '%' . $request->nama . '%');
    }

    $siswaList = $query->orderBy('nama_siswa')->get();

    // Proses rekap
    $rekapHafalan = $siswaList->map(function ($siswa) {
        $kelasList = $siswa->regisMapelSiswas->map(function ($regis) {
            return optional($regis->kelasMapel->kelas)->nama_kelas;
        })->filter()->unique()->implode(', ');

        $detailHafalan = [];

        foreach ($siswa->regisMapelSiswas as $regis) {
            foreach ($regis->hafalanQuranSiswa as $hafalan) {
                $detailHafalan[] = [
                    'nama_surat' => optional($hafalan->surat)->nama_surat ?? '-',
                    'ayat_dari' => $hafalan->ayat_dari ?? '-',
                    'ayat_sampai' => $hafalan->ayat_sampai ?? '-',
                    'tgl_setor' => $hafalan->tgl_setor ?? '-',
                    'guru' => optional($hafalan->guru)->nama_lengkap ?? '-',
                    'keterangan' => $hafalan->keterangan ?? '-',
                ];
            }
        }

        $totalHafalan = count($detailHafalan);
        $tanggalTerakhir = collect($detailHafalan)->pluck('tgl_setor')->max();

        return [
            'nama_siswa' => $siswa->nama_siswa,
            'kelas' => $kelasList ?: '-',
            'total_hafalan' => $totalHafalan,
            'tanggal_setor' => $tanggalTerakhir,
            'detail_hafalan' => $detailHafalan,
        ];
    });

    // Ambil list kelas untuk filter dropdown
    $kelasList = $siswaList
        ->flatMap(function ($siswa) {
            return $siswa->regisMapelSiswas->map(function ($regis) {
                return optional($regis->kelasMapel->kelas)->nama_kelas;
            });
        })
        ->filter()
        ->unique()
        ->sort()
        ->values();

    // Ambil list tahun ajaran
    $tahunAjaranList = TahunAjaran::notDeleted()->orderBy('tahun_ajaran')->pluck('tahun_ajaran');

    return view('LembagaHafalanSiswa', [
        'kelasList' => $kelasList,
        'tahunAjaranList' => $tahunAjaranList,
        'rekapHafalan' => $rekapHafalan,
        'jenjang' => $jenjang,
    ]);
}
public function dataPegawai(Request $request, $jenjang)
{
    $user = Auth::user();

    $search = $request->input('search');
    $tugasFilter = $request->input('tugas_pokok');

    $tugasKepegawaianOptions = Pegawai::select('tugas_pokok')->distinct()->pluck('tugas_pokok')->toArray();

    // Tentukan unit kerja berdasarkan parameter jenjang, override role
    if ($jenjang === 'sd') {
        $unitKerjaFilter = 'SD';
    } elseif ($jenjang === 'smp') {
        $unitKerjaFilter = 'SMP';
    } else {
        abort(404, 'Jenjang tidak ditemukan');
    }

    // Query pegawai filter berdasarkan unit kerja sesuai jenjang
    $query = Pegawai::where('unit_kerja', 'like', "%{$unitKerjaFilter}%");

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('niy', 'like', "%{$search}%");
        });
    }

    if ($tugasFilter) {
        $query->where('tugas_pokok', $tugasFilter);
    }

    $pegawai = $query->orderBy('nama_lengkap')->paginate(10);

    $noResults = $pegawai->isEmpty();

    return view('LembagaLihatDataPegawai', compact('pegawai', 'tugasKepegawaianOptions', 'noResults', 'jenjang'));
}

public function cetakPegawai(Request $request, $jenjang)
{
    $query = Pegawai::query();

    if ($jenjang === 'sd') {
        $query->where('unit_kerja', 'like', '%SD%');
    } elseif ($jenjang === 'smp') {
        $query->where('unit_kerja', 'like', '%SMP%');
    } else {
        abort(404, 'Jenjang tidak ditemukan');
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('niy', 'like', "%{$search}%");
        });
    }

    if ($request->filled('tugas_pokok')) {
        $query->where('tugas_pokok', $request->tugas_pokok);
    }

    $pegawai = $query->get();

    return view('LembagaCetakDataPegawai', compact('pegawai', 'jenjang'));
}

public function absensiPegawai(Request $request, $jenjang)
{
    $user = Auth::user();

    // Tentukan unit berdasarkan parameter jenjang, bukan role user
    if ($jenjang === 'sd') {
        $unit = 'SD';
    } elseif ($jenjang === 'smp') {
        $unit = 'SMP';
    } else {
        abort(404, 'Jenjang tidak ditemukan');
    }

    // Ambil data absensi pegawai dengan filter dinamis
    $absensis = AbsensiPegawai::with('pegawai')
        ->whereHas('pegawai', function ($q) use ($unit) {
            $q->where('unit_kerja', 'like', '%' . $unit . '%');
        })
        ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun))
        ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
        ->orderBy('tanggal', 'desc')
        ->get();

    // Susun data ke dalam array bertingkat: Tahun > Bulan > Hari
    $grouped = [];

    foreach ($absensis as $item) {
        $tanggal = Carbon::parse($item->tanggal);
        $year = $tanggal->format('Y');
        $month = $tanggal->format('m');
        $day = $tanggal->toDateString();

        $grouped[$year][$month][$day][] = $item;
    }

    return view('LembagaLihatAbsensiPegawai', compact('grouped', 'jenjang'));
}

public function cetakAbsensiPegawai(Request $request, $jenjang)
{
    $user = Auth::user();

    if ($jenjang === 'sd') {
        $unit = 'SD';
    } elseif ($jenjang === 'smp') {
        $unit = 'SMP';
    } else {
        abort(404, 'Jenjang tidak ditemukan');
    }

    $tahun = $request->tahun ?? now()->year;
    $bulan = $request->bulan;

    $absensis = AbsensiPegawai::with('pegawai')
        ->whereHas('pegawai', fn($q) => $q->where('unit_kerja', 'like', "%$unit%"))
        ->when($tahun, fn($q) => $q->whereYear('waktu_masuk', $tahun))
        ->when($bulan, fn($q) => $q->whereMonth('waktu_masuk', $bulan))
        ->orderBy('waktu_masuk', 'desc')
        ->get();

    $grouped = [];

    foreach ($absensis as $item) {
        $tanggal = Carbon::parse($item->waktu_masuk);
        $year = $tanggal->format('Y');
        $month = $tanggal->format('m');
        $day = $tanggal->toDateString();

        $grouped[$year][$month][$day][] = $item;
    }

    return view('LembagaCetakAbsensiPegawai', compact('grouped', 'tahun', 'bulan', 'jenjang'));
}



}
