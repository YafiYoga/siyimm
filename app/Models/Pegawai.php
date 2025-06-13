<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    

    // Primary key default sudah 'id' dan auto increment, jadi ini bisa dihilangkan
    // protected $primaryKey = 'id';
    // public $incrementing = true;

    protected $fillable = [
        'niy', // sekarang bukan primary key, tetap di fillable
        'unit_kerja',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_tanggal_lahir',
        'alamat',
        'no_telfon',
        'email',
        'tmt',
        'tugas_kepegawaian',
        'tugas_pokok',
        'status_pernikahan',
        'nama_pasangan',
        'nama_anak',
        'nama_ayah',
        'nama_ibu',
        'pendidikan_terakhir',
        'pas_foto_url',
        'foto',
    ];

    // Relasi ke User jika ada
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function siswa()
{
    return $this->hasMany(Siswa::class, 'id_pegawai');
}

    public function regisMapelSiswa()
    {
        return $this->hasMany(RegisMapelSiswa::class, 'id_pegawai');
    }

    public function kelasMapel()
    {
        return $this->hasMany(KelasMapel::class, 'id_pegawai');
    }
  
public function absensiPegawai()
{
    return $this->hasMany(AbsensiPegawai::class, 'id_pegawai', 'niy');
}


}
