<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KelasMapel;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;

class KelasMapelController extends Controller
{
   public function index(Request $request)
{
    $user = Auth::user();

    // Tentukan jenjang dan unit kerja berdasarkan role staff
    if ($user->role === 'staff_sd') {
        $jenjang = 'SD ISLAM TERPADU INSAN MADANI';
        $unitKerja = 'SD ISLAM TERPADU INSAN MADANI';
    } elseif ($user->role === 'staff_smp') {
        $jenjang = 'SMP IT TAHFIDZUL QURAN INSAN MADANI';
        $unitKerja = 'SMP IT TAHFIDZUL QURAN INSAN MADANI';
    } else {
        $jenjang = null; // Role lain, tampilkan semua
        $unitKerja = null;
    }

    $search = $request->input('search');

    // Buat query awal dengan relasi yang dibutuhkan
    $query = KelasMapel::with(['tahunAjaran', 'kelas', 'mapel', 'guru']);

    // Filter berdasarkan jenjang jika ada
    if ($jenjang) {
        $query->whereHas('kelas', function ($q) use ($jenjang) {
            $q->where('jenjang', $jenjang);
        })->whereHas('mapel', function ($q) use ($jenjang) {
            $q->where('jenjang', $jenjang);
        });
    }

    // Filter berdasarkan keyword search jika ada
    if ($search) {
    $query->where(function ($query) use ($search) {
        $query->whereHas('kelas', function ($q) use ($search) {
            $q->where('nama_kelas', 'like', "%{$search}%");
            // Kalau ada kolom lain di tabel kelas, tambahkan di sini
        })
        ->orWhereHas('tahunAjaran', function ($q) use ($search) {
            $q->where('tahun_ajaran', 'like', "%{$search}%")
              ->orWhere('semester', 'like', "%{$search}%");
        })
        ->orWhereHas('mapel', function ($q) use ($search) {
            $q->where('nama_mapel', 'like', "%{$search}%");
        })
        ->orWhereHas('guru', function ($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%");
        });
    });
}


    // Ambil data dengan pagination
    $kelasMapel = $query->paginate(10);

    // Ambil daftar tahun ajaran aktif sesuai jenjang
    if ($jenjang) {
        $tahunAjaranList = TahunAjaran::notDeleted()
            ->where('jenjang', $jenjang)
            ->get();
    } else {
        $tahunAjaranList = TahunAjaran::notDeleted()->get();
    }

    // Ambil master data kelas dan mapel sesuai jenjang
    if ($jenjang) {
        $kelasList = Kelas::active()->where('jenjang', $jenjang)->get();
        $mapelList = Mapel::active()->where('jenjang', $jenjang)->get();
    } else {
        $kelasList = Kelas::active()->get();
        $mapelList = Mapel::active()->get();
    }

    // Query daftar guru berdasarkan unit kerja dan role guru
    $queryGuru = Pegawai::join('users', 'users.id_pegawai', '=', 'pegawai.niy')
        ->where('users.role', 'like', 'guru_%');

    if ($unitKerja) {
        $queryGuru->where('pegawai.unit_kerja', $unitKerja);
    }

    $guruList = $queryGuru->select('pegawai.*')->get();

    // Handle data untuk edit jika parameter 'edit' ada
    $editKelasMapel = null;
    if ($request->filled('edit')) {
        $editKelasMapel = KelasMapel::findOrFail($request->edit);

        if ($jenjang && ($editKelasMapel->kelas->jenjang !== $jenjang || $editKelasMapel->mapel->jenjang !== $jenjang)) {
            abort(403, 'Tidak diizinkan mengedit data jenjang lain.');
        }
    }

    return view('StaffKelasMapelSiswa', compact(
        'kelasMapel',
        'tahunAjaranList',
        'kelasList',
        'mapelList',
        'guruList',
        'editKelasMapel'
    ));
}

    

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'staff_sd') {
            $request->merge(['jenjang_kelas' => 'SD ISLAM TERPADU INSAN MADANI', 'jenjang_mapel' => 'SD ISLAM TERPADU INSAN MADANI']);
        } elseif ($user->role === 'staff_smp') {
            $request->merge(['jenjang_kelas' => 'SMP IT TAHFIDZUL QURAN INSAN MADANI', 'jenjang_mapel' => 'SMP IT TAHFIDZUL QURAN INSAN MADANI']);
        } else {
            $request->merge(['jenjang_kelas' => null, 'jenjang_mapel' => null]);
        }

        $request->validate([
            'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id',
            'id_kelas'        => 'required|exists:kelas,id',
            'id_mapel'        => 'required|exists:mapel,id',
            'id_guru'         => 'required|exists:pegawai,id',
        ]);

        if ($user->role === 'staff_sd' || $user->role === 'staff_smp') {
            $kelas = Kelas::findOrFail($request->id_kelas);
            $mapel = Mapel::findOrFail($request->id_mapel);

            if ($kelas->jenjang !== $request->jenjang_kelas || $mapel->jenjang !== $request->jenjang_mapel) {
                abort(403, 'Anda hanya dapat menambahkan data untuk jenjang Anda.');
            }
        }

        KelasMapel::create([
            'id_tahun_ajaran' => $request->id_tahun_ajaran,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'id_guru' => $request->id_guru,
            // hilangkan is_deleted karena pakai soft delete Laravel
        ]);

        return redirect()->route('StaffKelasMapelSiswa')->with('success', 'Data Kelas Mapel berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
{
    $user = Auth::user();

    if ($user->role === 'staff_sd') {
        $request->merge([
            'jenjang_kelas' => 'SD ISLAM TERPADU INSAN MADANI',
            'jenjang_mapel' => 'SD ISLAM TERPADU INSAN MADANI'
        ]);
    } elseif ($user->role === 'staff_smp') {
        $request->merge([
            'jenjang_kelas' => 'SMP IT TAHFIDZUL QURAN INSAN MADANI',
            'jenjang_mapel' => 'SMP IT TAHFIDZUL QURAN INSAN MADANI'
        ]);
    } else {
        $request->merge([
            'jenjang_kelas' => null,
            'jenjang_mapel' => null
        ]);
    }

    $request->validate([
        'id_tahun_ajaran' => 'required|exists:tahun_ajaran,id',
        'id_kelas'        => 'required|exists:kelas,id',
        'id_mapel'        => 'required|exists:mapel,id',
        'id_guru'         => 'required|exists:pegawai,id',
    ]);

    $kelasMapel = KelasMapel::findOrFail($id);

    // Hapus pengecekan trashed() karena tidak ada softdelete
    // if ($kelasMapel->trashed()) {
    //     abort(404, 'Data tidak ditemukan atau sudah dihapus.');
    // }

    if ($user->role === 'staff_sd' || $user->role === 'staff_smp') {
        $kelas = Kelas::findOrFail($request->id_kelas);
        $mapel = Mapel::findOrFail($request->id_mapel);

        if ($kelas->jenjang !== $request->jenjang_kelas || $mapel->jenjang !== $request->jenjang_mapel) {
            abort(403, 'Anda hanya dapat memperbarui data untuk jenjang Anda.');
        }

        if ($kelasMapel->kelas->jenjang !== $request->jenjang_kelas || $kelasMapel->mapel->jenjang !== $request->jenjang_mapel) {
            abort(403, 'Data ini tidak bisa diperbarui oleh staff jenjang lain.');
        }
    }

    $kelasMapel->update([
        'id_tahun_ajaran' => $request->id_tahun_ajaran,
        'id_kelas'        => $request->id_kelas,
        'id_mapel'        => $request->id_mapel,
        'id_guru'         => $request->id_guru,
    ]);

    return redirect()->route('StaffKelasMapelSiswa')->with('success', 'Data Kelas Mapel berhasil diperbarui.');
}


    public function destroy($id)
{
    $user = Auth::user();

    $kelasMapel = KelasMapel::findOrFail($id);

    // Validasi role dan jenjang
    if ($user->role === 'staff_sd' || $user->role === 'staff_smp') {
        $jenjang = $user->role === 'staff_sd'
            ? 'SD ISLAM TERPADU INSAN MADANI'
            : 'SMP IT TAHFIDZUL QURAN INSAN MADANI';

        if ($kelasMapel->kelas->jenjang !== $jenjang || $kelasMapel->mapel->jenjang !== $jenjang) {
            abort(403, 'Tidak diizinkan menghapus data jenjang lain.');
        }
    }

    // Langsung delete karena tidak pakai softdelete
    $kelasMapel->delete();

    return redirect()->route('StaffKelasMapelSiswa')->with('success', 'Data Kelas Mapel berhasil dihapus.');
}

}
