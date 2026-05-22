<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileSiswa extends Model
{
    protected $table = 'profiles_siswa';
    protected $fillable = ['user_id', 'nis', 'nisn', 'kelas', 'jurusan', 'foto'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
