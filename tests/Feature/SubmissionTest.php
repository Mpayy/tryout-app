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
use App\Support\CacheKey;
use Carbon\Carbon;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SubmissionTest extends TestCase
{
    use RefreshDatabase;

    private User $guru;
    private User $siswa;
    private PaketUjian $paket;
    private array $soals = [];

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

        $kelas = Kelas::create(['nama' => '12 IPA 1', 'tingkat' => 12, 'kode' => '12-IPA-1']);
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

        $this->paket->kelas()->attach($kelas->id);

        // 4. Buat 10 Soal
        for ($i = 1; $i <= 10; $i++) {
            $soal = Soal::create([
                'guru_id' => $this->guru->id,
                'mata_pelajaran_id' => $mapel->id,
                'konten' => "Soal nomor $i"
            ]);

            $this->paket->soal()->attach($soal->id, ['nomor_urut' => $i]);

            $benar = PilihanJawaban::create([
                'soal_id' => $soal->id,
                'label' => 'A',
                'konten' => 'Benar',
                'is_correct' => true
            ]);

            $salah = PilihanJawaban::create([
                'soal_id' => $soal->id,
                'label' => 'B',
                'konten' => 'Salah',
                'is_correct' => false
            ]);

            $this->soals[] = [
                'model' => $soal,
                'benar' => $benar,
                'salah' => $salah
            ];
        }
    }

    public function test_submit_normal_menghitung_nilai_akurat_dan_menghapus_cache(): void
    {
        // Spy on cache forget
        $cacheSpy = Cache::spy();

        // Mulai ujian
        $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $this->paket->id));
        $sesi = SesiUjian::where('siswa_id', $this->siswa->id)->first();

        // Jawab 8 soal benar
        for ($i = 0; $i < 8; $i++) {
            $this->postJson(route('siswa.ujian.jawab', $sesi->token), [
                'soal_id' => $this->soals[$i]['model']->id,
                'pilihan_jawaban_id' => $this->soals[$i]['benar']->id
            ]);
        }

        // Jawab 2 soal salah
        for ($i = 8; $i < 10; $i++) {
            $this->postJson(route('siswa.ujian.jawab', $sesi->token), [
                'soal_id' => $this->soals[$i]['model']->id,
                'pilihan_jawaban_id' => $this->soals[$i]['salah']->id
            ]);
        }

        // Submit
        $response = $this->postJson(route('siswa.ujian.submit', $sesi->token));
        $response->assertStatus(200);

        // Verifikasi sesi
        $sesi->refresh();
        $this->assertEquals('selesai', $sesi->status);
        $this->assertEquals(80.00, $sesi->nilai);
        $this->assertEquals(8, $sesi->total_benar);
        $this->assertEquals(2, $sesi->total_salah);

        // Verifikasi Cache::forget dipanggil (beberapa key yang dihapus di UjianController)
        $cacheSpy->shouldHaveReceived('forget')->with(CacheKey::DASHBOARD_UJIAN_TERBARU);
        $cacheSpy->shouldHaveReceived('forget')->with(CacheKey::rekapPaket($sesi->paket_ujian_id));
        $cacheSpy->shouldHaveReceived('forget')->with("rekap_belum_ikut_{$sesi->paket_ujian_id}");
    }

    public function test_auto_submit_karena_waktu_habis(): void
    {
        // Mulai ujian
        $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $this->paket->id));
        $sesi = SesiUjian::where('siswa_id', $this->siswa->id)->first();

        // Jawab 5 soal benar
        for ($i = 0; $i < 5; $i++) {
            $this->postJson(route('siswa.ujian.jawab', $sesi->token), [
                'soal_id' => $this->soals[$i]['model']->id,
                'pilihan_jawaban_id' => $this->soals[$i]['benar']->id
            ]);
        }

        // Manipulasi waktu mulai agar lebih dari durasi 60 menit
        $sesi->waktu_mulai = now()->subMinutes(61);
        $sesi->save();

        // Akses halaman ujian show, seharusnya ter-auto submit
        $response = $this->get(route('siswa.ujian.show', $sesi->token));

        $response->assertRedirect(route('siswa.ujian.hasil', $sesi->token));

        // Verifikasi sesi
        $sesi->refresh();
        $this->assertEquals('selesai', $sesi->status);
        $this->assertEquals(50.00, $sesi->nilai); // 5 benar dari 10
    }

    public function test_tidak_bisa_menjawab_setelah_ujian_selesai(): void
    {
        // Mulai ujian
        $this->actingAs($this->siswa)->post(route('siswa.ujian.mulai', $this->paket->id));
        $sesi = SesiUjian::where('siswa_id', $this->siswa->id)->first();

        // Submit
        $this->postJson(route('siswa.ujian.submit', $sesi->token));

        $sesi->refresh();
        $this->assertEquals('selesai', $sesi->status);

        // Coba jawab soal
        $response = $this->postJson(route('siswa.ujian.jawab', $sesi->token), [
            'soal_id' => $this->soals[0]['model']->id,
            'pilihan_jawaban_id' => $this->soals[0]['benar']->id
        ]);

        // UjianController menggunakan firstOrFail() di mana status harus 'berlangsung'
        $response->assertStatus(404);
    }
}
