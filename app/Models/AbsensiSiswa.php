<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbsensiSiswa extends Model
{
    use HasFactory;

    protected $table = 'absensi_siswa';

    protected $fillable = [
        'id_regis_mapel_siswa',
        'tanggal',
        'status', // hadir / sakit / alpha / izin
    ];

    /**
     * Relasi ke RegisMapelSiswa
     */
    public function regisMapelSiswa()
    {
        return $this->belongsTo(RegisMapelSiswa::class, 'id_regis_mapel_siswa');
    }
    
}
