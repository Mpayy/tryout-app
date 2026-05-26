<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MataPelajaranRequest;
use App\Models\MataPelajaran;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mapels = MataPelajaran::select('id', 'nama', 'kode', 'deskripsi')->get();
        return view('admin.mapels.index', compact('mapels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MataPelajaranRequest $request)
    {
        $validateData = $request->validated();
        MataPelajaran::create($validateData);
        return redirect()->route('admin.mapels.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MataPelajaranRequest $request, MataPelajaran $mapel)
    {
        $validateData = $request->validated();
        $mapel->update($validateData);
        return redirect()->route('admin.mapels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaran $mapel)
    {
        $mapel->delete();
        return redirect()->route('admin.mapels.index');
    }
}
