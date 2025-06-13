<?php

namespace App\Imports;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PegawaiImport implements ToCollection
{
    protected $userId;
    protected $role;

    public $imported = 0;
    public $duplicates = 0;
    public $invalidRole = 0;

    public function __construct($userId, $role)
    {
        $this->userId = $userId;
        $this->role = $role;
    }

    private function isValidRoleForUnitKerja(string $unitKerja, string $role): bool
    {
        $mapping = [
            'SMP IT TAHFIDZUL QURAN INSAN MADANI' => ['staff_smp', 'guru_smp', 'lembaga_smp'],
            'SD ISLAM TERPADU INSAN MADANI' => ['staff_sd', 'guru_sd', 'lembaga_sd'],
            // Tambahkan unit kerja lain jika diperlukan
        ];

        foreach ($mapping as $unitPattern => $allowedRoles) {
            if (stripos($unitKerja, $unitPattern) !== false) {
                return in_array($role, $allowedRoles);
            }
        }

        return false; // unit kerja tidak ditemukan dalam mapping
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0 || empty($row[0])) {
                continue; // Skip header or empty rows
            }

            $niy = trim($row[0]);
            $unitKerja = trim($row[1]);
            $role = strtolower(trim($row[19] ?? 'staff_sd'));

            // Validasi role berdasarkan unit kerja
            if (!$this->isValidRoleForUnitKerja($unitKerja, $role)) {
                $this->invalidRole++;
                continue;
            }

            // Cek duplikat NIY
            if (Pegawai::where('niy', $niy)->exists()) {
                $this->duplicates++;
                continue;
            }

            // Convert TMT (tanggal dari Excel)
            $tmt = null;
            try {
                $tmt = Date::excelToDateTimeObject($row[9])->format('Y-m-d');
            } catch (\Exception $e) {
                // Biarkan $tmt null jika gagal convert
            }

            // Normalisasi jenis kelamin
            $jenis_kelamin = $this->normalizeJenisKelamin($row[4]);

            // Simpan data Pegawai
            $pegawai = Pegawai::create([
                'user_id' => $this->userId,
                'niy' => $niy,
                'unit_kerja' => $unitKerja,
                'nama_lengkap' => $row[2],
                'nama_panggilan' => $row[3],
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_tanggal_lahir' => $row[5],
                'alamat' => $row[6],
                'no_telfon' => $row[7],
                'email' => $row[8],
                'tmt' => $tmt,
                'tugas_kepegawaian' => $row[10],
                'tugas_pokok' => $row[11] ?? 'Tidak Diketahui',
                'status_pernikahan' => $row[12] ?? 'Belum Menikah',
                'nama_pasangan' => $row[13],
                'nama_anak' => $row[14],
                'nama_ayah' => $row[15],
                'nama_ibu' => $row[16],
                'pendidikan_terakhir' => $row[17],
                'pas_foto_url' => $row[18],
            ]);

            // Buat username unik dari nama lengkap
            $username = strtolower(str_replace(' ', '_', $pegawai->nama_lengkap));
            $originalUsername = $username;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $originalUsername . $counter;
                $counter++;
            }

            // Simpan data User terkait
            User::create([
                'namalengkap' => $pegawai->nama_lengkap,
                'username' => $username,
                'password' => $pegawai->niy,  // jangan lupa hash password!
                'role' => $role,
                'id_pegawai' => $pegawai->niy,
                'isDeleted' => false,
            ]);

            $this->imported++;
        }
    }

    private function normalizeJenisKelamin($jenisKelamin)
    {
        $jenisKelamin = strtolower(trim($jenisKelamin));
        if (strpos($jenisKelamin, 'laki') !== false) return 'Laki-laki';
        if (strpos($jenisKelamin, 'perempuan') !== false) return 'Perempuan';
        return null;
    }
}
