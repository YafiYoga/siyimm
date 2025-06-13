<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Tampilkan hanya pengumuman yang dibuat oleh staff yang sesuai role
    if ($user->role === 'staff_sd') {
        $pengumumen = Pengumuman::where('ditujukan_kepada', 'walimurid_sd')
                                 ->where('dibuat_oleh', $user->id)
                                 ->latest()
                                 ->get();
    } elseif ($user->role === 'staff_smp') {
        $pengumumen = Pengumuman::where('ditujukan_kepada', 'walimurid_smp')
                                 ->where('dibuat_oleh', $user->id)
                                 ->latest()
                                 ->get();
    } else {
        return redirect()->back()->with('error', 'Role tidak diizinkan melihat pengumuman.');
    }

    return view('PengumumanStaff', compact('pengumumen'));
}


    public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'isi' => 'required|string',
    ]);

    $user = Auth::user();

    // Cek role dan target pengumuman
    if ($user->role === 'staff_sd') {
        $ditujukan = 'walimurid_sd';
    } elseif ($user->role === 'staff_smp') {
        $ditujukan = 'walimurid_smp';
    } else {
        return redirect()->back()->with('error', 'Role tidak diizinkan membuat pengumuman.');
    }

    // Simpan data
    Pengumuman::create([
        'judul' => $request->judul,
        'isi' => $request->isi,
        'ditujukan_kepada' => $ditujukan,
        'dibuat_oleh' => $user->id,
        'role_pembuat' => $user->role,
    ]);

    return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
}


   public function edit($id)
{
    $pengumumen = Pengumuman::latest()->get();
    $pengumuman = Pengumuman::findOrFail($id);

    $user = Auth::user();
    if ($user->id !== $pengumuman->dibuat_oleh) {
        return redirect()->route('pengumuman.index')->with('error', 'Anda tidak punya akses mengedit pengumuman ini.');
    }

    return view('PengumumanStaff', compact('pengumuman', 'pengumumen'));
}


    public function update(Request $request, $id)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'isi' => 'required|string',
    ]);

    $pengumuman = Pengumuman::findOrFail($id);

    if (Auth::id() !== $pengumuman->dibuat_oleh) {
        return redirect()->route('pengumuman.index')->with('error', 'Tidak diizinkan mengedit pengumuman ini.');
    }

    $pengumuman->update([
        'judul' => $request->judul,
        'isi' => $request->isi,
    ]);

    return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
}


    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $user = Auth::user();

        if ($user->id !== $pengumuman->dibuat_oleh) {
            return redirect()->route('pengumuman.index')->with('error', 'Anda tidak punya akses menghapus pengumuman ini.');
        }

        $pengumuman->delete();

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
