<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User management
            'manage-admin',
            'manage-guru',
            'manage-siswa',
            'view-users',
            // Mata pelajaran
            'manage-mata-pelajaran',
            'view-mata-pelajaran',
            // Soal
            'create-soal',
            'edit-soal',
            'delete-soal',
            'view-soal',
            // Paket ujian
            'create-paket-ujian',
            'edit-paket-ujian',
            'delete-paket-ujian',
            'publish-paket-ujian',
            'view-paket-ujian',
            // Pelaksanaan ujian
            'take-ujian',
            'view-hasil-sendiri',
            'monitor-ujian',
            // Rekap & export
            'view-rekap-nilai',
            'view-ranking',
            'export-excel',
            'export-pdf',
            // Profil
            'edit-profil-sendiri',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ── Buat role & assign permission ─────────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'manage-admin',
            'manage-guru',
            'manage-siswa',
            'view-users',
            'manage-mata-pelajaran',
            'view-mata-pelajaran',
            'view-soal',
            'delete-soal',
            'delete-paket-ujian',
            'publish-paket-ujian',
            'view-paket-ujian',
            'monitor-ujian',
            'view-rekap-nilai',
            'view-ranking',
            'export-excel',
            'export-pdf',
            'edit-profil-sendiri',
        ]);

        $guru = Role::firstOrCreate(['name' => 'guru']);
        $guru->syncPermissions([
            'view-mata-pelajaran',
            'create-soal',
            'edit-soal',
            'delete-soal',
            'view-soal',
            'create-paket-ujian',
            'edit-paket-ujian',
            'delete-paket-ujian',
            'publish-paket-ujian',
            'view-paket-ujian',
            'monitor-ujian',
            'view-rekap-nilai',
            'view-ranking',
            'export-excel',
            'export-pdf',
            'edit-profil-sendiri',
        ]);

        $siswa = Role::firstOrCreate(['name' => 'siswa']);
        $siswa->syncPermissions([
            'view-mata-pelajaran',
            'view-paket-ujian',
            'take-ujian',
            'view-hasil-sendiri',
            'view-ranking',
            'edit-profil-sendiri',
        ]);

        // ── Buat user default ─────────────────────────────────────────
        $adminUser = \App\Models\User::firstOrCreate(
            ['email' => 'admin@tryout.com'],
            ['name' => 'Admin Utama', 'password' => bcrypt('password')]
        );
        $adminUser->assignRole('admin');

        $guruUser = \App\Models\User::firstOrCreate(
            ['email' => 'guru@tryout.com'],
            ['name' => 'Achmad Rifaih', 'password' => bcrypt('password')]
        );
        $guruUser->assignRole('guru');
        \App\Models\ProfileGuru::firstOrCreate(
            ['user_id' => $guruUser->id],
            ['nip' => '199001001', 'bidang_studi' => 'Matematika']
        );
    }
}
