<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Requests\GuruRequest;
use App\Services\GuruService;
use App\Models\MataPelajaran;
use App\Support\CacheKey;
use Illuminate\Support\Facades\Cache;

class GuruController extends Controller
{

    public function __construct(
        protected GuruService $guruService
    ) {}

    public function index()
    {
        $roles = Cache::remember(
            CacheKey::ALL_ROLES,
            now()->addMinutes(CacheKey::TTL_LONG),
            fn() => Role::all()
        );

        $mapels = Cache::remember(
            CacheKey::ALL_MATA_PELAJARAN,
            now()->addMinutes(CacheKey::TTL_LONG),
            fn() => MataPelajaran::all()
        );

        $daftarGuru = User::role('guru')
            ->with(['profileGuru.mataPelajarans:id,nama', 'roles:id,name'])
            ->select('id', 'name', 'email')
            ->paginate(5);

        return view('admin.guru.index', compact('daftarGuru', 'roles', 'mapels'));
    }

    public function store(GuruRequest $request)
    {
        $validatedData = $request->validated();

        $this->guruService->createGuru($validatedData);

        Cache::forget(CacheKey::STAT_TOTAL_GURU);
        Cache::forget(CacheKey::ALL_GURU_DROPDOWN);

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan!');
    }

    public function update(GuruRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $this->guruService->updateGuru($user, $validatedData);

        Cache::forget(CacheKey::STAT_TOTAL_GURU);
        Cache::forget(CacheKey::ALL_GURU_DROPDOWN);
        Cache::forget(CacheKey::mataPelajaranGuru($user->id));

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        Cache::forget(CacheKey::STAT_TOTAL_GURU);
        Cache::forget(CacheKey::ALL_GURU_DROPDOWN);
        Cache::forget(CacheKey::mataPelajaranGuru($user->id));

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus!');
    }
}
