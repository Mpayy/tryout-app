<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiswaRequest;
use App\Models\Kelas;
use App\Models\User;
use App\Services\SiswaService;
use App\Support\CacheKey;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class SiswaController extends Controller
{
    public function __construct(
        protected SiswaService $siswaService
    ) {}

    public function index()
    {
        $roles = Cache::remember(CacheKey::ALL_ROLES, now()->addMinutes(CacheKey::TTL_LONG), fn() => Role::all());
        $daftarKelas = Cache::remember(CacheKey::ALL_KELAS, now()->addMinutes(CacheKey::TTL_LONG), fn() => Kelas::all());

        $daftarSiswa = User::role('siswa')
            ->with(['profileSiswa.kelas', 'roles:id,name'])
            ->select('id', 'name', 'email')
            ->paginate(5);

        return view('admin.siswa.index', compact('daftarKelas', 'daftarSiswa', 'roles'));
    }

    public function store(SiswaRequest $request)
    {
        $validated = $request->validated();

        $this->siswaService->createSiswa($validated);

        Cache::forget(CacheKey::STAT_TOTAL_SISWA);
        Cache::forget(CacheKey::KELAS_WITH_COUNT);
        

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function update(SiswaRequest $request, User $user)
    {
        $validated = $request->validated();

        $this->siswaService->updateSiswa($user, $validated);

        Cache::forget(CacheKey::STAT_TOTAL_SISWA);
        Cache::forget(CacheKey::KELAS_WITH_COUNT);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diupdate');
    }

    public function destroy(User $user)
    {
        $user->delete();

        Cache::forget(CacheKey::STAT_TOTAL_SISWA);
        Cache::forget(CacheKey::KELAS_WITH_COUNT);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus');
    }
}
