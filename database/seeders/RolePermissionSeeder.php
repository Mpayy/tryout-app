<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\ProfileGuru;
use App\Models\ProfileSiswa;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset permission cache dulu
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Buat semua permission ─────────────────────────────────
        $permissions = [
            'manage-admin',
            'manage-guru',
            'manage-siswa',
            'manage-kelas',
            'manage-mata-pelajaran',
            'manage-soal',
            'manage-paket-ujian',
            'manage-profil',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ── Buat role & assign permission ─────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'manage-admin',
            'manage-guru',
            'manage-siswa',
            'manage-kelas',
            'manage-mata-pelajaran',
            'manage-soal',
            'manage-paket-ujian',
            'manage-profil',
        ]);

        $guru = Role::firstOrCreate(['name' => 'guru']);
        $guru->syncPermissions([
            'manage-soal',
            'manage-paket-ujian',
            'manage-profil',
        ]);

        $siswa = Role::firstOrCreate(['name' => 'siswa']);
        $siswa->syncPermissions([
            'manage-profil',
        ]);
        
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@tryout.com'],
            [
                'name'     => 'Admin Utama',
                'password' => bcrypt('password'),
            ]
        );
        $adminUser->syncRoles('admin');
    }
}
