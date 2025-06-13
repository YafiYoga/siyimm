<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'jenjang',          // ditambahkan
        'aktif_saat_ini',
        'is_deleted',
    ];

    protected $casts = [
        'aktif_saat_ini' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    /**
     * Scope untuk mengambil tahun ajaran yang aktif dan belum dihapus
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif_saat_ini', true)
                     ->where('is_deleted', false);
    }

    /**
     * Scope untuk mengambil data yang belum dihapus
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }
    public function kelasMapel()
{
    return $this->hasMany(KelasMapel::class, 'id_tahun_ajaran');
}
}
