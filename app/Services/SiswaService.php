<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaService
{
    public function createSiswa(array $data)
    {
        return DB::transaction(function () use ($data) {
            $siswa = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password'])
            ]);

            $siswa->assignRole($data['role']);
            $siswa->profileSiswa()->create([
                'nis'      => $data['nis'],
                'kelas_id' => $data['kelas'],
                'jurusan'  => $data['jurusan'] ?? null,
            ]);

            return $siswa;
        });
    }

    public function updateSiswa(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);

            if (!empty($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password'])
                ]);
            }

            $user->syncRoles($data['role']);

            $user->profileSiswa()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nis'      => $data['nis'],
                    'kelas_id' => $data['kelas'],
                    'jurusan'  => $data['jurusan'] ?? null,
                ]
            );

            return $user;
        });
    }
}