<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PaketUjian;
use App\Models\ProfileSiswa;
use App\Models\SesiUjian;
use App\Models\Soal;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruBackendTest extends TestCase
{
    use RefreshDatabase;

    private User $guru1;
    private User $guru2;
    private User $siswa1;
    private User $siswa2;
    private MataPelajaran $mapel;
    private Kelas $kelas;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(RolePermissionSeeder::class);

        $this->guru1 = User::factory()->create();
        $this->guru1->assignRole('guru');

        $this->guru2 = User::factory()->create();
        $this->guru2->assignRole('guru');

        $this->kelas = Kelas::create(['nama' => '12 IPA 1', 'tingkat' => 12, 'kode' => '12-IPA-1']);

        $this->siswa1 = User::factory()->create();
        $this->siswa1->assignRole('siswa');
        ProfileSiswa::create(['user_id' => $this->siswa1->id, 'kelas_id' => $this->kelas->id, 'nis' => '111']);

        $this->siswa2 = User::factory()->create();
        $this->siswa2->assignRole('siswa');
        ProfileSiswa::create(['user_id' => $this->siswa2->id, 'kelas_id' => $this->kelas->id, 'nis' => '222']);

        $this->mapel = MataPelajaran::create(['nama' => 'Matematika', 'kode' => 'MTK']);
    }

    public function test_guru_bisa_membuat_paket_dan_akses_terproteksi()
    {
        // Guru 1 membuat paket
        $response = $this->actingAs($this->guru1)->post(route('guru.paket-ujian.store'), [
            'nama' => 'Ujian Akhir',
            'mata_pelajaran_id' => $this->mapel->id,
            'durasi' => 60,
            'tanggal_mulai' => now()->subDay()->toDateTimeString(),
            'tanggal_selesai' => now()->addDay()->toDateTimeString(),
            'status' => 'draft',
            'kelas_ids' => [$this->kelas->id]
        ]);

        $response->assertRedirect(route('guru.paket-ujian.index'));
        $paket = PaketUjian::where('guru_id', $this->guru1->id)->first();
        $this->assertNotNull($paket);

        // Guru 2 mencoba mengedit paket milik Guru 1
        $responseEdit = $this->actingAs($this->guru2)->put(route('guru.paket-ujian.update', $paket->id), [
            'nama' => 'Ujian Akhir Bajakan',
            'mata_pelajaran_id' => $this->mapel->id,
            'durasi' => 60,
            'tanggal_mulai' => now()->subDay()->toDateTimeString(),
            'tanggal_selesai' => now()->addDay()->toDateTimeString(),
            'kelas_ids' => [$this->kelas->id]
        ]);

        $responseEdit->assertStatus(403);
    }

    public function test_pencegahan_edit_atau_hapus_ujian_aktif()
    {
        $paket = PaketUjian::create([
            'guru_id' => $this->guru1->id,
            'mata_pelajaran_id' => $this->mapel->id,
            'nama' => 'Ujian Tengah Semester',
            'durasi' => 60,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDay(),
            'status' => 'aktif'
        ]);

        // Simulasikan ujian sudah dimulai oleh siswa
        SesiUjian::create([
            'siswa_id' => $this->siswa1->id,
            'paket_ujian_id' => $paket->id,
            'token' => 'abc',
            'status' => 'berlangsung',
            'waktu_mulai' => now()
        ]);

        // Guru 1 mencoba menghapus paket
        $responseDelete = $this->actingAs($this->guru1)->delete(route('guru.paket-ujian.destroy', $paket->id));
        $responseDelete->assertRedirect();
        $responseDelete->assertSessionHas('error'); // Diharapkan diblok dengan session error
        $this->assertDatabaseHas('paket_ujian', ['id' => $paket->id]);

        // Guru 1 mencoba tambah soal
        $soal = Soal::create(['guru_id' => $this->guru1->id, 'mata_pelajaran_id' => $this->mapel->id, 'konten' => 'T']);
        $responseTambah = $this->actingAs($this->guru1)->post(route('guru.paket-ujian.tambah-soal', $paket->id), [
            'soal_id' => [$soal->id]
        ]);
        $responseTambah->assertRedirect();
        $responseTambah->assertSessionHas('error'); // Diharapkan diblok

        // Guru 1 mencoba hapus soal
        $paket->soal()->attach($soal->id, ['nomor_urut' => 1]);
        $responseHapus = $this->actingAs($this->guru1)->delete(route('guru.paket-ujian.hapus-soal', ['paket_ujian' => $paket->id, 'soal' => $soal->id]));
        $responseHapus->assertRedirect();
        $responseHapus->assertSessionHas('error'); // Diharapkan diblok
    }

    public function test_rekapitulasi_kalkulasi_nilai_akurat()
    {
        $paket = PaketUjian::create([
            'guru_id' => $this->guru1->id,
            'mata_pelajaran_id' => $this->mapel->id,
            'nama' => 'Ujian',
            'durasi' => 60,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDay(),
            'status' => 'aktif'
        ]);

        // Siswa 1 dapat nilai 100
        SesiUjian::create([
            'siswa_id' => $this->siswa1->id,
            'paket_ujian_id' => $paket->id,
            'token' => 'tok1',
            'status' => 'selesai',
            'waktu_mulai' => now(),
            'waktu_selesai' => now()->addMinutes(10),
            'nilai' => 100,
        ]);

        // Siswa 2 dapat nilai 50
        SesiUjian::create([
            'siswa_id' => $this->siswa2->id,
            'paket_ujian_id' => $paket->id,
            'token' => 'tok2',
            'status' => 'selesai',
            'waktu_mulai' => now(),
            'waktu_selesai' => now()->addMinutes(20),
            'nilai' => 50,
        ]);

        $response = $this->actingAs($this->guru1)->get(route('guru.rekap.show', $paket->id));
        $response->assertStatus(200);

        // Assert view has data and values are correctly calculated
        $response->assertViewHas('statistik', function ($statistik) {
            return $statistik['total_peserta'] === 2
                && $statistik['rata_rata'] === 75.00
                && $statistik['nilai_tertinggi'] === 100
                && $statistik['nilai_terendah'] === 50;
        });
    }
}
