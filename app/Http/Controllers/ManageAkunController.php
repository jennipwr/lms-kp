<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManageAkunController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        return view('admin.list_user', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.create_user', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email',
            'password'=> 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:role,id_role',
            'nrp'     => 'nullable|required_if:role_id,3|string|max:10',
            'nik'     => 'nullable|required_if:role_id,2|string|max:10',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role_id'  => $request->role_id,
            ]);

            if ($request->role_id == 3 && $request->nrp) {
                Mahasiswa::create([
                    'nrp'      => $request->nrp,
                    'users_id' => $user->id,
                ]);
            } elseif ($request->role_id == 2 && $request->nik) {
                Dosen::create([
                    'nik'      => $request->nik,
                    'users_id' => $user->id,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.list-user')->with('success', 'User berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.edit_user', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:role,id_role',
        ]);

        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->role_id = $request->role_id;

        if($request->password){
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.list-user')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        try {
            DB::beginTransaction();

            // Hapus data mahasiswa/dosen dulu jika ada
            if ($user->mahasiswa) {
                $user->mahasiswa()->delete();
            }

            if ($user->dosen) {
                $user->dosen()->delete();
            }

            $user->delete();

            DB::commit();

            return redirect()->route('admin.list-user')->with('success', 'User berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.list-user')->withErrors(['error' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}