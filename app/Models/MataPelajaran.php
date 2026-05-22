<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $fillable = ['nama', 'kode', 'deskripsi'];

    public function soal()
    {
        return $this->hasMany(Soal::class, 'mata_pelajaran_id', 'id');
    }

    public function paketUjian()
    {
        return $this->hasMany(PaketUjian::class, 'mata_pelajaran_id', 'id');
    }
}
