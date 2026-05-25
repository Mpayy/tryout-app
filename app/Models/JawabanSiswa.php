<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswa';
    protected $fillable = ['sesi_ujian_id', 'soal_id', 'pilihan_jawaban_id', 'is_ragu'];

    protected $casts = [
        'is_ragu' => 'boolean',
    ];

    public function sesiUjian()
    {
        return $this->belongsTo(SesiUjian::class, 'sesi_ujian_id', 'id');
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id', 'id');
    }

    public function pilihanJawaban()
    {
        return $this->belongsTo(PilihanJawaban::class, 'pilihan_jawaban_id', 'id');
    }
}
