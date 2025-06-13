<?php

namespace App\Http\Controllers;

use App\Imports\PegawaiImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Siswa;

class PegawaiController extends Controller
{
    public function tambahPegawaiStaff()
    {
        return view('TambahPegawaiStaff');
    }

    private function getUnitKerjaPatternsByRole(string $role): array
    {
        return match ($role) {
    'staff_sd' => [Siswa::LEMBAGA_SD],    // lembaga SD penuh
    'staff_smp' => [Siswa::LEMBAGA_SMP],  // lembaga SMP penuh
    default => [],
};
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Role validation: hanya boleh tambah pegawai sesuai batasan role user
        if (
            $user->role === 'staff_sd' &&
            !in_array($request->role, ['guru_sd', 'staff_sd', 'lembaga_sd'])
        ) {
            return redirect()->back()->withErrors(['role' => 'Anda hanya dapat menambahkan pegawai dengan role guru_sd, staff_sd, atau lembaga_sd.'])->withInput();
        }

        if (
            $user->role === 'staff_smp' &&
            !in_array($request->role, ['guru_smp', 'staff_smp', 'lembaga_smp'])
        ) {
            return redirect()->back()->withErrors(['role' => 'Anda hanya dapat menambahkan pegawai dengan role guru_smp, staff_smp, atau lembaga_smp.'])->withInput();
        }

        // Validasi input
        $validated = $request->validate([
            'niy' => 'required|numeric|unique:pegawai,niy',
            'unit_kerja' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:100',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tempat_tanggal_lahir' => 'required|string|max:255',
            'alamat' => 'required|string|max:1000',
            'no_telfon' => 'nullable|numeric',
            'email' => 'nullable|email',
            'tmt' => 'nullable|date',
            'tugas_kepegawaian' => 'nullable|string|max:255',
            'tugas_pokok' => 'required|string|max:100',
            'status_pernikahan' => 'required|string|max:50',
            'nama_pasangan' => 'nullable|string|max:255',
            'nama_anak' => 'nullable|string',
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'pas_foto_url' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|in:yayasan,lembaga_sd,lembaga_smp,staff_sd,staff_smp,guru_sd,guru_smp,walimurid_sd,walimurid_smp',
        ]);

        // Upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_pegawai', 'public');
        }

        // Buat data Pegawai
        $pegawai = Pegawai::create([
            'niy' => $validated['niy'],
            'unit_kerja' => $validated['unit_kerja'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'nama_panggilan' => $validated['nama_panggilan'] ?? null,
            'jenis_kelamin' => strtolower($validated['jenis_kelamin']),
            'tempat_tanggal_lahir' => $validated['tempat_tanggal_lahir'],
            'alamat' => $validated['alamat'],
            'no_telfon' => $validated['no_telfon'] ?? null,
            'email' => $validated['email'] ?? null,
            'tmt' => $validated['tmt'] ?? null,
            'tugas_kepegawaian' => $validated['tugas_kepegawaian'] ?? null,
            'tugas_pokok' => $validated['tugas_pokok'],
            'status_pernikahan' => $validated['status_pernikahan'],
            'nama_pasangan' => $validated['nama_pasangan'] ?? null,
            'nama_anak' => $validated['nama_anak'] ?? null,
            'nama_ayah' => $validated['nama_ayah'] ?? null,
            'nama_ibu' => $validated['nama_ibu'] ?? null,
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? null,
            'pas_foto_url' => $validated['pas_foto_url'] ?? null,
            'foto' => $fotoPath,
        ]);

        // Generate username unik
        $username = strtolower(str_replace(' ', '_', $validated['nama_lengkap']));
        $originalUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        // Buat user terkait pegawai
        User::create([
            'namalengkap' => $validated['nama_lengkap'],
            'username' => $username,
            'password' => $validated['niy'], // hash password biar aman
            'role' => $validated['role'],
            'id_pegawai' => $pegawai->niy,
            'isDeleted' => false,
        ]);

        return redirect()->route('TambahPegawaiStaff')->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    public function index(Request $request)
    {
        $userRole = auth()->user()->role;
        $patterns = $this->getUnitKerjaPatternsByRole($userRole);

        if (empty($patterns)) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $search = $request->input('search');
        $jabatan = $request->input('jabatan');

        $pegawaiQuery = Pegawai::with('user')
            ->where(function ($query) use ($patterns) {
                foreach ($patterns as $pattern) {
                    $query->orWhere('unit_kerja', 'like', $pattern);
                }
            });

        if ($search) {
            $pegawaiQuery->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('niy', 'like', "%$search%")
                  ->orWhere('no_telfon', 'like', "%$search%");
            });
        }

        if ($jabatan) {
            $pegawaiQuery->where('tugas_kepegawaian', $jabatan);
        }

        $pegawai = $pegawaiQuery->get();

        $filtersApplied = $search || $jabatan;
        $noResults = $filtersApplied && $pegawai->isEmpty();

        $tugasKepegawaianOptions = Pegawai::where(function ($query) use ($patterns) {
            foreach ($patterns as $pattern) {
                $query->orWhere('unit_kerja', 'like', $pattern);
            }
        })
        ->distinct()
        ->pluck('tugas_kepegawaian')
        ->toArray();

        return view('LihatPegawaiStaff', compact('pegawai', 'tugasKepegawaianOptions', 'noResults'));
    }

    public function edit(string $niy)
    {
        $pegawai = Pegawai::with('user')->where('niy', $niy)->firstOrFail();
        return view('HalamanEditPegawaiStaff', compact('pegawai'));
    }


public function update(Request $request, string $niy)
{
    $pegawai = Pegawai::with('user')->where('niy', $niy)->firstOrFail();

    $validated = $request->validate([
        'niy' => 'required|string|max:30|unique:pegawai,niy,' . $niy . ',niy',
        'nama_lengkap' => 'required|string|max:255',
        'nama_panggilan' => 'nullable|string|max:255',
        'tempat_tanggal_lahir' => 'required|string|max:255',
        'alamat' => 'required|string|max:1000',
        'no_telfon' => 'nullable|string|max:20',
        'unit_kerja' => 'required|string|max:255',
        'tugas_kepegawaian' => 'nullable|string|max:255',
        'tugas_pokok' => 'required|string|max:100',
        'email' => 'nullable|email|max:255',
        'tmt' => 'nullable|date',
        'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        'status_pernikahan' => 'nullable|string|max:50',
        'nama_pasangan' => 'nullable|string|max:255',
        'nama_anak' => 'nullable|string',
        'nama_ayah' => 'nullable|string|max:255',
        'nama_ibu' => 'nullable|string|max:255',
        'pendidikan_terakhir' => 'nullable|string|max:255',
        'pas_foto_url' => 'nullable|string|max:500',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Upload foto jika ada file
    if ($request->hasFile('foto')) {
        // Hapus foto lama jika ada
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        // Simpan ke folder user_photos/
        $fotoPath = $request->file('foto')->store('user_photos', 'public');
        $validated['foto'] = $fotoPath;
    }

    $pegawai->update($validated);

    if ($pegawai->user) {
        $pegawai->user->update(['namalengkap' => $validated['nama_lengkap']]);
    }

    return redirect()->route('LihatPegawaiStaff')->with('success', 'Data pegawai berhasil diupdate!');
}

    public function destroy(string $niy)
    {
        $pegawai = Pegawai::where('niy', $niy)->firstOrFail();

        // Hapus foto jika ada
        if ($pegawai->foto) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        // Hapus user terkait
        User::where('id_pegawai', $pegawai->niy)->delete();

        // Hapus pegawai
        $pegawai->delete();

        return redirect()->route('LihatPegawaiStaff')->with('success', 'Data pegawai berhasil dihapus!');
    }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
    ]);

    $file = $request->file('file');
    $user = auth()->user();

    $import = new PegawaiImport($user->id, $user->role);
    Excel::import($import, $file);

    // Cek jika tidak ada data berhasil diimport
    if ($import->imported === 0) {
        $errorMessage = 'Tidak ada data yang berhasil diimpor.';
        
        if ($import->duplicates > 0) {
            $errorMessage .= ' Beberapa data sudah ada (duplikat).';
        }

        if ($import->invalidRole > 0) {
            $errorMessage .= ' Beberapa data memiliki role yang tidak sesuai.';
        }

        return redirect()->back()->with('error', $errorMessage);
    }

    return redirect()->route('LihatPegawaiStaff')->with([
        'success' => 'Import selesai.',
        'imported' => $import->imported,
        'duplicates' => $import->duplicates,
    ]);
}

    public function showImportForm()
{
    return view('ImportPegawaiStaff'); // pastikan view 'pegawai.import' ada
}
}
