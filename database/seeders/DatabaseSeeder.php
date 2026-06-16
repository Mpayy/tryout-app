<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * FIX #7: Panggil RolePermissionSeeder agar roles & permissions
     * terbuat saat developer baru clone repo dan jalankan `php artisan db:seed`.
     * Sebelumnya hanya membuat 1 test user tanpa role apapun.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}
