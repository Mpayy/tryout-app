<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketUjian extends Model
{
    protected $table = 'paket_ujian';
    protected $fillable = [
        'guru_id',
        'mata_pelajaran_id',
        'nama',
        'durasi',
        'tanggal_mulai',
        'tanggal_selesai',
        'acak_soal',
        'acak_jawaban',
        'status'
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id', 'id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id', 'id');
    }

    public function soal()
    {
        return $this->belongsToMany(Soal::class, 'paket_ujian_soal', 'paket_ujian_id', 'soal_id')->withPivot('nomor_urut');
    }

    public function sesiUjian()
    {
        return $this->hasMany(SesiUjian::class, 'paket_ujian_id', 'id');
    }
}
