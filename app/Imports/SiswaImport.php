<?php
namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\ValidationException;

class SiswaImport implements ToCollection, WithHeadingRow
{
    protected $userId;
    protected $duplicates = [];

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    private function getAllowedUnitKerja(string $role): array
    {
        return match ($role) {
    'staff_sd' => ['SD ISLAM TERPADU INSAN MADANI'],
    'staff_smp' => ['SMP IT TAHFIDZUL QURAN INSAN MADANI'],
    default => [],
};

    }
    private function cleanNumeric(?string $value): ?float
    {
        if ($value === null) return null;
        $clean = preg_replace('/[^0-9.]/', '', $value);
        return $clean === '' ? null : (float) $clean;
    }


    public function collection(Collection $rows)
    {
        $role = auth()->user()->role;
        $allowedUnits = $this->getAllowedUnitKerja($role);
        $userRole = $role === 'staff_sd' ? 'walimurid_sd' : 'walimurid_smp';

        foreach ($rows as $index => $row) {
            try {
                $unitKerja = trim($row['unit_kerja'] ?? '');

                // Jika unit kerja tidak sesuai, langsung throw exception dengan baris data
                $isAllowed = false;
                foreach ($allowedUnits as $unit) {
                    if (stripos($unitKerja, $unit) !== false) {
                        $isAllowed = true;
                        break;
                    }
                }
                if (!$isAllowed) {
                    throw new \Exception("Unit kerja '{$unitKerja}' pada baris ke-" . ($index+2) . " tidak diizinkan untuk role '{$role}'.");
                }

                // Cek duplikat
                if (Siswa::where('nisn', $row['nisn'])->exists() || User::where('username', $row['nisn'])->exists()) {
                    $this->duplicates[] = $row['nisn'];
                    continue;
                }

                // Simpan data siswa
                $siswa = Siswa::create([
                    'nama_siswa' => $row['nama_siswa'],
                    'nisn' => $row['nisn'],
                    'tempat_lahir' => $row['tempat_lahir'],
                    'tanggal_lahir' => $row['tanggal_lahir'],
                    'nik' => $row['nik'] ?? null,
                    'alamat' => $row['alamat'],
                    'asal_sekolah' => $row['asal_sekolah'] ?? null,
                    'nama_ayah' => $row['nama_ayah'] ?? null,
                    'nama_ibu' => $row['nama_ibu'] ?? null,
                    'nama_wali' => $row['nama_wali'] ?? null,
                    'no_kk' => $row['no_kk'] ?? null,
                    'berat_badan' => $this->cleanNumeric($row['berat_badan'] ?? null),
                    'tinggi_badan' => $this->cleanNumeric($row['tinggi_badan'] ?? null),
                    'lingkar_kepala' => $this->cleanNumeric($row['lingkar_kepala'] ?? null),
                    'jumlah_saudara_kandung' => $row['jumlah_saudara_kandung'] ?? null,
                    'jarak_rumah_ke_sekolah' => $row['jarak_rumah_ke_sekolah'] ?? null,
                    'lembaga' => $unitKerja,
                    'status' => $row['status'] ?? 'aktif',
                    'foto' => $row['foto'] ?? 'default.png',
                ]);

                $idWali = DB::table('walimurid')->insertGetId([
                    'id_siswa' => $siswa->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                User::create([
                    'namalengkap' => $row['nama_siswa'],
                    'username' => $row['nisn'],
                    'password' => Hash::make($row['nisn']),
                    'id_walimurid' => $idWali,
                    'role' => $userRole,
                    'foto' => 'default.png',
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal mengimpor siswa: ' . $e->getMessage());
                // Lempar exception agar proses import berhenti dan notifikasi muncul
                throw $e;
            }
        }
    }

    public function getDuplicates()
    {
        return $this->duplicates;
    }
}
