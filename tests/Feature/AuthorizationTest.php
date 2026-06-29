<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup roles & permissions
        $this->seed(RolePermissionSeeder::class);

        // Define generic routes to isolate middleware testing from controller logic
        Route::get('/_test/admin', fn() => 'ok')->middleware(['web', 'auth', 'role:admin']);
        Route::get('/_test/guru', fn() => 'ok')->middleware(['web', 'auth', 'role:guru']);
        Route::get('/_test/siswa', fn() => 'ok')->middleware(['web', 'auth', 'role:siswa']);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $this->get('/_test/admin')->assertRedirect(route('login'));
        $this->get('/_test/guru')->assertRedirect(route('login'));
        $this->get('/_test/siswa')->assertRedirect(route('login'));
    }

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        $this->get('/_test/admin')->assertStatus(200);
        $this->get('/_test/guru')->assertStatus(403);
        $this->get('/_test/siswa')->assertStatus(403);
    }

    public function test_guru_can_access_guru_routes(): void
    {
        $guru = User::factory()->create();
        $guru->assignRole('guru');

        $this->actingAs($guru);

        $this->get('/_test/guru')->assertStatus(200);
        $this->get('/_test/admin')->assertStatus(403);
        $this->get('/_test/siswa')->assertStatus(403);
    }

    public function test_siswa_can_access_siswa_routes(): void
    {
        $siswa = User::factory()->create();
        $siswa->assignRole('siswa');

        $this->actingAs($siswa);

        $this->get('/_test/siswa')->assertStatus(200);
        $this->get('/_test/admin')->assertStatus(403);
        $this->get('/_test/guru')->assertStatus(403);
    }
}
