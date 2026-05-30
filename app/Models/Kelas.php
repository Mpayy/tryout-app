<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = [
        'nama',
        'kode',
    ];

    public function profileSiswas()
    {
        return $this->hasMany(ProfileSiswa::class, 'kelas_id', 'id');
    }

    public function paketUjians()
    {
        return $this->belongsToMany(PaketUjian::class, 'kelas_paket_ujian', 'kelas_id', 'paket_ujian_id');
    }
}
