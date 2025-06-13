<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSurat extends Model
{
    use HasFactory;

    protected $table = 'master_surat';

    protected $fillable = [
        'nama_surat',
        'jumlah_ayat',
        'jenjang',  // tambahkan ini
    ];

    /**
     * Scope untuk mencari surat berdasarkan nama (partial match)
     */
    public function scopeSearchByName($query, $keyword)
    {
        return $query->where('nama_surat', 'like', "%{$keyword}%");
    }

    /**
     * Scope untuk filter surat berdasarkan jenjang
     */
    public function scopeByJenjang($query, $jenjang)
    {
        return $query->where('jenjang', $jenjang);
    }

    /**
     * Contoh relasi jika ada tabel ayat terkait
     */
    public function ayat()
    {
        return $this->hasMany(MasterAyat::class, 'id_surat');
    }
}
