<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaliMurid extends Model
{
    use HasFactory;

    protected $table = 'walimurid';

    protected $fillable = [
        'id_siswa',
    ];

    /**
     * Relasi ke model Siswa.
     * Setiap wali murid terkait dengan satu siswa.
     */
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id', 'id_siswa');
    }

    /**
     * Relasi ke model User.
     * Setiap wali murid punya satu user.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id_walimurid', 'id');
    }
}
