<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiSiswa extends Model
{
    use HasFactory;

    protected $table = 'nilai_siswa';

    protected $fillable = [
        'id_regis_mapel_siswa',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
    ];

    /**
     * Relasi ke RegisMapelSiswa
     */
    public function regisMapelSiswa()
    {
        return $this->belongsTo(RegisMapelSiswa::class, 'id_regis_mapel_siswa');
    }
}
