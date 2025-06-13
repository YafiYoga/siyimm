<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mapel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Tentukan jenjang berdasarkan role staff
        if ($user->role === 'staff_sd') {
            $jenjang = 'SD ISLAM TERPADU INSAN MADANI';
        } elseif ($user->role === 'staff_smp') {
            $jenjang = 'SMP IT TAHFIDZUL QURAN INSAN MADANI';
        } else {
            $jenjang = null; // Jika role lain, bisa tampilkan semua
        }

        // Query dasar (asumsikan scope active() sudah ada di model Mapel)
        $query = Mapel::where('is_deleted', false);

        // Filter jenjang jika role staff
        if ($jenjang) {
            $query->where('jenjang', $jenjang);
        }

        // Filter berdasarkan pencarian nama_mapel
        if ($request->filled('search')) {
            $query->where('nama_mapel', 'like', '%' . $request->search . '%');
        }

        // Ambil data mapel dengan pagination
        $mapelList = $query->orderBy('nama_mapel')->paginate(10)->withQueryString();

        // Handle edit mapel jika ada parameter 'edit'
        $editMapel = null;
        if ($request->filled('edit')) {
            $editMapel = Mapel::findOrFail($request->edit);

            // Cek jika user mencoba mengedit mapel dari jenjang yang bukan miliknya
            if (
                ($user->role === 'staff_sd' && $editMapel->jenjang !== 'SD ISLAM TERPADU INSAN MADANI') ||
                ($user->role === 'staff_smp' && $editMapel->jenjang !== 'SMP IT TAHFIDZUL QURAN INSAN MADANI')
            ) {
                abort(403, 'Tidak diizinkan mengedit mapel jenjang lain.');
            }
        }

        return view('StaffMapelSiswa', compact('mapelList', 'editMapel'));
    }

    public function store(Request $request)
{
    $user = Auth::user();

    // Jika role staff_sd, paksa jenjang selalu SD (override input)
    if ($user->role === 'staff_sd') {
        $request->merge(['jenjang' => 'SD ISLAM TERPADU INSAN MADANI']);
    } elseif ($user->role === 'staff_smp') {
        $request->merge(['jenjang' => 'SMP IT TAHFIDZUL QURAN INSAN MADANI']);
    }

    // Validasi input dengan rule unique per jenjang
    $request->validate([
        'nama_mapel' => [
            'required',
            'string',
            'max:255',
            Rule::unique('mapel')->where(function ($query) use ($request) {
                return $query->where('jenjang', $request->jenjang)
                             ->where('is_deleted', false);
            }),
        ],
        'jenjang' => 'required|in:SD ISLAM TERPADU INSAN MADANI,SMP IT TAHFIDZUL QURAN INSAN MADANI',
    ]);

    // Buat data mapel baru
    Mapel::create([
        'nama_mapel' => $request->nama_mapel,
        'jenjang' => $request->jenjang,
        'is_deleted' => false,
    ]);

    return redirect()->route('StaffMapelSIswa')->with('success', 'Mapel berhasil ditambahkan.');
}


    public function update(Request $request, $id)
{
    $user = Auth::user();

    // Jika role staff_sd, paksa jenjang selalu SD (override input)
    if ($user->role === 'staff_sd') {
        $request->merge(['jenjang' => 'SD ISLAM TERPADU INSAN MADANI']);
    } elseif ($user->role === 'staff_smp') {
        $request->merge(['jenjang' => 'SMP IT TAHFIDZUL QURAN INSAN MADANI']);
    }

    // Validasi input dengan rule unique per jenjang, kecuali record sendiri
    $request->validate([
        'nama_mapel' => [
            'required',
            'string',
            'max:255',
            Rule::unique('mapel')->where(function ($query) use ($request, $id) {
                return $query->where('jenjang', $request->jenjang)
                             ->where('is_deleted', false)
                             ->where('id', '!=', $id);
            }),
        ],
        'jenjang' => 'required|in:SD ISLAM TERPADU INSAN MADANI,SMP IT TAHFIDZUL QURAN INSAN MADANI',
    ]);

    $mapel = Mapel::findOrFail($id);

    // Cek hak akses update berdasarkan role dan jenjang mapel
    if ($user->role === 'staff_sd' && $mapel->jenjang !== 'SD ISLAM TERPADU INSAN MADANI') {
        abort(403, 'Tidak diizinkan memperbarui mapel jenjang lain.');
    }
    if ($user->role === 'staff_smp' && $mapel->jenjang !== 'SMP IT TAHFIDZUL QURAN INSAN MADANI') {
        abort(403, 'Tidak diizinkan memperbarui mapel jenjang lain.');
    }

    $mapel->update([
        'nama_mapel' => $request->nama_mapel,
        'jenjang' => $request->jenjang,
    ]);

    return redirect()->route('StaffMapelSIswa')->with('success', 'Mapel berhasil diperbarui.');
}


  public function destroy($id)
{
    $user = Auth::user();
    $mapel = Mapel::findOrFail($id);

    // Cek hak akses delete
    if ($user->role === 'staff_sd' && $mapel->jenjang !== 'SD ISLAM TERPADU INSAN MADANI') {
        abort(403, 'Tidak diizinkan menghapus mapel jenjang lain.');
    }

    if ($user->role === 'staff_smp' && $mapel->jenjang !== 'SMP IT TAHFIDZUL QURAN INSAN MADANI') {
        abort(403, 'Tidak diizinkan menghapus mapel jenjang lain.');
    }

    // Hapus permanen data mapel
    $mapel->delete();

    return redirect()->route('StaffMapelSIswa')->with('success', 'Mapel berhasil dihapus.');
}


}
