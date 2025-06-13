<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasMapel extends Model
{
    use HasFactory;

    protected $table = 'kelas_mapel';

    protected $fillable = [
        'id_tahun_ajaran',
        'id_kelas',
        'id_mapel',
        'id_guru',
    ];

    /**
     * Relasi ke TahunAjaran
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
    }

    /**
     * Relasi ke Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    /**
     * Relasi ke Mapel
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel');
    }

    /**
     * Relasi ke Pegawai (guru)
     */
    public function guru()
    {
        return $this->belongsTo(Pegawai::class, 'id_guru');
    }
    // app/Models/KelasMapel.php

public function regisMapelSiswas()
{
    return $this->hasMany(RegisMapelSiswa::class, 'id_kelas_mapel');
}


}
