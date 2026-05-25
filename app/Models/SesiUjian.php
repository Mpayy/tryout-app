<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiUjian extends Model
{
    protected $table = 'sesi_ujian';
    protected $fillable = [
        'siswa_id',
        'paket_ujian_id',
        'token',
        'waktu_mulai',
        'waktu_selesai',
        'sisa_waktu',
        'status',
        'nilai',
        'total_benar',
        'total_salah',
        'total_ragu'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id', 'id');
    }

    public function paketUjian()
    {
        return $this->belongsTo(PaketUjian::class, 'paket_ujian_id', 'id');
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class, 'sesi_ujian_id', 'id');
    }
    
}
