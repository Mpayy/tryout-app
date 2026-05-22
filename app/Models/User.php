<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'is_profile_complete',
        'is_approved'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profileSiswa()
    {
        return $this->hasOne(ProfileSiswa::class, 'user_id', 'id');
    }

    public function profileGuru()
    {
        return $this->hasOne(ProfileGuru::class, 'user_id', 'id');
    }

    public function soal()
    {
        return $this->hasMany(Soal::class, 'guru_id', 'id');
    }

    public function paketUjian()
    {
        return $this->hasMany(PaketUjian::class, 'guru_id', 'id');
    }

    public function sesiUjian()
    {
        return $this->hasMany(SesiUjian::class, 'siswa_id', 'id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
}
