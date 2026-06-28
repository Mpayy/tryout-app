<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MataPelajaranRequest;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Cache;
use App\Support\CacheKey;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapels = Cache::remember(
            CacheKey::ALL_MATA_PELAJARAN,
            now()->addMinutes(CacheKey::TTL_LONG),
            fn() => MataPelajaran::all()
        );

        return view('admin.mapels.index', compact('mapels'));
    }

    public function store(MataPelajaranRequest $request)
    {
        $validateData = $request->validated();
        MataPelajaran::create($validateData);

        Cache::forget(CacheKey::ALL_MATA_PELAJARAN);
        return redirect()->route('admin.mapels.index')->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

    public function update(MataPelajaranRequest $request, MataPelajaran $mapel)
    {
        $validateData = $request->validated();
        $mapel->update($validateData);

        Cache::forget(CacheKey::ALL_MATA_PELAJARAN);
        return redirect()->route('admin.mapels.index')->with('success', 'Mata pelajaran berhasil diupdate');
    }

    public function destroy(MataPelajaran $mapel)
    {
        $mapel->delete();
        Cache::forget(CacheKey::ALL_MATA_PELAJARAN);
        return redirect()->route('admin.mapels.index')->with('success', 'Mata pelajaran berhasil dihapus');
    }
}
