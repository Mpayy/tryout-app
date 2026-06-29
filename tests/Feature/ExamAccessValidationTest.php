<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PaketUjian;
use App\Models\ProfileSiswa;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamAccessValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $guru;
    private User $siswa;
    private Kelas $kelasSiswa;
    private Kelas $kelasLain;
    private MataPelajaran $mapel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(RolePermissionSeeder::class);

        // 1. Setup Guru
        $this->guru = User::factory()->create();
        $this->guru->assignRole('guru');

        // 2. Setup Kelas
        $this->kelasSiswa = Kelas::create(['nama' => '12 IPA 1', 'tingkat' => 12, 'kode' => '12-IPA-1']);
        $this->kelasLain = Kelas::create(['nama' => '12 IPS 1', 'tingkat' => 12, 'kode' => '12-IPS-1']);

        // 3. Setup Siswa
        $this->siswa = User::factory()->create();
        $this->siswa->assignRole('siswa');
        ProfileSiswa::create(['user_id' => $this->siswa->id, 'kelas_id' => $this->kelasSiswa->id, 'nis' => '12345']);

        // 4. Setup Mapel
        $this->mapel = MataPelajaran::create(['nama' => 'Matematika', 'kode' => 'MTK']);
    }

    private function createPaket($overrides = [])
    {
        return PaketUjian::create(array_merge([
            'guru_id' => $this->guru->id,
            'mata_pelajaran_id' => $this->mapel->id,
            'nama' => 'Ujian Akhir Semester',
            'durasi' => 60,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDay(),
            'status' => 'aktif'
        ], $overrides));
    }

    public function test_siswa_ditolak_jika_ujian_belum_waktunya(): void
    {
        $paket = $this->createPaket([
            'tanggal_mulai' => now()->addDay(),
            'tanggal_selesai' => now()->addDays(2),
        ]);
        $paket->kelas()->attach($this->kelasSiswa->id);

        $response = $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $paket->id));

        $response->assertRedirect(route('siswa.ujian.index'));
        $response->assertSessionHas('error');
    }

    public function test_siswa_ditolak_jika_ujian_sudah_lewat(): void
    {
        $paket = $this->createPaket([
            'tanggal_mulai' => now()->subDays(2),
            'tanggal_selesai' => now()->subDay(),
        ]);
        $paket->kelas()->attach($this->kelasSiswa->id);

        $response = $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $paket->id));

        $response->assertRedirect(route('siswa.ujian.index'));
        $response->assertSessionHas('error');
    }

    public function test_siswa_ditolak_jika_ujian_status_non_aktif(): void
    {
        $paket = $this->createPaket([
            'status' => 'draft',
        ]);
        $paket->kelas()->attach($this->kelasSiswa->id);

        $response = $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $paket->id));

        $response->assertRedirect(route('siswa.ujian.index'));
        $response->assertSessionHas('error');
    }

    public function test_siswa_ditolak_jika_ujian_untuk_kelas_lain(): void
    {
        $paket = $this->createPaket();
        // Assign ke kelas lain, bukan kelas siswa
        $paket->kelas()->attach($this->kelasLain->id);

        $response = $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $paket->id));

        // Karena controller UjianController saat ini mungkin belum mengecek kelas,
        // test ini bisa jadi gagal (mendapat 200/302 ke halaman show).
        // Mari kita assert redirect ke index atau minimal tidak berhasil membuat SesiUjian.
        $response->assertRedirect(route('siswa.ujian.index'));
        
        $this->assertDatabaseMissing('sesi_ujian', [
            'siswa_id' => $this->siswa->id,
            'paket_ujian_id' => $paket->id,
        ]);
    }
}
