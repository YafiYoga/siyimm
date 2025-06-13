<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LihatAkunPenggunaController extends Controller
{
    public function lihatAkunPenggunaAdmin()
{
    $currentUserId = Auth::id();
    $query = User::query()->where('id', '!=', $currentUserId);

    // Ambil semua user selain user yang sedang login
    $users = User::with(['pegawai', 'walimurid.siswa'])
                 ->where('id', '!=', $currentUserId) // pastikan user login tidak ikut tampil
                 ->paginate(10);

    return view('LihatAkunPenggunaAdmin', compact('users'));
}


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('LihatAkunPenggunaAdmin')->with('success', 'Akun berhasil dihapus.');
    }

    public function edit($id)
    {
        $user = User::with(['pegawai', 'walimurid.siswa'])->findOrFail($id);
        return view('HalamanEditAkunAdmin', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'isDeleted' => 'required|in:0,1',
            'role' => 'required|string',
            'password' => 'nullable|string|min:6',
        ]);

        $user = User::with(['pegawai', 'walimurid.siswa'])->findOrFail($id);

        $user->username = $request->username;
        $user->isDeleted = $request->isDeleted;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        $user->save();

        // Update nama lengkap di relasi pegawai atau walimurid jika ada
        if ($user->pegawai) {
        $user->pegawai->nama_lengkap = $request->nama_lengkap;
        $user->pegawai->save();
    } elseif ($user->walimurid && $user->walimurid->siswa) {
        $user->walimurid->siswa->nama_siswa = $request->nama_lengkap;
        $user->walimurid->siswa->save();
}

        return redirect()->route('LihatAkunPenggunaAdmin')->with('success', 'Akun berhasil diperbarui.');
    }
}
