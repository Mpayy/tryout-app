<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ProfileGuru;
use App\Models\ProfileSiswa;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $guruUser = User::create([
                'name' => 'Guru ' . $i,
                'email' => 'guru' . $i . '@example.com',
                'password' => bcrypt('password'),
            ]);

            $guruUser->syncRoles('guru');

            ProfileGuru::create([
                'user_id' => $guruUser->id,
                'nip' => '19900100' . $i,
            ]);
        }

        for ($i = 0; $i < 100; $i++) {
            $siswaUser = User::create([
                'name' => 'Siswa ' . $i,
                'email' => 'siswa' . $i . '@example.com',
                'password' => bcrypt('password'),
            ]);

            $siswaUser->syncRoles('siswa');

            ProfileSiswa::create([
                'user_id' => $siswaUser->id,
                'nis' => '20071069' . $i,
            ]);
        }
    }
}
