<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'jenjang',
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];

    /**
     * Scope untuk mengambil kelas yang belum dihapus
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }
    /**
     * Relasi ke KelasMapel
     */ 
    public function kelasMapels()
{
    return $this->hasMany(KelasMapel::class, 'id_kelas');
}

}
