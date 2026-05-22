<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilihanJawaban extends Model
{
    protected $table = 'pilihan_jawaban';
    protected $fillable = ['soal_id', 'label', 'konten', 'is_correct'];

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id', 'id');
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class, 'pilihan_jawaban_id', 'id');
    }
}
