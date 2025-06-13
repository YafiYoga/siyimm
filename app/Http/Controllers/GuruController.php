<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NilaiSiswa; // 
use App\Models\AbsensiSiswa; // AbsensiSiswa
use App\Models\Siswa;
use App\Models\HafalanQuranSiswa; // HafalanQuranSiswa
use App\Models\RegisMapelSiswa; // RegisMapelSiswa
use App\Models\MasterSurat; // MasterSurat
use App\Models\Kelas; // Kelas
use App\Models\TahunAjaran; // TahunAjaran
use App\Models\Mapel; // KelasMapel

class GuruController extends Controller
{

    
public function nilai(Request $request)
{
    $user = auth()->user();

    // Tentukan lembaga sesuai role guru
    if ($user->role == 'guru_sd') {
        $allowedLembaga = [Siswa::LEMBAGA_SD];
    } elseif ($user->role == 'guru_smp') {
        $allowedLembaga = [Siswa::LEMBAGA_SMP];
    } else {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
    }

    // Ambil filter dari request
    $filterSemester = $request->input('filter_semester');
    $filterTahunAjaran = $request->input('filter_tahun_ajaran');
    $namaKelas = $request->input('nama_kelas');
    $namaMapel = $request->input('nama_mapel');

    $idPegawai = $user->pegawai->id ?? null;

    // Query nilai siswa sesuai dengan kriteria guru dan lembaga
    $nilaiSiswaQuery = NilaiSiswa::with([
        'regisMapelSiswa.siswa',
        'regisMapelSiswa.kelasMapel.kelas',
        'regisMapelSiswa.kelasMapel.mapel',
        'regisMapelSiswa.kelasMapel.tahunAjaran'
    ])->whereHas('regisMapelSiswa', function ($query) use ($allowedLembaga, $idPegawai, $filterSemester, $filterTahunAjaran, $namaKelas, $namaMapel) {
        $query->whereHas('siswa', function ($q) use ($allowedLembaga) {
            $q->whereIn('lembaga', $allowedLembaga);
        })->whereHas('kelasMapel', function ($q) use ($idPegawai, $filterSemester, $filterTahunAjaran, $namaKelas, $namaMapel) {
            $q->where('id_guru', $idPegawai);

            // Filter Tahun Ajaran dan Semester
            if ($filterTahunAjaran || $filterSemester) {
                $q->whereHas('tahunAjaran', function ($ta) use ($filterTahunAjaran, $filterSemester) {
                    if ($filterTahunAjaran) {
                        $ta->where('tahun_ajaran', $filterTahunAjaran);
                    }
                    if ($filterSemester) {
                        $ta->where('semester', $filterSemester);
                    }
                });
            }

            // Filter Nama Kelas
            if ($namaKelas) {
                $q->whereHas('kelas', function ($kelasQuery) use ($namaKelas) {
                    $kelasQuery->where('nama_kelas', $namaKelas);
                });
            }

            // Filter Nama Mapel
            if ($namaMapel) {
                $q->whereHas('mapel', function ($mapelQuery) use ($namaMapel) {
                    $mapelQuery->where('nama_mapel', 'like', "%$namaMapel%");
                });
            }
        });
    });

    $nilaiSiswa = $nilaiSiswaQuery->get();

    // Group nilai berdasarkan siswa id untuk menggabungkan nilai dalam satu baris di blade
    $grouped = $nilaiSiswa->groupBy(function($item) {
        return $item->regisMapelSiswa->siswa->id ?? null;
    });

    // Ambil list RegisMapelSiswa untuk dropdown/input
    $regisList = RegisMapelSiswa::with(['siswa', 'kelasMapel.kelas', 'kelasMapel.mapel', 'kelasMapel.tahunAjaran'])
        ->whereHas('siswa', function ($q) use ($allowedLembaga) {
            $q->whereIn('lembaga', $allowedLembaga);
        })
        ->whereHas('kelasMapel', function ($q) use ($idPegawai, $filterSemester, $filterTahunAjaran, $namaKelas, $namaMapel) {
            $q->where('id_guru', $idPegawai);

            if ($filterTahunAjaran || $filterSemester) {
                $q->whereHas('tahunAjaran', function ($ta) use ($filterTahunAjaran, $filterSemester) {
                    if ($filterTahunAjaran) {
                        $ta->where('tahun_ajaran', $filterTahunAjaran);
                    }
                    if ($filterSemester) {
                        $ta->where('semester', $filterSemester);
                    }
                });
            }

            if ($namaKelas) {
                $q->whereHas('kelas', function ($kelasQuery) use ($namaKelas) {
                    $kelasQuery->where('nama_kelas', $namaKelas);
                });
            }

            if ($namaMapel) {
                $q->whereHas('mapel', function ($mapelQuery) use ($namaMapel) {
                    $mapelQuery->where('nama_mapel', 'like', "%$namaMapel%");
                });
            }
        })
        ->get();

    // Mode edit jika ada parameter edit
    $editMode = null;
    if ($request->has('edit')) {
        $editMode = NilaiSiswa::with([
            'regisMapelSiswa.siswa',
            'regisMapelSiswa.kelasMapel.kelas',
            'regisMapelSiswa.kelasMapel.mapel',
            'regisMapelSiswa.kelasMapel.tahunAjaran'
        ])->find($request->edit);

        if (!$editMode) {
            return redirect()->back()->withErrors(['error' => 'Data nilai yang ingin diedit tidak ditemukan.']);
        }
    }

    // Jika filter aktif tapi data kosong, flash message
    if (($namaKelas || $namaMapel || $filterSemester || $filterTahunAjaran) && $nilaiSiswa->isEmpty()) {
        session()->flash('info', 'Data nilai dengan filter yang Anda cari tidak ditemukan.');
    }

    // Ambil data dropdown filter: kelas, mapel, dan tahun ajaran unik
   $kelasList = Kelas::whereIn('jenjang', $allowedLembaga)->get();
   $mapelList = Mapel::whereIn('jenjang', $allowedLembaga)->get();
    $tahunAjaranList = TahunAjaran::aktif()->orderBy('tahun_ajaran', 'desc')->pluck('tahun_ajaran');
    // Kalau kamu punya model TahunAjaran, bisa diganti ambil dari sana, kalau tidak ambil dari NilaiSiswa



    return view('GuruNilai', [
        'nilai' => $nilaiSiswa,
        'filter_semester' => $filterSemester,
        'filter_tahun_ajaran' => $filterTahunAjaran,
        'nama_kelas' => $namaKelas,
        'nama_mapel' => $namaMapel,
        'regisList' => $regisList,
        'editMode' => $editMode,
        'grouped' => $grouped,
        'kelasList' => $kelasList,
        'mapelList' => $mapelList,
        'tahunAjaranList' => $tahunAjaranList,
    ]);
}








    public function storenilai(Request $request)
{
    $user = auth()->user();
    $idPegawai = $user->pegawai->id ?? null;

    // Tentukan allowed lembaga berdasarkan role
    $allowedLembaga = match($user->role) {
        'guru_sd' => [Siswa::LEMBAGA_SD],
        'guru_smp' => [Siswa::LEMBAGA_SMP],
        default => [],
    };

    if (empty($allowedLembaga)) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk input nilai.']);
    }

    $validated = $request->validate([
        'id_regis_mapel_siswa' => 'required|exists:regis_mapel_siswa,id',
        'nilai_tugas' => 'nullable|numeric',
        'nilai_uts' => 'nullable|numeric',
        'nilai_uas' => 'nullable|numeric',
    ]);

    // Ambil data registrasi siswa beserta kelasMapel dan siswa-nya
    $regis = RegisMapelSiswa::with('kelasMapel.kelas', 'siswa')->findOrFail($validated['id_regis_mapel_siswa']);

    // Cek apakah guru yang sedang login adalah guru kelasMapel tsb
    if ($regis->kelasMapel->id_guru != $idPegawai) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak berhak menginput nilai untuk mapel ini.']);
    }

    // Cek apakah siswa termasuk dalam lembaga yang diperbolehkan untuk role ini
    if (!in_array($regis->siswa->lembaga, $allowedLembaga)) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak berhak menginput nilai untuk siswa ini.']);
    }

    // Cek apakah nilai sudah ada sebelumnya
    $existing = NilaiSiswa::where('id_regis_mapel_siswa', $validated['id_regis_mapel_siswa'])->first();
    if ($existing) {
        return redirect()->back()->withErrors(['error' => 'Nilai untuk siswa dan mapel ini sudah ada.']);
    }

    // Hitung nilai akhir (rata-rata)
    $nilaiTugas = $validated['nilai_tugas'] ?? 0;
    $nilaiUTS = $validated['nilai_uts'] ?? 0;
    $nilaiUAS = $validated['nilai_uas'] ?? 0;
    $nilaiAkhir = round(($nilaiTugas + $nilaiUTS + $nilaiUAS) / 3, 2);

    $nilaiData = [
        'id_regis_mapel_siswa' => $validated['id_regis_mapel_siswa'],
        'nilai_tugas' => $nilaiTugas,
        'nilai_uts' => $nilaiUTS,
        'nilai_uas' => $nilaiUAS,
        'nilai_akhir' => $nilaiAkhir,
    ];

    NilaiSiswa::create($nilaiData);

    return redirect()->route('GuruNilai')->with('success', 'Nilai berhasil disimpan.');
}
public function updatenilai(Request $request, $id)
{
    $user = auth()->user();
    $idPegawai = $user->pegawai->id ?? null;

    // Tentukan allowed lembaga berdasarkan role
    $allowedLembaga = match($user->role) {
        'guru_sd' => [Siswa::LEMBAGA_SD],
        'guru_smp' => [Siswa::LEMBAGA_SMP],
        default => [],
    };

    if (empty($allowedLembaga)) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mengedit nilai ini.']);
    }

    $nilai = NilaiSiswa::with('regisMapelSiswa.kelasMapel.kelas', 'regisMapelSiswa.siswa')->findOrFail($id);

    // Cek apakah guru yang sedang login adalah guru mapel yang punya nilai ini
    if ($nilai->regisMapelSiswa->kelasMapel->id_guru != $idPegawai) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses ke nilai ini.']);
    }

    // Cek apakah siswa termasuk lembaga yang diperbolehkan untuk role ini
    if (!in_array($nilai->regisMapelSiswa->siswa->lembaga, $allowedLembaga)) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses ke siswa ini.']);
    }

    $validated = $request->validate([
        'nilai_tugas' => 'nullable|numeric',
        'nilai_uts' => 'nullable|numeric',
        'nilai_uas' => 'nullable|numeric',
    ]);

    $nilaiTugas = $validated['nilai_tugas'] ?? 0;
    $nilaiUTS = $validated['nilai_uts'] ?? 0;
    $nilaiUAS = $validated['nilai_uas'] ?? 0;
    $nilaiAkhir = round(($nilaiTugas + $nilaiUTS + $nilaiUAS) / 3, 2);

    $nilai->update([
        'nilai_tugas' => $nilaiTugas,
        'nilai_uts' => $nilaiUTS,
        'nilai_uas' => $nilaiUAS,
        'nilai_akhir' => $nilaiAkhir,
    ]);

    return redirect()->route('GuruNilai')->with('success', 'Nilai berhasil diupdate.');
}



    public function deletenilai($id)
{
    $user = auth()->user();
    $idPegawai = $user->pegawai->id ?? null;

    // Tentukan allowed lembaga sesuai role
    $allowedLembaga = match($user->role) {
        'guru_sd' => [Siswa::LEMBAGA_SD],
        'guru_smp' => [Siswa::LEMBAGA_SMP],
        default => [],
    };

    if (empty($allowedLembaga)) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menghapus nilai ini.']);
    }

    $nilai = NilaiSiswa::with('regisMapelSiswa.kelasMapel.kelas', 'regisMapelSiswa.siswa')->findOrFail($id);

    // Cek apakah guru yang sedang login adalah guru mapel yang punya nilai ini
    if ($nilai->regisMapelSiswa->kelasMapel->id_guru != $idPegawai) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses ke nilai ini.']);
    }

    // Cek apakah siswa termasuk lembaga yang diperbolehkan untuk role ini
    if (!in_array($nilai->regisMapelSiswa->siswa->lembaga, $allowedLembaga)) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses ke siswa ini.']);
    }

    $nilai->delete();

    return redirect()->route('GuruNilai')->with('success', 'Nilai berhasil dihapus.');
}
public function getMapelByKelas($kelasId)
{
    $mapelList = KelasMapel::where('id_kelas', $kelasId)->with('mapel')->get();

    return response()->json($mapelList->map(function ($item) {
        return [
            'id' => $item->id_mapel,
            'nama_mapel' => $item->mapel->nama_mapel ?? '-',
        ];
    }));
}

public function getSiswaByKelasMapel($kelasId, $mapelId)
{
    $regisList = RegisMapelSiswa::whereHas('kelasMapel', function ($query) use ($kelasId, $mapelId) {
        $query->where('id_kelas', $kelasId)
              ->where('id_mapel', $mapelId);
    })->with(['siswa', 'kelasMapel.tahunAjaran'])->get();

    return response()->json($regisList->map(function ($item) {
        return [
            'id' => $item->id,
            'nama_siswa' => $item->siswa->nama_siswa ?? 'Siswa Tidak Dikenal',
            'tahun_ajaran' => $item->kelasMapel->tahunAjaran->tahun_ajaran ?? '-',
            'semester' => $item->kelasMapel->tahunAjaran->semester ?? '-',
        ];
    }));
}









    // ----------------- HAFALAN -----------------
    

 // pastikan import model surat yang benar

public function hafalan(Request $request)
{
    $user = auth()->user();

    if ($user->role == 'guru_sd') {
        $allowedLembaga = [Siswa::LEMBAGA_SD];
        $jenjang = Siswa::LEMBAGA_SD;
    } elseif ($user->role == 'guru_smp') {
        $allowedLembaga = [Siswa::LEMBAGA_SMP];
        $jenjang = Siswa::LEMBAGA_SMP;
    } else {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menambahkan hafalan.']);
    }

   $surats = MasterSurat::where('jenjang', $jenjang)->get();
    $siswa = Siswa::whereIn('lembaga', $allowedLembaga)->get();

    // Ambil daftar kelas aktif sesuai jenjang
    $kelasList = Kelas::where('is_deleted', false)
        ->where('jenjang', $jenjang)
        ->orderBy('nama_kelas')
        ->get();

    // Ambil filter dari request
    $bulan = $request->input('bulan', date('m'));
    $tahun = $request->input('tahun', date('Y'));
    $namaKelas = $request->input('nama_kelas');
    $namaSurat = $request->input('nama_surat');

    // Query hafalan
    $hafalanQuery = HafalanQuranSiswa::whereHas('regisMapelSiswa.siswa', function ($query) use ($allowedLembaga) {
        $query->whereIn('lembaga', $allowedLembaga);
    })->with(['regisMapelSiswa.siswa', 'surat', 'guru']);

    if ($namaKelas) {
        $hafalanQuery->whereHas('regisMapelSiswa.kelasMapel.kelas', function ($q) use ($namaKelas) {
            $q->where('nama_kelas', $namaKelas);
        });
    }

    if ($bulan && $tahun) {
        $hafalanQuery->whereYear('tgl_setor', $tahun)
                     ->whereMonth('tgl_setor', $bulan);
    }

    // Filter nama surat
    if ($namaSurat) {
        $hafalanQuery->whereHas('surat', function ($q) use ($namaSurat) {
            $q->where('nama_surat', 'like', "%$namaSurat%");
        });
    }

    $hafalan = $hafalanQuery->get();

    if (($namaKelas || $bulan || $tahun || $namaSurat) && $hafalan->isEmpty()) {
        session()->flash('info', 'Data hafalan dengan filter yang Anda cari tidak ditemukan.');
    }

    $hafalanGrouped = $hafalan->groupBy(fn($item) => $item->regisMapelSiswa->siswa->id);

    $editMode = null;
    if ($request->has('edit')) {
        $editMode = HafalanQuranSiswa::with(['regisMapelSiswa.siswa', 'surat', 'guru'])->find($request->edit);
    }

    return view('GuruHafalan', compact(
        'hafalanGrouped',
        'siswa',
        'editMode',
        'surats',
        'kelasList',
        'namaKelas',
        'bulan',
        'tahun',
        'namaSurat'
    ));
}



public function storeHafalan(Request $request)
{
    $user = auth()->user();

    // Validasi input dasar
    $validated = $request->validate([
        'id_regis_mapel_siswa' => 'required|exists:regis_mapel_siswa,id',
        'id_surat' => 'required|exists:master_surat,id',
        'ayat_dari' => 'required|integer|min:1',
        'ayat_sampai' => 'required|integer|min:1',
        'tgl_setor' => 'required|date',
        'keterangan' => 'nullable|string',
    ]);

    // Ambil surat dari DB untuk validasi jumlah ayat
    $surat = MasterSurat::findOrFail($validated['id_surat']);

    if ($validated['ayat_sampai'] > $surat->jumlah_ayat) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['ayat_sampai' => "Ayat sampai tidak boleh melebihi jumlah ayat surat ({$surat->jumlah_ayat})."]);
    }

    if ($validated['ayat_dari'] > $validated['ayat_sampai']) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['ayat_dari' => 'Ayat dari harus lebih kecil atau sama dengan ayat sampai.']);
    }

    // Ambil data registrasi dan relasi siswa
    $regis = RegisMapelSiswa::with('siswa')->findOrFail($validated['id_regis_mapel_siswa']);
    $lembaga = $regis->siswa->lembaga ?? null;

    // Tentukan lembaga yang diizinkan berdasarkan role guru
    if ($user->role == 'guru_sd') {
        $allowedLembaga = [Siswa::LEMBAGA_SD];
    } elseif ($user->role == 'guru_smp') {
        $allowedLembaga = [Siswa::LEMBAGA_SMP];
    } else {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menambahkan hafalan.']);
    }

    // Cek apakah lembaga siswa sesuai dengan hak akses guru
    if (!in_array($lembaga, $allowedLembaga)) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menambahkan hafalan untuk siswa di lembaga ini.']);
    }

    // Simpan data hafalan
    HafalanQuranSiswa::create([
        'id_regis_mapel_siswa' => $regis->id,
        'id_surat' => $validated['id_surat'],
        'id_guru' => $user->pegawai->id ?? null,
        'ayat_dari' => $validated['ayat_dari'],
        'ayat_sampai' => $validated['ayat_sampai'],
        'tgl_setor' => $validated['tgl_setor'],
        'keterangan' => $validated['keterangan'],
    ]);

    return redirect()->route('GuruHafalan')->with('success', 'Data hafalan berhasil ditambahkan.');
}

public function updateHafalan(Request $request, $id)
{
    $user = auth()->user();

    // Validasi input dasar
    $validated = $request->validate([
        'id_regis_mapel_siswa' => 'required|exists:regis_mapel_siswa,id',
        'id_surat' => 'required|exists:master_surat,id',
        'ayat_dari' => 'required|integer|min:1',
        'ayat_sampai' => 'required|integer|min:1',
        'tgl_setor' => 'required|date',
        'keterangan' => 'nullable|string',
    ]);

    // Ambil surat dari DB untuk validasi jumlah ayat
    $surat = MasterSurat::findOrFail($validated['id_surat']);

    if ($validated['ayat_sampai'] > $surat->jumlah_ayat) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['ayat_sampai' => "Ayat sampai tidak boleh melebihi jumlah ayat surat ({$surat->jumlah_ayat})."]);
    }

    if ($validated['ayat_dari'] > $validated['ayat_sampai']) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['ayat_dari' => 'Ayat dari harus lebih kecil atau sama dengan ayat sampai.']);
    }

    $hafalan = HafalanQuranSiswa::findOrFail($id);
    $regis = RegisMapelSiswa::with('siswa')->findOrFail($validated['id_regis_mapel_siswa']);
    $lembaga = $regis->siswa->lembaga ?? null;

    // Tentukan lembaga yang diizinkan berdasarkan role guru
    if ($user->role == 'guru_sd') {
        $allowedLembaga = [Siswa::LEMBAGA_SD];
    } elseif ($user->role == 'guru_smp') {
        $allowedLembaga = [Siswa::LEMBAGA_SMP];
    } else {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mengedit hafalan.']);
    }

    // Cek apakah lembaga siswa sesuai dengan hak akses guru
    if (!in_array($lembaga, $allowedLembaga)) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mengedit hafalan siswa di lembaga ini.']);
    }

    // Update data hafalan
    $hafalan->update([
        'id_regis_mapel_siswa' => $regis->id,
        'id_surat' => $validated['id_surat'],
        'id_guru' => $user->pegawai->id ?? null,
        'ayat_dari' => $validated['ayat_dari'],
        'ayat_sampai' => $validated['ayat_sampai'],
        'tgl_setor' => $validated['tgl_setor'],
        'keterangan' => $validated['keterangan'],
    ]);

    return redirect()->route('GuruHafalan')->with('success', 'Data hafalan berhasil diperbarui.');
}




public function destroyHafalan($id)
{
    $user = auth()->user();

    if ($user->role == 'guru_sd') {
        $allowedLembaga = [Siswa::LEMBAGA_SD];
    } elseif ($user->role == 'guru_smp') {
        $allowedLembaga = [Siswa::LEMBAGA_SMP];
    } else {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menghapus hafalan.']);
    }

    $hafalan = HafalanQuranSiswa::with('regisMapelSiswa.siswa')->findOrFail($id);
    $lembaga = $hafalan->regisMapelSiswa->siswa->lembaga ?? null;

    if (!in_array($lembaga, $allowedLembaga)) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menghapus hafalan siswa di lembaga ini.']);
    }

    $hafalan->delete();

    return redirect()->route('GuruHafalan')->with('success', 'Data hafalan berhasil dihapus.');
}



 public function absensi(Request $request)
{
    $user = auth()->user();

    // Tentukan lembaga dan jenjang berdasar role
    if ($user->role == 'guru_sd') {
        $lembaga = Siswa::LEMBAGA_SD;
        $jenjang = 'SD ISLAM TERPADU INSAN MADANI';
    } elseif ($user->role == 'guru_smp') {
        $lembaga = Siswa::LEMBAGA_SMP;
        $jenjang = 'SMP IT TAHFIDZUL QURAN INSAN MADANI';
    } else {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
    }

    // Ambil daftar kelas aktif sesuai jenjang (untuk dropdown kelas)
    $kelasList = Kelas::where('is_deleted', false)
        ->where('jenjang', $jenjang)
        ->orderBy('nama_kelas')
        ->get();

    // Ambil filter dari request
    $bulan = $request->input('bulan', date('m'));
    $tahun = $request->input('tahun', date('Y'));
    $namaKelas = $request->input('nama_kelas');

    // Query absensi siswa berdasarkan lembaga dan filter tanggal
    $absensiQuery = AbsensiSiswa::with('regisMapelSiswa.siswa', 'regisMapelSiswa.kelasMapel.kelas')
        ->whereHas('regisMapelSiswa.siswa', function ($q) use ($lembaga) {
            $q->where('lembaga', $lembaga);
        })
        ->whereYear('tanggal', $tahun)
        ->whereMonth('tanggal', $bulan);

    if ($namaKelas) {
        $absensiQuery->whereHas('regisMapelSiswa.kelasMapel.kelas', function ($q) use ($namaKelas) {
            $q->where('nama_kelas', $namaKelas);
        });
    }

    $absensiAll = $absensiQuery->get();

    // Group absensi by siswa id agar lebih mudah ditampilkan
    $absensiGroupedBySiswa = $absensiAll->groupBy(fn($item) => $item->regisMapelSiswa->id_siswa);

    // Untuk edit mode, ambil data absensi yang diedit jika ada param 'edit'
    $editMode = null;
    if ($request->has('edit')) {
        $editMode = AbsensiSiswa::with('regisMapelSiswa.kelasMapel.kelas')->find($request->edit);
    }

    return view('GuruAbsensiSiswa', compact(
        'absensiGroupedBySiswa',
        'kelasList',
        'editMode',
        'bulan',
        'tahun',
        'namaKelas'
    ));
}

public function storeAbsensi(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'id_regis_mapel_siswa' => 'required|exists:regis_mapel_siswa,id',
        'tanggal' => 'required|date',
        'status' => 'required|in:hadir,alpha,sakit,izin',
    ]);

    $regis = RegisMapelSiswa::with('siswa')->findOrFail($request->id_regis_mapel_siswa);
    $siswa = $regis->siswa;

    $lembaga = $user->role == 'guru_sd' ? Siswa::LEMBAGA_SD : ($user->role == 'guru_smp' ? Siswa::LEMBAGA_SMP : null);

    if (!$lembaga || $siswa->lembaga !== $lembaga) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menambahkan absensi siswa ini.']);
    }

    $existing = AbsensiSiswa::where('id_regis_mapel_siswa', $regis->id)
        ->where('tanggal', $request->tanggal)
        ->first();

    if ($existing) {
        return redirect()->back()->withErrors(['error' => 'Absensi untuk siswa ini pada tanggal tersebut sudah ada.']);
    }

    AbsensiSiswa::create([
        'id_regis_mapel_siswa' => $regis->id,
        'tanggal' => $request->tanggal,
        'status' => $request->status,
    ]);

    return redirect()->route('GuruAbsensi')->with('success', 'Data absensi berhasil ditambahkan.');
}

public function updateAbsensi(Request $request, $id)
{
    $user = auth()->user();

    $request->validate([
        'id_regis_mapel_siswa' => 'required|exists:regis_mapel_siswa,id',
        'tanggal' => 'required|date',
        'status' => 'required|in:hadir,alpha,sakit,izin',
    ]);

    $absensi = AbsensiSiswa::findOrFail($id);
    $regis = RegisMapelSiswa::with('siswa')->findOrFail($request->id_regis_mapel_siswa);
    $siswa = $regis->siswa;

    $lembaga = $user->role == 'guru_sd' ? Siswa::LEMBAGA_SD : ($user->role == 'guru_smp' ? Siswa::LEMBAGA_SMP : null);

    if (!$lembaga || $siswa->lembaga !== $lembaga) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk mengedit absensi siswa ini.']);
    }

    // Validasi agar tidak duplikat tanggal kecuali data sendiri
    $existing = AbsensiSiswa::where('id_regis_mapel_siswa', $regis->id)
        ->where('tanggal', $request->tanggal)
        ->where('id', '!=', $absensi->id)
        ->first();

    if ($existing) {
        return redirect()->back()->withErrors(['error' => 'Absensi untuk siswa ini pada tanggal tersebut sudah ada.']);
    }

    $absensi->update([
        'id_regis_mapel_siswa' => $regis->id,
        'tanggal' => $request->tanggal,
        'status' => $request->status,
    ]);

    return redirect()->route('GuruAbsensi')->with('success', 'Data absensi berhasil diperbarui.');
}


public function destroyAbsensi($id)
{
    $user = auth()->user();

    $absensi = AbsensiSiswa::with('regisMapelSiswa.siswa')->findOrFail($id);
    $siswa = $absensi->regisMapelSiswa->siswa;

    $lembaga = $user->role == 'guru_sd' ? Siswa::LEMBAGA_SD : ($user->role == 'guru_smp' ? Siswa::LEMBAGA_SMP : null);

    if (!$lembaga || $siswa->lembaga !== $lembaga) {
        return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menghapus absensi siswa ini.']);
    }

    $absensi->delete();

    return redirect()->route('GuruAbsensi')->with('success', 'Data absensi berhasil dihapus.');
}

/**
 * AJAX endpoint untuk mendapatkan daftar siswa beserta mapel berdasarkan kelas yang dipilih.
 */
public function getSiswaByKelas($kelasId)
{
    $user = auth()->user();

    // Validasi role dan lembaga
    if ($user->role == 'guru_sd') {
        $lembaga = Siswa::LEMBAGA_SD;
    } elseif ($user->role == 'guru_smp') {
        $lembaga = Siswa::LEMBAGA_SMP;
    } else {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Ambil daftar RegisMapelSiswa yang kelasnya sesuai $kelasId dan siswa sesuai lembaga
    $regisList = RegisMapelSiswa::with(['siswa', 'kelasMapel.mapel'])
        ->whereHas('kelasMapel', function($q) use ($kelasId) {
            $q->where('id_kelas', $kelasId);
        })
        ->whereHas('siswa', function($q) use ($lembaga) {
            $q->where('lembaga', $lembaga);
        })
        ->get();

    // Format data untuk dropdown
    $data = $regisList->map(function($regis) {
        return [
            'id_regis_mapel_siswa' => $regis->id,
            'nama_siswa' => $regis->siswa->nama_siswa,
            'nama_mapel' => $regis->kelasMapel->mapel->nama_mapel ?? '-',
        ];
    });

    return response()->json($data);
}

    public function raport()
    {
        return view('GuruCetakRaportSiswa');
    }
}
