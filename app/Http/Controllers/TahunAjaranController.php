<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class TahunAjaranController extends Controller
{
    private $jenjangOptions = [
        'SD ISLAM TERPADU INSAN MADANI',
        'SMP IT TAHFIDZUL QURAN INSAN MADANI',
    ];

    // Fungsi bantu mendapatkan jenjang sesuai role user
    private function getUserJenjang()
    {
        $user = Auth::user();
        if (!$user) return null;

        // Contoh asumsi role disimpan di $user->role
        if ($user->role === 'staff_sd') {
            return 'SD ISLAM TERPADU INSAN MADANI';
        } elseif ($user->role === 'staff_smp') {
            return 'SMP IT TAHFIDZUL QURAN INSAN MADANI';
        }

        return null; // misal admin atau role lain, artinya akses semua jenjang
    }

    public function index(Request $request)
{
    $jenjang = $this->getUserJenjang();
    $search = $request->query('search');

   $tahunList = TahunAjaran::where('is_deleted', false)
                ->when($jenjang, function ($query) use ($jenjang) {
                    $query->where('jenjang', $jenjang);
                })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('tahun_ajaran', 'like', '%' . $search . '%')
                          ->orWhere('semester', 'like', '%' . $search . '%');
                    });
                })
                ->get();

    $editTahun = null;
    if ($request->has('edit')) {
        $editTahun = TahunAjaran::findOrFail($request->edit);

        // Jika ada pembatasan jenjang, cek dulu
        if ($jenjang && $editTahun->jenjang !== $jenjang) {
            abort(403, "Anda tidak punya akses mengedit tahun ajaran jenjang ini.");
        }
    }

    return view('StaffTahunAjaranSiswa', compact('tahunList', 'editTahun', 'search'));
}


    public function store(Request $request)
{
    $jenjang = $this->getUserJenjang();

    $request->validate([
        'tahun_ajaran' => 'required|string|max:255',
        'semester' => 'required|in:Ganjil,Genap',
        'jenjang' => 'required|in:' . implode(',', $this->jenjangOptions),
        'aktif_saat_ini' => 'nullable|boolean',
    ]);

    if ($jenjang && $request->jenjang !== $jenjang) {
        return redirect()->back()->withErrors(['jenjang' => 'Anda hanya bisa menambahkan tahun ajaran untuk jenjang ' . $jenjang]);
    }

    TahunAjaran::create([
        'tahun_ajaran' => $request->tahun_ajaran,
        'semester' => $request->semester,
        'jenjang' => $request->jenjang,
        'aktif_saat_ini' => $request->aktif_saat_ini ?? false,
        'is_deleted' => false,
    ]);

    return redirect()->route('StaffTahunAjaranSiswa')->with('success', 'Tahun Ajaran berhasil ditambahkan.');
}

   public function update(Request $request, $id)
{
    $jenjang = $this->getUserJenjang();

    $request->validate([
        'tahun_ajaran' => 'required|string|max:255',
        'semester' => 'required|in:Ganjil,Genap',
        'jenjang' => 'required|in:' . implode(',', $this->jenjangOptions),
        'aktif_saat_ini' => 'nullable|boolean',
    ]);

    $tahun = TahunAjaran::findOrFail($id);

    if ($jenjang && $tahun->jenjang !== $jenjang) {
        abort(403, "Anda tidak punya akses mengupdate tahun ajaran jenjang ini.");
    }

    $tahun->update([
        'tahun_ajaran' => $request->tahun_ajaran,
        'semester' => $request->semester,
        'jenjang' => $request->jenjang,
        'aktif_saat_ini' => $request->aktif_saat_ini ?? false,
    ]);

    return redirect()->route('StaffTahunAjaranSiswa')->with('success', 'Tahun Ajaran berhasil diperbarui.');
}


    public function destroy($id)
    {
        $jenjang = $this->getUserJenjang();

        $tahun = TahunAjaran::findOrFail($id);

        if ($jenjang && $tahun->jenjang !== $jenjang) {
            abort(403, "Anda tidak punya akses menghapus tahun ajaran jenjang ini.");
        }

        $tahun->update(['is_deleted' => true]);

        return redirect()->route('StaffTahunAjaranSiswa')->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
