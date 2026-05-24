<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\ProfileGuru;

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

        // ── Buat user default ─────────────────────────────────────
        // FIX #4 (benar): Role dikelola Spatie di tabel model_has_roles,
        // TIDAK ada kolom 'role' di tabel users — tidak perlu field 'role' di sini.
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
                'name'     => 'Achmad Rifaih',
                'password' => bcrypt('password'),
            ]
        );
        $guruUser->syncRoles('guru');

        ProfileGuru::firstOrCreate(
            ['user_id' => $guruUser->id],
            ['nip' => '199001001', 'bidang_studi' => 'Matematika']
        );

        // Contoh siswa untuk testing
        $siswaUser = User::firstOrCreate(
            ['email' => 'siswa@tryout.com'],
            [
                'name'                => 'Budi Santoso',
                'password'            => bcrypt('password'),
                'is_profile_complete' => true,
            ]
        );
        $siswaUser->syncRoles('siswa');
    }
}
