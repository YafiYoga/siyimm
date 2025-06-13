<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class TambahAkunPenggunaController extends Controller
{
    public function tambah_akun_pengguna(Request $request)
{
    $pegawaiRoles = [
        'admin', 'yayasan',
        'lembaga_sd', 'lembaga_smp',
        'staff_sd', 'staff_smp',
        'guru_sd', 'guru_smp',
    ];

    $request->validate([
        'namalengkap' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'password' => 'required|string|min:6',
        'role' => 'required|string',
        'isDeleted' => 'required|in:0,1',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'niy' => in_array($request->role, $pegawaiRoles) ? 'required|string|max:30|unique:pegawai,niy' : 'nullable|string|max:30',
    ]);

    $fotoPath = $request->hasFile('foto')
        ? $request->file('foto')->store('user_photos', 'public')
        : null;

    // Default user data
    $userData = [
        'username' => $request->username,
        'password' => $request->password,
        'role' => $request->role,
        'isDeleted' => $request->isDeleted,
    ];

    // Jika pegawai
    if (in_array($request->role, $pegawaiRoles)) {
        $pegawai = Pegawai::create([
            'niy' => $request->niy,
            'nama_lengkap' => $request->namalengkap,
            'foto' => $fotoPath,
        ]);

        $userData['id_pegawai'] = $pegawai->niy; // simpan niy ke kolom id_pegawai
    }

    // Jika walimurid
    elseif (in_array($request->role, ['walimurid_sd', 'walimurid_smp'])) {
        $siswa = Siswa::create([
            'nama_siswa' => $request->namalengkap,
            'foto' => $fotoPath,
        ]);

        $userData['id_walimurid'] = $siswa->id; // simpan id siswa ke kolom id_walimurid
    }

    // Simpan user setelah id_pegawai atau id_walimurid di-set
    User::create($userData);

    return redirect()->back()->with('success', 'Akun berhasil ditambahkan!');
}


    public function TambahAkunAdmin()
    {
        return view('TambahAkunAdmin');
    }
}
