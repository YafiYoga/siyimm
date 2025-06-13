<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisMapelSiswa extends Model
{
    use HasFactory;

    protected $table = 'regis_mapel_siswa';

    protected $fillable = [
        
        'id_kelas_mapel',
        'id_siswa',
       
    ];

    /**
     * Relasi ke kelas mapel
     */
    public function kelasMapel()
    {
        return $this->belongsTo(KelasMapel::class, 'id_kelas_mapel');
    }
    /**
     * Relasi ke siswa
     */ 
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
    /**
     * Relasi ke absensi siswa
     */         
    public function absensiSiswa()
    {
        return $this->hasMany(AbsensiSiswa::class, 'id_regis_mapel_siswa');
    }
    /**
     * Relasi ke nilai siswa
     */             
    public function nilaiSiswa()
    {
        return $this->hasOne(NilaiSiswa::class, 'id_regis_mapel_siswa');
    }
    public function hafalanQuranSiswa()
{
    return $this->hasMany(HafalanQuranSiswa::class, 'id_regis_mapel_siswa');
}
    
}
