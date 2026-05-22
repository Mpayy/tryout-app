<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileGuru extends Model
{
    protected $table = 'profiles_guru';
    protected $fillable = ['user_id', 'nip', 'bidang_studi', 'foto'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
