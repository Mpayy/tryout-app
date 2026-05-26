<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruService
{
    public function createGuru(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
    
            $user->assignRole($data['role']);
    
            $user->profileGuru()->create([
                'nip' => $data['nip'],
                'bidang_studi' => $data['bidang_studi'],
            ]);
    
            return $user;
        });
    }

    public function updateGuru(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            if(!empty($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }
            
            $user->syncRoles($data['role']);
            $user->profileGuru()->update([
                'nip' => $data['nip'],
                'bidang_studi' => $data['bidang_studi'],
            ]);

            return $user;
        });
    }
}