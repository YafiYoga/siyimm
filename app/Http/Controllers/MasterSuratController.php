<?php

namespace App\Http\Controllers;

use App\Models\MasterSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterSuratController extends Controller
{
    // Helper function untuk dapatkan jenjang sesuai role login
    private function getJenjangForRole($role)
    {
        return match ($role) {
            'staff_sd' => 'SD ISLAM TERPADU INSAN MADANI',
            'staff_smp' => 'SMP IT TAHFIDZUL QURAN INSAN MADANI',
            default => null,
        };
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $user = Auth::user();

        $jenjang = $this->getJenjangForRole($user->role);

        $suratList = MasterSurat::when($search, function ($query) use ($search) {
                return $query->searchByName($search);
            })
            ->when($jenjang, function ($query) use ($jenjang) {
                return $query->byJenjang($jenjang);
            })
            ->get();

        $editSurat = null;
        if ($request->has('edit')) {
            $editSurat = MasterSurat::findOrFail($request->edit);

            // Cek agar hanya dapat edit surat jenjang yang sesuai role
            if ($editSurat->jenjang !== $jenjang) {
                abort(403, 'Tidak diizinkan mengedit surat jenjang lain.');
            }
        }

        return view('StaffMasterSuratSiswa', compact('suratList', 'editSurat', 'search'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $jenjang = $this->getJenjangForRole($user->role);

        if (!$jenjang) {
            abort(403, 'Role anda tidak diizinkan menambah surat.');
        }

        $request->validate([
            'nama_surat' => 'required|string|max:255',
            'jumlah_ayat' => 'required|integer|min:1',
            // 'jenjang' tidak perlu input dari user, kita set otomatis sesuai role
        ]);

        MasterSurat::create([
            'nama_surat' => $request->nama_surat,
            'jumlah_ayat' => $request->jumlah_ayat,
            'jenjang' => $jenjang,
        ]);

        return redirect()->route('StaffMasterSuratSiswa')->with('success', 'Surat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $jenjang = $this->getJenjangForRole($user->role);

        if (!$jenjang) {
            abort(403, 'Role anda tidak diizinkan mengubah surat.');
        }

        $request->validate([
            'nama_surat' => 'required|string|max:255',
            'jumlah_ayat' => 'required|integer|min:1',
            // 'jenjang' tidak perlu input user, di-set sesuai role
        ]);

        $surat = MasterSurat::findOrFail($id);

        if ($surat->jenjang !== $jenjang) {
            abort(403, 'Tidak diizinkan mengubah surat jenjang lain.');
        }

        $surat->update([
            'nama_surat' => $request->nama_surat,
            'jumlah_ayat' => $request->jumlah_ayat,
            // jangan update jenjang, biarkan tetap sesuai role
        ]);

        return redirect()->route('StaffMasterSuratSiswa')->with('success', 'Surat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $jenjang = $this->getJenjangForRole($user->role);

        if (!$jenjang) {
            abort(403, 'Role anda tidak diizinkan menghapus surat.');
        }

        $surat = MasterSurat::findOrFail($id);

        if ($surat->jenjang !== $jenjang) {
            abort(403, 'Tidak diizinkan menghapus surat jenjang lain.');
        }

        $surat->delete();

        return redirect()->route('StaffMasterSuratSiswa')->with('success', 'Surat berhasil dihapus.');
    }
}
