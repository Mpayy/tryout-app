<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiswaRequest;
use App\Models\Kelas;
use App\Models\User;
use App\Services\SiswaService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SiswaController extends Controller
{
    public function __construct(
        protected SiswaService $siswaService
    ){}

    public function index()
    {
        $roles = Role::all();
        $daftarKelas = Kelas::all();
        $daftarSiswa = User::role('siswa')->with(['profileSiswa.kelas'], 'roles:id,name')->select('id', 'name', 'email')->paginate(5);
        
        return view('admin.siswa.index', compact('daftarKelas', 'daftarSiswa', 'roles'));
    }

    public function store(SiswaRequest $request)
    {
        $validated = $request->validated();

        $this->siswaService->createSiswa($validated);

        return redirect()->route('admin.siswa.index');
    }

    public function update(SiswaRequest $request, User $user)
    {
        $validated = $request->validated();

        $this->siswaService->updateSiswa($user, $validated);

        return redirect()->route('admin.siswa.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('admin.siswa.index');
    }

}
