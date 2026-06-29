<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PaketUjian;
use App\Models\PilihanJawaban;
use App\Models\ProfileSiswa;
use App\Models\SesiUjian;
use App\Models\Soal;
use App\Models\User;
use App\Models\JawabanSiswa;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $guru;
    private User $siswa;
    private PaketUjian $paket;
    private Soal $soal;
    private PilihanJawaban $pilihanBenar;
    private PilihanJawaban $pilihanSalah;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(RolePermissionSeeder::class);

        // 1. Setup Guru
        $this->guru = User::factory()->create();
        $this->guru->assignRole('guru');

        // 2. Setup Siswa & Kelas
        $this->siswa = User::factory()->create();
        $this->siswa->assignRole('siswa');
        
        $kelas = Kelas::create(['nama' => '12 IPA 1', 'kode' => '12-IPA-1']);
        ProfileSiswa::create(['user_id' => $this->siswa->id, 'kelas_id' => $kelas->id, 'nis' => '12345']);

        // 3. Setup Mata Pelajaran & Paket Ujian
        $mapel = MataPelajaran::create(['nama' => 'Matematika', 'kode' => 'MTK']);
        
        $this->paket = PaketUjian::create([
            'guru_id' => $this->guru->id,
            'mata_pelajaran_id' => $mapel->id,
            'nama' => 'Ujian Akhir Semester',
            'durasi' => 60,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDay(),
            'status' => 'aktif'
        ]);
        
        // Assign paket to kelas
        $this->paket->kelas()->attach($kelas->id);

        // 4. Setup Soal & Pilihan
        $this->soal = Soal::create([
            'guru_id' => $this->guru->id,
            'mata_pelajaran_id' => $mapel->id,
            'konten' => '1 + 1 = ?'
        ]);

        $this->paket->soal()->attach($this->soal->id, ['nomor_urut' => 1]);

        $this->pilihanBenar = PilihanJawaban::create([
            'soal_id' => $this->soal->id,
            'label' => 'A',
            'konten' => '2',
            'is_correct' => true
        ]);

        $this->pilihanSalah = PilihanJawaban::create([
            'soal_id' => $this->soal->id,
            'label' => 'B',
            'konten' => '3',
            'is_correct' => false
        ]);
    }

    public function test_mulai_ujian_membuat_sesi_dan_jawaban_kosong(): void
    {
        $response = $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $this->paket->id));

        $sesi = SesiUjian::where('siswa_id', $this->siswa->id)->first();
        
        $response->assertRedirect(route('siswa.ujian.show', $sesi->token));

        $this->assertNotNull($sesi);
        $this->assertEquals('berlangsung', $sesi->status);
        $this->assertNotNull($sesi->waktu_mulai);

        $this->assertDatabaseHas('jawaban_siswa', [
            'sesi_ujian_id' => $sesi->id,
            'soal_id' => $this->soal->id,
            'pilihan_jawaban_id' => null,
            'is_ragu' => false,
        ]);
    }

    public function test_simpan_jawaban_update_tabel_tanpa_duplikasi(): void
    {
        // Setup: Siswa mulai ujian
        $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $this->paket->id));
        $sesi = SesiUjian::where('siswa_id', $this->siswa->id)->first();

        // Act 1: Pilih jawaban benar
        $response1 = $this->postJson(route('siswa.ujian.jawab', $sesi->token), [
            'soal_id' => $this->soal->id,
            'pilihan_jawaban_id' => $this->pilihanBenar->id
        ]);
        
        $response1->assertStatus(200);
        $this->assertDatabaseHas('jawaban_siswa', [
            'sesi_ujian_id' => $sesi->id,
            'soal_id' => $this->soal->id,
            'pilihan_jawaban_id' => $this->pilihanBenar->id
        ]);
        $this->assertEquals(1, JawabanSiswa::where('sesi_ujian_id', $sesi->id)->count());

        // Act 2: Ubah ke jawaban salah
        $response2 = $this->postJson(route('siswa.ujian.jawab', $sesi->token), [
            'soal_id' => $this->soal->id,
            'pilihan_jawaban_id' => $this->pilihanSalah->id
        ]);

        $response2->assertStatus(200);
        $this->assertDatabaseHas('jawaban_siswa', [
            'sesi_ujian_id' => $sesi->id,
            'soal_id' => $this->soal->id,
            'pilihan_jawaban_id' => $this->pilihanSalah->id
        ]);
        // Pastikan tidak ada duplikasi baris
        $this->assertEquals(1, JawabanSiswa::where('sesi_ujian_id', $sesi->id)->count());
    }

    public function test_tandai_ragu_ragu_mengubah_status_is_ragu(): void
    {
        $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $this->paket->id));
        $sesi = SesiUjian::where('siswa_id', $this->siswa->id)->first();

        $response = $this->postJson(route('siswa.ujian.ragu', $sesi->token), [
            'soal_id' => $this->soal->id,
            'is_ragu' => true
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('jawaban_siswa', [
            'sesi_ujian_id' => $sesi->id,
            'soal_id' => $this->soal->id,
            'is_ragu' => true
        ]);
    }

    public function test_submit_ujian_mengubah_status_dan_menghitung_nilai(): void
    {
        $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $this->paket->id));
        $sesi = SesiUjian::where('siswa_id', $this->siswa->id)->first();

        // Jawab dengan benar
        $this->postJson(route('siswa.ujian.jawab', $sesi->token), [
            'soal_id' => $this->soal->id,
            'pilihan_jawaban_id' => $this->pilihanBenar->id
        ]);

        // Submit ujian
        $response = $this->postJson(route('siswa.ujian.submit', $sesi->token));

        $response->assertStatus(200);
        $response->assertJson(['redirect' => route('siswa.ujian.hasil', $sesi->token)]);

        // Refresh sesi dari DB
        $sesi->refresh();

        $this->assertEquals('selesai', $sesi->status);
        $this->assertNotNull($sesi->waktu_selesai);
        $this->assertEquals(100, $sesi->nilai); // 1 soal benar dari 1 total = 100
        $this->assertEquals(1, $sesi->total_benar);
        $this->assertEquals(0, $sesi->total_salah);
    }
}
