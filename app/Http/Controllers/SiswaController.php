<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\TahunAjaran;

class SiswaController extends Controller
{
    // Form tambah siswa
    public function TambahSiswa()
    {
        return view('TambahSiswaStaff');
    }

    // Simpan data siswa baru
   // Simpan data siswa baru
  // Simpan data siswa baru
public function store(Request $request)
{
    $user = Auth::user();

    // Tentukan lembaga berdasarkan role staff
    if ($user->role === 'staff_sd') {
        $lembaga = Siswa::LEMBAGA_SD;
        if ($request->role !== 'walimurid_sd') {
            return redirect()->back()->withErrors([
                'role' => 'Anda hanya dapat menambahkan wali murid dengan role walimurid_sd.'
            ])->withInput();
        }
    } elseif ($user->role === 'staff_smp') {
        $lembaga = Siswa::LEMBAGA_SMP;
        if ($request->role !== 'walimurid_smp') {
            return redirect()->back()->withErrors([
                'role' => 'Anda hanya dapat menambahkan wali murid dengan role walimurid_smp.'
            ])->withInput();
        }
    } else {
        abort(403, 'Unauthorized access');
    }

    // Validasi input
    $validated = $request->validate([
        'nama_siswa' => 'required|string|max:255',
        'nisn' => 'required|numeric|unique:siswa,nisn',
        'nik' => 'required|numeric|unique:siswa,nik',
        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'alamat' => 'required|string',
        'asal_sekolah' => 'nullable|string|max:255',
        'no_kk' => 'nullable|digits_between:5,16',
        'berat_badan' => 'nullable|numeric',
        'tinggi_badan' => 'nullable|numeric',
        'lingkar_kepala' => 'nullable|numeric',
        'jumlah_saudara_kandung' => 'nullable|integer',
        'jarak_rumah_ke_sekolah' => 'nullable|string|max:20',
        'status' => 'required|in:Aktif,Lulus,Pindah',
        'foto' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'role' => 'required|in:walimurid_sd,walimurid_smp',
        'nama_ayah' => 'nullable|string|max:255',
        'nama_ibu' => 'nullable|string|max:255',
        'nama_wali' => 'nullable|string|max:255',
    ]);

    // Pastikan minimal salah satu orang tua diisi
    if (empty($request->nama_ayah) && empty($request->nama_ibu) && empty($request->nama_wali)) {
        return redirect()->back()->withErrors([
            'orangtua' => 'Minimal salah satu dari Nama Ayah, Nama Ibu, atau Nama Wali harus diisi.'
        ])->withInput();
    }

    // Cek duplikat user berdasarkan NISN
    if (User::where('username', $request->nisn)->exists()) {
        return redirect()->back()->withErrors([
            'username' => 'Akun wali murid dengan NISN ini sudah terdaftar.'
        ])->withInput();
    }

    // Simpan foto
    $fotoPath = $request->file('foto')->store('foto_siswa', 'public');

    // Buat data siswa
    $siswa = Siswa::create([
        'nama_siswa' => $request->nama_siswa,
        'nisn' => $request->nisn,
        'nik' => $request->nik,
        'tempat_lahir' => $request->tempat_lahir,
        'tanggal_lahir' => $request->tanggal_lahir,
        'alamat' => $request->alamat,
        'asal_sekolah' => $request->asal_sekolah,
        'no_kk' => $request->no_kk,
        'berat_badan' => $request->berat_badan,
        'tinggi_badan' => $request->tinggi_badan,
        'lingkar_kepala' => $request->lingkar_kepala,
        'jumlah_saudara_kandung' => $request->jumlah_saudara_kandung,
        'jarak_rumah_ke_sekolah' => $request->jarak_rumah_ke_sekolah,
        'status' => $request->status,
        'foto' => basename($fotoPath),
        'nama_ayah' => $request->nama_ayah,
        'nama_ibu' => $request->nama_ibu,
        'nama_wali' => $request->nama_wali,
        'lembaga' => $lembaga,
    ]);

    // Simpan ke tabel walimurid
    $walimuridId = DB::table('walimurid')->insertGetId([
        'id_siswa' => $siswa->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Buat akun user untuk wali murid
    User::create([
        'namalengkap' => $request->nama_siswa,
        'username' => $request->nama_siswa,
        'password' => $request->nisn, // gunakan bcrypt untuk keamanan
        'id_walimurid' => $walimuridId,
        'role' => $request->role,
        'foto' => basename($fotoPath),
    ]);

    return redirect()->route('TambahSiswaStaff')->with('success', 'Data siswa dan akun wali murid berhasil ditambahkan!');
}

public function cetak(Request $request)
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

    $query = Siswa::with([
        'regisMapelSiswas.kelasMapel.kelas',
        'regisMapelSiswas.kelasMapel.mapel',
        'regisMapelSiswas.kelasMapel.guru',
        'regisMapelSiswas.kelasMapel.tahunAjaran',
        'regisMapelSiswas.nilaiSiswa',
    ])->where('lembaga', $lembaga);

    // Filter kelas jika ada
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

    return view('lembagaCetakDataSiswa', compact('siswa'));
}




    // Tampilkan daftar siswa
  public function index(Request $request)
{
    $user = auth()->user();

    if ($user->role === 'staff_sd') {
        $lembagaFilter = Siswa::LEMBAGA_SD;
        $lembagaOptions = [Siswa::LEMBAGA_SD]; // opsi hanya 1 lembaga untuk staff_sd
    } elseif ($user->role === 'staff_smp') {
        $lembagaFilter = Siswa::LEMBAGA_SMP;
        $lembagaOptions = [Siswa::LEMBAGA_SMP]; // opsi hanya 1 lembaga untuk staff_smp
    } else {
        abort(403, 'Unauthorized access');
    }

    $siswaQuery = Siswa::where('lembaga', $lembagaFilter);

    if ($request->filled('search')) {
        $search = $request->search;
        $siswaQuery->where(function($q) use ($search) {
            $q->where('nama_siswa', 'like', "%$search%")
              ->orWhere('nisn', 'like', "%$search%");
        });
    }

    if ($request->filled('status')) {
        $statusOptions = ['Aktif', 'Lulus', 'Pindah'];
        if (in_array($request->status, $statusOptions)) {
            $siswaQuery->where('status', $request->status);
        }
    }

    $siswa = $siswaQuery->orderBy('nama_siswa')->paginate(10);
    $noResults = $siswa->isEmpty();

    return view('LihatDataSiswaStaff', compact('siswa', 'user', 'noResults', 'lembagaOptions'));
}





    // Form edit siswa
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $user = Auth::user();
        return view('EditSiswaStaff', compact('siswa', 'user'));
    }

    public function destroy($id)
{
    $siswa = Siswa::findOrFail($id);
    $siswa->delete();

   return redirect()->route('LihatDataSiswaStaff')->with('success', 'Data pegawai berhasil diupdate!');
}

    // Update data siswa
    public function update(Request $request, $id)
{
    $user = Auth::user();

    // Tentukan lembaga berdasarkan role staff
    if ($user->role === 'staff_sd') {
        $lembaga = \App\Models\Siswa::LEMBAGA_SD;
    } elseif ($user->role === 'staff_smp') {
        $lembaga = \App\Models\Siswa::LEMBAGA_SMP;
    } else {
        abort(403, 'Unauthorized access');
    }

    // Validasi input
    $validated = $request->validate([
        'nama_siswa' => 'required|string|max:255',
        'nisn' => 'required|numeric|digits_between:9,12|unique:siswa,nisn,' . $id,
        'nik' => 'required|numeric|digits_between:10,20|unique:siswa,nik,' . $id,
        'tempat_lahir' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'alamat' => 'required|string',
        'asal_sekolah' => 'nullable|string|max:255',
        'no_kk' => 'nullable|digits_between:5,16',
        'berat_badan' => 'nullable|numeric',
        'tinggi_badan' => 'nullable|numeric',
        'lingkar_kepala' => 'nullable|numeric',
        'jumlah_saudara_kandung' => 'nullable|integer',
        'jarak_rumah_ke_sekolah' => 'nullable|string|max:20',
        'status' => 'required|in:Aktif,Lulus,Pindah',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'nama_ayah' => 'nullable|string|max:255',
        'nama_ibu' => 'nullable|string|max:255',
        'nama_wali' => 'nullable|string|max:255',
    ]);

    // Pastikan minimal salah satu dari nama_ayah, nama_ibu, atau nama_wali diisi
    if (empty($request->nama_ayah) && empty($request->nama_ibu) && empty($request->nama_wali)) {
        return redirect()->back()->withErrors([
            'orangtua' => 'Minimal salah satu dari Nama Ayah, Nama Ibu, atau Nama Wali harus diisi.'
        ])->withInput();
    }

    $siswa = \App\Models\Siswa::findOrFail($id);

    // Cek dan simpan foto baru jika ada
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('foto_siswa', 'public');
        $siswa->foto = basename($fotoPath);
    }

    // Update data siswa
    $siswa->update([
        'nama_siswa' => $request->nama_siswa,
        'nisn' => $request->nisn,
        'nik' => $request->nik,
        'tempat_lahir' => $request->tempat_lahir,
        'tanggal_lahir' => $request->tanggal_lahir,
        'alamat' => $request->alamat,
        'asal_sekolah' => $request->asal_sekolah,
        'no_kk' => $request->no_kk,
        'berat_badan' => $request->berat_badan,
        'tinggi_badan' => $request->tinggi_badan,
        'lingkar_kepala' => $request->lingkar_kepala,
        'jumlah_saudara_kandung' => $request->jumlah_saudara_kandung,
        'jarak_rumah_ke_sekolah' => $request->jarak_rumah_ke_sekolah,
        'status' => $request->status,
        'lembaga' => $lembaga,
        'nama_ayah' => $request->nama_ayah,
        'nama_ibu' => $request->nama_ibu,
        'nama_wali' => $request->nama_wali,
        'foto' => $siswa->foto,
    ]);

    return redirect()->route('LihatDataSiswaStaff')->with('success', 'Data siswa berhasil diperbarui.');
}


    // Form impor siswa
    public function showImportForm()
    {
        $user = Auth::user();
        return view('ImportDataSiswaStaff', compact('user'));
    }

    // Proses impor file Excel
  

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $userId = auth()->id();
        $import = new SiswaImport($userId);

        try {
            Excel::import($import, $request->file('file'));

            $duplicates = $import->getDuplicates();

            if (count($duplicates) > 0) {
                $message = 'Beberapa data gagal diimpor karena duplikat NISN: ' . implode(', ', $duplicates);
                return redirect()->back()->with('error', $message);
            }

            return redirect()->back()->with('success', 'Data siswa berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }


}
