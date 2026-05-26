<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProfileGuru;
use Spatie\Permission\Models\Role;
use App\Http\Requests\GuruRequest;
use App\Services\GuruService;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        $daftarGuru = User::role('guru')->with(['profileGuru'=> function($query){
            $query->select('id', 'user_id', 'nip', 'bidang_studi');
        }])->select('id', 'name', 'email')->get();

        return view('admin.users.index', compact('daftarGuru', 'roles'));
    }


    protected $guruService;

    public function __construct(GuruService $guruService)
    {
        $this->guruService = $guruService;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(GuruRequest $request)
    {
        $validatedData = $request->validated();

        $this->guruService->createGuru($validatedData);

        return redirect()->route('admin.users.index');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(GuruRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $this->guruService->updateGuru($user, $validatedData);

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('admin.users.index');
    }
}
