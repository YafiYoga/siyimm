<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    // Fungsi untuk mapping role ke jenjang
    private function getJenjangByRole($role)
    {
        return match ($role) {
            'staff_sd' => 'SD ISLAM TERPADU INSAN MADANI',
            'staff_smp' => 'SMP IT TAHFIDZUL QURAN INSAN MADANI',
            default => null,
        };
    }

    // Menampilkan daftar kelas & form tambah/edit
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $jenjang = $this->getJenjangByRole($role);

        if (!$jenjang) {
            abort(403, 'Unauthorized action.');
        }

        $kelasList = Kelas::where('is_deleted', false)
                          ->where('jenjang', $jenjang)
                          ->get();

        $editKelas = null;

        if ($request->has('edit')) {
            $editKelas = Kelas::where('jenjang', $jenjang)
                              ->findOrFail($request->edit);
        }

        return view('StaffKelasSiswa', compact('kelasList', 'editKelas'));
    }

    // Menyimpan kelas baru
    public function store(Request $request)
    {
        $role = auth()->user()->role;
        $jenjang = $this->getJenjangByRole($role);

        if (!$jenjang) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nama_kelas' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kelas')->where(function ($query) use ($jenjang) {
                    return $query->where('jenjang', $jenjang)
                                 ->where('is_deleted', false);
                }),
            ],
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'jenjang' => $jenjang,
        ]);

        return redirect()->route('StaffKelasSiswa')->with('success', 'Kelas berhasil ditambahkan.');
    }

    // Memperbarui data kelas
    public function update(Request $request, $id)
    {
        $role = auth()->user()->role;
        $jenjang = $this->getJenjangByRole($role);

        if (!$jenjang) {
            abort(403, 'Unauthorized action.');
        }

        $kelas = Kelas::where('jenjang', $jenjang)->findOrFail($id);

        $request->validate([
            'nama_kelas' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kelas')->where(function ($query) use ($jenjang) {
                    return $query->where('jenjang', $jenjang)
                                 ->where('is_deleted', false);
                })->ignore($kelas->id),
            ],
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'jenjang' => $jenjang,
        ]);

        return redirect()->route('StaffKelasSiswa')->with('success', 'Kelas berhasil diperbarui.');
    }

    // Menghapus kelas (soft delete)
    public function destroy($id)
{
    $role = auth()->user()->role;
    $jenjang = $this->getJenjangByRole($role);

    if (!$jenjang) {
        abort(403, 'Unauthorized action.');
    }

    $kelas = Kelas::where('jenjang', $jenjang)->findOrFail($id);

    // Hapus permanen data kelas
    $kelas->delete();

    return redirect()->route('StaffKelasSiswa')->with('success', 'Kelas berhasil dihapus.');
}

}
