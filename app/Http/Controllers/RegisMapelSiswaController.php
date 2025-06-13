<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegisMapelSiswa;
use App\Models\KelasMapel;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class RegisMapelSiswaController extends Controller
{
    protected function getJenjangByRole($role)
    {
        if ($role === 'staff_sd') {
            return Siswa::LEMBAGA_SD;
        } elseif ($role === 'staff_smp') {
            return Siswa::LEMBAGA_SMP;
        } else {
            return null;
        }
    }

    public function index(Request $request)
{
    $user = Auth::user();
    $jenjang = $this->getJenjangByRole($user->role);
    $search = strtolower($request->input('search'));

    // Query RegisMapelSiswa dengan eager loading
    $regisMapelQuery = RegisMapelSiswa::with([
        'kelasMapel.tahunAjaran',
        'kelasMapel.kelas',
        'kelasMapel.mapel',
        'kelasMapel.guru',
        'siswa'
    ]);

    // Filter jenjang staff_sd / staff_smp
    if ($jenjang) {
        $regisMapelQuery = $regisMapelQuery->whereHas('kelasMapel.kelas', function ($q) use ($jenjang) {
            $q->where('jenjang', $jenjang);
        })->whereHas('kelasMapel.mapel', function ($q) use ($jenjang) {
            $q->where('jenjang', $jenjang);
        });
    }

    // Filter pencarian dengan case-insensitive
    if ($search) {
        $regisMapelQuery = $regisMapelQuery->where(function ($query) use ($search) {
            $query->whereHas('kelasMapel.kelas', function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_kelas) LIKE ?', ["%{$search}%"]);
            })
            ->orWhereHas('kelasMapel.mapel', function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_mapel) LIKE ?', ["%{$search}%"]);
            })
            ->orWhereHas('kelasMapel.guru', function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_lengkap) LIKE ?', ["%{$search}%"]);
            });
        });
    }

    $regisMapel = $regisMapelQuery->get();

    // Filter KelasMapel sesuai jenjang
    $kelasMapelQuery = KelasMapel::with(['tahunAjaran', 'kelas', 'mapel', 'guru']);
    if ($jenjang) {
        $kelasMapelQuery = $kelasMapelQuery->whereHas('kelas', function ($q) use ($jenjang) {
            $q->where('jenjang', $jenjang);
        })->whereHas('mapel', function ($q) use ($jenjang) {
            $q->where('jenjang', $jenjang);
        });
    }
    $kelasMapelList = $kelasMapelQuery->get();

    // Filter Siswa sesuai jenjang
    if ($jenjang) {
        $siswaList = Siswa::where('lembaga', $jenjang)->get();
    } else {
        $siswaList = Siswa::all();
    }

    // Untuk edit data jika ada parameter edit
    $editRegis = null;
    if ($request->has('edit')) {
        $editRegis = RegisMapelSiswa::findOrFail($request->edit);

        // Pastikan edit data sesuai jenjang staff
        if ($jenjang) {
            if (
                $editRegis->kelasMapel->kelas->jenjang !== $jenjang ||
                $editRegis->kelasMapel->mapel->jenjang !== $jenjang ||
                $editRegis->siswa->lembaga !== $jenjang
            ) {
                abort(403, 'Anda tidak diizinkan mengedit data jenjang lain.');
            }
        }
    }

    return view('StaffRegisMapelSiswa', compact('regisMapel', 'kelasMapelList', 'siswaList', 'editRegis'));
}



    public function store(Request $request)
{
    $request->validate([
        'id_kelas_mapel' => 'required|array|min:1',
        'id_kelas_mapel.*' => 'required|exists:kelas_mapel,id',
        'id_siswa' => 'required|array|min:1',
        'id_siswa.*' => 'required|exists:siswa,id',
    ]);

    $user = Auth::user();
    $jenjang = $this->getJenjangByRole($user->role);

    $kelasMapelItems = KelasMapel::with(['kelas', 'mapel'])->whereIn('id', $request->id_kelas_mapel)->get();

    // Validasi semua mapel dari kelas yang sama
    $kelasIdUnik = $kelasMapelItems->pluck('kelas.id')->unique();
    if ($kelasIdUnik->count() > 1) {
        return redirect()->back()->withErrors(['id_kelas_mapel' => 'Semua mapel harus berasal dari kelas yang sama.'])->withInput();
    }

    $kelasTarget = $kelasMapelItems->first()->kelas; // Ambil kelas tujuan
    $siswaItems = Siswa::whereIn('id', $request->id_siswa)->get();

    $errors = [];

    foreach ($siswaItems as $siswa) {
        // Cek apakah siswa sudah terdaftar di kelas lain (selain kelas yang dituju)
        $kelasSiswa = RegisMapelSiswa::where('id_siswa', $siswa->id)
            ->with('kelasMapel.kelas')
            ->get()
            ->pluck('kelasMapel.kelas')
            ->unique('id');

        foreach ($kelasSiswa as $existingKelas) {
            if ($existingKelas && $existingKelas->id !== $kelasTarget->id) {
                $errors[] = "Siswa {$siswa->nama_siswa} (NISN: {$siswa->nisn}) sudah terdaftar di kelas {$existingKelas->nama_kelas}.";
            }
        }
    }

    if (!empty($errors)) {
        return redirect()->back()->withErrors(['id_siswa' => $errors])->withInput();
    }

    // Jika lolos validasi, simpan data
    foreach ($siswaItems as $siswa) {
        foreach ($kelasMapelItems as $kelasMapel) {
            if ($jenjang) {
                if (
                    $kelasMapel->kelas->jenjang !== $jenjang ||
                    $kelasMapel->mapel->jenjang !== $jenjang ||
                    $siswa->lembaga !== $jenjang
                ) {
                    continue;
                }
            }

            $exists = RegisMapelSiswa::where('id_kelas_mapel', $kelasMapel->id)
                ->where('id_siswa', $siswa->id)
                ->exists();

            if (!$exists) {
                RegisMapelSiswa::create([
                    'id_kelas_mapel' => $kelasMapel->id,
                    'id_siswa' => $siswa->id,
                ]);
            }
        }
    }

    return redirect()->route('StaffRegisMapelSiswa')->with('success', 'Registrasi berhasil ditambahkan.');
}



    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $jenjang = $this->getJenjangByRole($user->role);

        $request->validate([
            'id_kelas_mapel' => 'required|exists:kelas_mapel,id',
            'id_siswa' => 'required|exists:siswa,id',
        ]);

        $regis = RegisMapelSiswa::findOrFail($id);

        if (method_exists($regis, 'trashed') && $regis->trashed()) {
            abort(404, 'Data tidak ditemukan atau sudah dihapus.');
        }

        $kelasMapel = KelasMapel::with(['kelas', 'mapel'])->findOrFail($request->id_kelas_mapel);
        $siswa = Siswa::findOrFail($request->id_siswa);

        if ($jenjang) {
            if (
                $kelasMapel->kelas->jenjang !== $jenjang ||
                $kelasMapel->mapel->jenjang !== $jenjang ||
                $siswa->lembaga !== $jenjang
            ) {
                abort(403, 'Anda hanya dapat memperbarui data untuk jenjang Anda.');
            }
            if (
                $regis->kelasMapel->kelas->jenjang !== $jenjang ||
                $regis->kelasMapel->mapel->jenjang !== $jenjang
            ) {
                abort(403, 'Data ini tidak bisa diperbarui oleh staff jenjang lain.');
            }
        }

        // Cek duplikasi data selain id ini
        $duplicate = RegisMapelSiswa::where('id_kelas_mapel', $request->id_kelas_mapel)
            ->where('id_siswa', $request->id_siswa)
            ->where('id', '!=', $id)
            ->first();

        if ($duplicate) {
            return redirect()->route('StaffRegisMapelSiswa')->with('error', 'Data duplikat: siswa sudah terdaftar di mapel tersebut.');
        }

        $regis->update($request->only(['id_kelas_mapel', 'id_siswa']));

        return redirect()->route('StaffRegisMapelSiswa')->with('success', 'Registrasi Mapel Siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $regis = RegisMapelSiswa::findOrFail($id);

        if (method_exists($regis, 'trashed') && $regis->trashed()) {
            abort(404, 'Data sudah dihapus.');
        }

        if ($user->role === 'staff_sd' || $user->role === 'staff_smp') {
            $jenjang = $this->getJenjangByRole($user->role);

            if (
                $regis->kelasMapel->kelas->jenjang !== $jenjang ||
                $regis->kelasMapel->mapel->jenjang !== $jenjang
            ) {
                abort(403, 'Tidak diizinkan menghapus data jenjang lain.');
            }
        }

        $regis->delete();

        return redirect()->route('StaffRegisMapelSiswa')->with('success', 'Registrasi Mapel Siswa berhasil dihapus.');
    }
}
