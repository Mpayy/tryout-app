<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    protected $table = 'soal';
    protected $fillable = ['mata_pelajaran_id', 'guru_id', 'konten', 'gambar', 'tingkat_kesulitan'];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id', 'id');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id', 'id');
    }

    public function pilihanJawaban()
    {
        return $this->hasMany(PilihanJawaban::class, 'soal_id', 'id');
    }

    public function jawabanBenar()
    {
        return $this->hasOne(PilihanJawaban::class)->where('is_correct', true);
    }

    public function paketUjian()
    {
        return $this->belongsToMany(PaketUjian::class, 'paket_ujian_soal', 'soal_id', 'paket_ujian_id')->withPivot('nomor_urut');
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class, 'soal_id', 'id');
    }
}
