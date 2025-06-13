<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'isDeleted',
        'id_pegawai',    // FK ke pegawai.id (primary key)
        'id_walimurid',  // FK ke walimurid.id
    ];

    // Hash password otomatis saat diset
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => bcrypt($value),
        );
    }

    // Relasi ke Pegawai (id_pegawai -> pegawai.id)
    public function pegawai()
    {
        
        // Karena pegawai primary key adalah 'id', bukan 'niy'
       return $this->belongsTo(Pegawai::class, 'id_pegawai', 'niy');
    }

    // Relasi ke Wali Murid (id_walimurid -> walimurid.id)
    public function walimurid()
    {
        return $this->belongsTo(Walimurid::class, 'id_walimurid');
    }
}
