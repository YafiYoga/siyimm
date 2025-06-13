<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CariController extends Controller
{
   
    // Fungsi pencarian untuk admin
    public function cariAdmin(Request $request)
{
    $query = User::query()
        ->with(['pegawai', 'walimurid.siswa']) // Eager load semua relasi
        ->orderBy('created_at', 'desc');

    if ($request->has('search') && $request->search !== null) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('username', 'like', '%' . $search . '%')
              ->orWhere('role', 'like', '%' . $search . '%')
              ->orWhereHas('pegawai', function ($sub) use ($search) {
                  $sub->where('nama_lengkap', 'like', '%' . $search . '%');
              })
              ->orWhereHas('walimurid.siswa', function ($sub) use ($search) {
                  $sub->where('nama_siswa', 'like', '%' . $search . '%');
              })
              ->orWhereRaw("CASE WHEN isDeleted = 0 THEN 'Aktif' WHEN isDeleted = 1 THEN 'Tidak Aktif' END LIKE ?", ["%{$search}%"]);
        });
    }

    if ($request->has('status') && $request->status !== '') {
        $query->where('isDeleted', $request->status);
    }

    if ($request->has('role') && $request->role !== '') {
        $query->where('role', $request->role);
    }

    $users = $query->paginate(10)->appends($request->query());
    $counter = User::count();
    $noResults = $users->isEmpty();

    return view('DashboardAdmin', compact('users', 'counter', 'noResults'));
}


    

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

   public function cariPengguna(Request $request)
{
    $query = User::query()
        ->with(['pegawai', 'walimurid.siswa']) // Eager load semua relasi
        ->orderBy('created_at', 'desc');

    if ($request->has('search') && $request->search !== null) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('username', 'like', '%' . $search . '%')
              ->orWhere('role', 'like', '%' . $search . '%')
              ->orWhereHas('pegawai', function ($sub) use ($search) {
                  $sub->where('nama_lengkap', 'like', '%' . $search . '%');
              })
              ->orWhereHas('walimurid.siswa', function ($sub) use ($search) {
                  $sub->where('nama_siswa', 'like', '%' . $search . '%');
              })
              ->orWhereRaw("CASE WHEN isDeleted = 0 THEN 'Aktif' WHEN isDeleted = 1 THEN 'Tidak Aktif' END LIKE ?", ["%{$search}%"]);
        });
    }

    if ($request->has('status') && $request->status !== '') {
        $query->where('isDeleted', $request->status);
    }

    if ($request->has('role') && $request->role !== '') {
        $query->where('role', $request->role);
    }

    $users = $query->paginate(10)->appends($request->query());
    $counter = User::count();
    $noResults = $users->isEmpty();

    return view('LihatAkunPenggunaAdmin', compact('users', 'counter', 'noResults'));
}



}
