<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileGuru extends Model
{
    protected $table = 'profiles_guru';
    protected $fillable = ['user_id', 'nip', 'foto'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mataPelajarans()
    {
        return $this->belongsToMany(MataPelajaran::class, 'mata_pelajaran_profile_guru', 'profile_guru_id', 'mata_pelajaran_id',);
    }
}
