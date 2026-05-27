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
            'manage-admin', 'manage-guru', 'manage-siswa', 'view-users',
            'manage-mata-pelajaran', 'view-mata-pelajaran',
            'create-soal', 'edit-soal', 'delete-soal', 'view-soal',
            'create-paket-ujian', 'edit-paket-ujian', 'delete-paket-ujian',
            'publish-paket-ujian', 'view-paket-ujian',
            'take-ujian', 'view-hasil-sendiri', 'monitor-ujian',
            'view-rekap-nilai', 'view-ranking', 'export-excel', 'export-pdf',
            'edit-profil-sendiri',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ── Buat role & assign permission ─────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'manage-admin', 'manage-guru', 'manage-siswa', 'view-users',
            'manage-mata-pelajaran', 'view-mata-pelajaran',
            'view-soal', 'delete-soal',
            'delete-paket-ujian', 'publish-paket-ujian', 'view-paket-ujian',
            'monitor-ujian',
            'view-rekap-nilai', 'view-ranking', 'export-excel', 'export-pdf',
            'edit-profil-sendiri',
        ]);

        $guru = Role::firstOrCreate(['name' => 'guru']);
        $guru->syncPermissions([
            'view-mata-pelajaran',
            'create-soal', 'edit-soal', 'delete-soal', 'view-soal',
            'create-paket-ujian', 'edit-paket-ujian', 'delete-paket-ujian',
            'publish-paket-ujian', 'view-paket-ujian',
            'monitor-ujian',
            'view-rekap-nilai', 'view-ranking', 'export-excel', 'export-pdf',
            'edit-profil-sendiri',
        ]);

        $siswa = Role::firstOrCreate(['name' => 'siswa']);
        $siswa->syncPermissions([
            'view-mata-pelajaran', 'view-paket-ujian',
            'take-ujian', 'view-hasil-sendiri', 'view-ranking',
            'edit-profil-sendiri',
        ]);
        
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@tryout.com'],
            [
                'name'     => 'Admin Utama',
                'password' => bcrypt('password'),
            ]
        );
        $adminUser->syncRoles('admin');

        $guruUser = User::firstOrCreate(
            ['email' => 'guru@tryout.com'],
            [
                'name'     => 'Guru Utama',
                'password' => bcrypt('password'),
            ]
        );
        $guruUser->syncRoles('guru');

        ProfileGuru::firstOrCreate(
            ['user_id' => $guruUser->id],
            ['nip' => '199001001']
        );

        // Contoh siswa untuk testing
        $siswaUser = User::firstOrCreate(
            ['email' => 'siswa@tryout.com'],
            [
                'name'                => 'Siswa Utama',
                'password'            => bcrypt('password'),
                'is_profile_complete' => true,
            ]
        );
        $siswaUser->syncRoles('siswa');
        ProfileSiswa::firstOrCreate(
            ['user_id' => $siswaUser->id],
            ['kelas_id' => 1, 'nis' => '200710693', 'jurusan' => 'RPL']
        );
    }
}
