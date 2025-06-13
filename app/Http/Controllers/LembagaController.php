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


class LembagaController extends Controller
{
    // Tampilkan seluruh data siswa berdasarkan lembaga yang login
public function dataSiswa(Request $request)
{
    $user = Auth::user();
    $role = $user->role;

    if ($role === 'lembaga_sd') {
        $lembaga = Siswa::LEMBAGA_SD;
        $jenjang = 'SD';
    } elseif ($role === 'lembaga_smp') {
        $lembaga = Siswa::LEMBAGA_SMP;
        $jenjang = 'SMP';
    } else {
        abort(403, 'Unauthorized access');
    }

    // Ambil tahun ajaran aktif sesuai jenjang
    $tahunAjaranAktif = TahunAjaran::aktif()
        ->where('jenjang', $jenjang)
        ->first();

    // Ambil daftar kelas
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

    // Filter kelas
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

    // Filter pencarian nama siswa
    if ($request->filled('search')) {
        $query->where('nama_siswa', 'like', '%' . $request->search . '%');
    }

    $siswa = $query->get();

    return view('LembagaDataSiswa', compact('siswa', 'kelasList'));
}







    // Tampilkan nilai siswa berdasarkan ID siswa
public function showNilai(Request $request, $id)
{
    $user = Auth::user();
    $role = $user->role;

    // Tentukan lembaga berdasarkan role
    $lembaga = match ($role) {
        'lembaga_sd' => Siswa::LEMBAGA_SD,
        'lembaga_smp' => Siswa::LEMBAGA_SMP,
        default => abort(403, 'Unauthorized access'),
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

            // Filter case-insensitive dan trim
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

    return view('LembagaNilaiSiswa', [
        'groupedData' => $groupedData,
        'tahunAjaranList' => $tahunAjaranList,
        'kelasList' => $kelasList,
        'mapelList' => $mapelList,
        'filterTA' => $filterTA,
        'filterSemester' => $filterSemester,
        'filterKelas' => $filterKelas,
        'filterMapel' => $filterMapel,
        'lembaga' => $lembaga,
        'id' => $id,
    ]);
}


public function cetakNilai($id, Request $request)
{
    $tahunAjaran = $request->input('tahun_ajaran');
    $semester = $request->input('semester');
    $kelas = $request->input('kelas');
    $mapel = $request->input('mapel');

    // Ambil data yang difilter sama seperti halaman utama
    $data = $this->ambilDataNilaiSiswa($id, $tahunAjaran, $semester, $kelas, $mapel);

    // Kirim ke view khusus untuk cetak
    return view('LembagaCetakNilaiSiswa', [
        'groupedData' => $data,
        'id' => $id,
    ]);
}

public function ambilDataNilaiSiswa($id, $tahunAjaran = null, $semester = null, $kelas = null, $mapel = null)
{
    $user = Auth::user();
    $role = $user->role;

    // Tentukan lembaga berdasarkan role
    $lembaga = match($role) {
        'lembaga_sd' => Siswa::LEMBAGA_SD,
        'lembaga_smp' => Siswa::LEMBAGA_SMP,
        default => abort(403, 'Unauthorized access'),
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
            // Lewati jika ada relasi penting yang null
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

            // Gunakan perbandingan yang sensitif spasi dan case
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


public function showAbsensi(Request $request, $id)
{
    $user = Auth::user();
    $role = $user->role;

    // Ambil filter dari request
    $tahunAjaranFilter = $request->input('tahun_ajaran');
    $semesterFilter = $request->input('semester');
    $kelasFilter = $request->input('kelas');
    $mapelFilter = $request->input('mapel');
    $tanggalFilter = $request->input('tanggal');
    $bulanFilter = $request->input('bulan');

    // Ambil semua data absensi yang berkaitan dengan role lembaga
    $absensiList = AbsensiSiswa::with([
        'regisMapelSiswa.siswa',
        'regisMapelSiswa.kelasMapel.kelas',
        'regisMapelSiswa.kelasMapel.mapel',
        'regisMapelSiswa.kelasMapel.tahunAjaran'
    ])->whereHas('regisMapelSiswa.siswa', function ($query) use ($role) {
        if ($role === 'lembaga_sd') {
            $query->where('lembaga', Siswa::LEMBAGA_SD);
        } elseif ($role === 'lembaga_smp') {
            $query->where('lembaga', Siswa::LEMBAGA_SMP);
        } else {
            abort(403, 'Unauthorized access');
        }
    });

    // Filter tanggal
    if (!empty($tanggalFilter)) {
        $absensiList->whereDate('tanggal', $tanggalFilter);
    }

    // Filter bulan (format: YYYY-MM)
    if (!empty($bulanFilter)) {
        $absensiList->where('tanggal', 'like', $bulanFilter . '%');
    }

    // Apply relational filters
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

    // List kelas untuk dropdown filter
    $kelasList = Kelas::active()->pluck('nama_kelas')->unique();
    $mapelList = Mapel::active()->pluck('nama_mapel')->unique();
    $tahunAjaranList = TahunAjaran::where('is_deleted', false)->pluck('tahun_ajaran')->unique();

    return view('LembagaAbsensiSiswa', [
        'id' => $id,
        'rekapAbsensi' => $rekapAbsensi,
        'filters' => [
            'tahun_ajaran' => $tahunAjaranFilter,
            'semester' => $semesterFilter,
            'kelas' => $kelasFilter,
            'mapel' => $mapelFilter,
            'tanggal' => $tanggalFilter,
            'bulan' => $bulanFilter,
        ],
        'kelasList' => $kelasList,
        'mapelList' => $mapelList,
        'tahunAjaranList' => $tahunAjaranList,
    ]);
}


public function cetakAbsensiSiswa($id)
{
    $filters = request()->only(['tahun_ajaran', 'semester', 'kelas', 'mapel', 'bulan', 'tanggal']);
    $rekapAbsensi = $this->getRekapAbsensi($filters);

    return view('LembagaCetakAbsensiSiswa', compact('rekapAbsensi', 'filters', 'id'));
}

private function getRekapAbsensi($filters)
{
    $user = Auth::user();
    $role = $user->role;

    $absensiList = AbsensiSiswa::with([
        'regisMapelSiswa.siswa',
        'regisMapelSiswa.kelasMapel.kelas',
        'regisMapelSiswa.kelasMapel.mapel',
        'regisMapelSiswa.kelasMapel.tahunAjaran',
    ])->whereHas('regisMapelSiswa.siswa', function ($query) use ($role) {
        if ($role === 'lembaga_sd') {
            $query->where('lembaga', Siswa::LEMBAGA_SD);
        } elseif ($role === 'lembaga_smp') {
            $query->where('lembaga', Siswa::LEMBAGA_SMP);
        } else {
            abort(403, 'Unauthorized access');
        }
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




public function ambilDataHafalan($id, $tahunAjaran = null, $semester = null, $kelas = null, $nama = null)
{
    $user = Auth::user();
    $role = $user->role;

    // Query siswa dengan eager loading relasi lengkap
    $query = Siswa::with([
        'regisMapelSiswas.kelasMapel.kelas',
        'regisMapelSiswas.kelasMapel.tahunAjaran',
        'regisMapelSiswas.hafalanQuranSiswa.surat',
        'regisMapelSiswas.hafalanQuranSiswa.guru',
    ]);

    // Filter berdasarkan role lembaga
    if ($role === 'lembaga_sd') {
        $query->where('lembaga', Siswa::LEMBAGA_SD);
    } elseif ($role === 'lembaga_smp') {
        $query->where('lembaga', Siswa::LEMBAGA_SMP);
    } else {
        abort(403, 'Unauthorized access');
    }

    // Filter berdasarkan kelas jika ada
    if ($kelas) {
        $query->whereHas('regisMapelSiswas.kelasMapel.kelas', function ($q) use ($kelas) {
            $q->where('nama_kelas', $kelas);
        });
    }

    // Filter berdasarkan tahun ajaran jika ada
    if ($tahunAjaran) {
        $query->whereHas('regisMapelSiswas.kelasMapel.tahunAjaran', function ($q) use ($tahunAjaran) {
            $q->where('tahun_ajaran', $tahunAjaran);
        });
    }

    // Filter berdasarkan semester jika ada
    if ($semester) {
        $query->whereHas('regisMapelSiswas.kelasMapel.tahunAjaran', function ($q) use ($semester) {
            $q->where('semester', $semester);
        });
    }

    // Filter nama siswa jika ada
    if ($nama) {
        $query->where('nama_siswa', 'like', '%' . $nama . '%');
    }

    $siswaList = $query->orderBy('nama_siswa')->get();

    // Struktur data rekap
    $rekapHafalan = $siswaList->map(function ($siswa) {
        // Kelas yang pernah diikuti siswa, gabungkan jadi string
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

    return $rekapHafalan;
}














    // Tampilkan hafalan quran siswa berdasarkan ID siswa
public function showHafalan(Request $request, $id)
{
    $user = Auth::user();
    $role = $user->role;

    $query = Siswa::with([
        'regisMapelSiswas.kelasMapel.kelas',
        'regisMapelSiswas.kelasMapel.tahunAjaran',
        'regisMapelSiswas.hafalanQuranSiswa.surat',
        'regisMapelSiswas.hafalanQuranSiswa.guru',
    ]);

    // Filter berdasarkan role lembaga
    if ($role === 'lembaga_sd') {
        $query->where('lembaga', Siswa::LEMBAGA_SD);
    } elseif ($role === 'lembaga_smp') {
        $query->where('lembaga', Siswa::LEMBAGA_SMP);
    } else {
        abort(403, 'Unauthorized access');
    }

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

    // **Filter Search Nama Siswa**
    if ($request->filled('nama')) {
        $query->where('nama_siswa', 'like', '%' . $request->nama . '%');
    }

    $siswaList = $query->orderBy('nama_siswa')->get();

    // Proses rekap
    $rekapHafalan = $siswaList->map(function ($siswa) {
        // Ambil semua nama kelas yang pernah diikuti siswa ini
        $kelasList = $siswa->regisMapelSiswas->map(function ($regis) {
            return optional($regis->kelasMapel->kelas)->nama_kelas;
        })->filter()->unique()->implode(', ');

        $detailHafalan = [];

        foreach ($siswa->regisMapelSiswas as $regis) {
            foreach ($regis->hafalanQuranSiswa as $hafalan) {
                $detailHafalan[] = [
                    'nama_surat' => optional($hafalan->surat)->nama_surat,
                    'ayat_dari' => $hafalan->ayat_dari,
                    'ayat_sampai' => $hafalan->ayat_sampai,
                    'tgl_setor' => $hafalan->tgl_setor,
                    'guru' => optional($hafalan->guru)->nama_lengkap,
                    'keterangan' => $hafalan->keterangan,
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
        'id' => $id,
    ]);
}







    // Tampilkan seluruh pegawai milik lembaga yang login
 public function dataPegawai(Request $request)
{
    $user = Auth::user();

    // Ambil input filter dari request
    $search = $request->input('search');
    $tugasFilter = $request->input('tugas_pokok'); // ganti jadi tugas_pokok

    // Daftar opsi tugas pokok (ambil distinct dari kolom tugas_pokok)
    $tugasKepegawaianOptions = Pegawai::select('tugas_pokok')->distinct()->pluck('tugas_pokok')->toArray();

    // Query dasar Pegawai sesuai role user
    if ($user->role === 'lembaga_sd') {
        $query = Pegawai::where('unit_kerja', 'like', '%SD%');
    } elseif ($user->role === 'lembaga_smp') {
        $query = Pegawai::where('unit_kerja', 'like', '%SMP%');
    } else {
        $query = Pegawai::where('niy', $user->id_pegawai);
    }

    // Apply filter search (nama lengkap atau NIY)
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('niy', 'like', "%{$search}%");
        });
    }

    // Apply filter tugas pokok
    if ($tugasFilter) {
        $query->where('tugas_pokok', $tugasFilter);
    }

    // Ambil data dengan pagination (misal 10 per halaman)
    $pegawai = $query->orderBy('nama_lengkap')->paginate(10);

    // Jika data kosong setelah filter, berikan flag noResults ke view
    $noResults = $pegawai->isEmpty();

    return view('LembagaLihatDataPegawai', compact('pegawai', 'tugasKepegawaianOptions', 'noResults'));
}
public function cetakPegawai(Request $request)
{
    $query = Pegawai::query();

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

    // Tampilkan view cetak, misal resources/views/lembaga/data_pegawai/cetak.blade.php
    return view('LembagaCetakDataPegawai', compact('pegawai'));
}




public function absensiPegawai(Request $request)
{
    $user = Auth::user();

    // Tentukan unit berdasarkan role pengguna
    $unit = null;
    if (in_array($user->role, ['lembaga_sd', 'lembaga_smp'])) {
        $unit = $user->role === 'lembaga_sd' ? 'SD' : 'SMP';
    }

    // Ambil data absensi pegawai dengan filter dinamis
    $absensis = AbsensiPegawai::with('pegawai')
        ->when($unit, function ($query) use ($unit) {
            $query->whereHas('pegawai', function ($q) use ($unit) {
                $q->where('unit_kerja', 'like', '%' . $unit . '%');
            });
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

    return view('LembagaLihatAbsensiPegawai', compact('grouped'));
}

public function cetakAbsensiPegawai(Request $request)
{
    $user = Auth::user();

    $unit = null;
    if (in_array($user->role, ['lembaga_sd', 'lembaga_smp'])) {
        $unit = $user->role === 'lembaga_sd' ? 'SD' : 'SMP';
    }

    $tahun = $request->tahun ?? now()->year;
    $bulan = $request->bulan;

    $absensis = AbsensiPegawai::with('pegawai')
        ->when($unit, fn($q) => $q->whereHas('pegawai', fn($q2) => $q2->where('unit_kerja', 'like', "%$unit%")))
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

    return view('LembagaCetakAbsensiPegawai', compact('grouped', 'tahun', 'bulan'));
}







    // Tampilkan halaman cetak data
    public function cetakData()
    {
        return view('LembagaCetakData');
    }
}
