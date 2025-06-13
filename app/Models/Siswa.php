<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    public const LEMBAGA_SD = 'SD ISLAM TERPADU INSAN MADANI';
    public const LEMBAGA_SMP = 'SMP IT TAHFIDZUL QURAN INSAN MADANI';

    public const LEMBAGA_OPTIONS = [
        self::LEMBAGA_SD,
        self::LEMBAGA_SMP,
    ];

    protected $fillable = [
        'nama_siswa',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'alamat',
        'asal_sekolah',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'no_kk',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'jumlah_saudara_kandung',
        'jarak_rumah_ke_sekolah',
        'lembaga',
        'status',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function nilai()
    {
        return $this->hasMany(NilaiSiswa::class, 'id_siswa', 'id');
    }

    

    public function hafalan()
    {
        return $this->hasMany(HafalanQuranSiswa::class, 'siswa_id', 'id');
    }
    public function regisMapelSiswas()
{
    return $this->hasMany(RegisMapelSiswa::class, 'id_siswa', 'id');
}

}
