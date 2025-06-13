<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HafalanQuranSiswa extends Model
{
    use HasFactory;

    protected $table = 'hafalan_quran_siswa';

    protected $fillable = [
        'id_regis_mapel_siswa',
        'id_surat',
        'id_guru',
        'ayat_dari',
        'ayat_sampai',
        'tgl_setor',
        'keterangan',
    ];

    protected $casts = [
        'tgl_setor' => 'date',
    ];

    /**
     * Relasi ke RegisMapelSiswa
     */
    public function regisMapelSiswa()
    {
        return $this->belongsTo(RegisMapelSiswa::class, 'id_regis_mapel_siswa');
    }

    /**
     * Relasi ke MasterSurat
     */
    public function surat()
    {
        return $this->belongsTo(MasterSurat::class, 'id_surat');
    }

    /**
     * Relasi ke Pegawai (guru)
     */
    public function guru()
    {
        return $this->belongsTo(Pegawai::class, 'id_guru');
    }
}
