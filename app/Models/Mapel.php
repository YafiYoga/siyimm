<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapel';

    protected $fillable = [
        'nama_mapel',
        'jenjang',      // tambahkan jenjang
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];

    /**
     * Scope untuk mengambil mapel yang belum dihapus
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }
    // App\Models\Mapel.php
    public function kelasMapel()
    {
        return $this->hasMany(KelasMapel::class, 'id_mapel');
    }

}
