<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\KelasMahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ManageKelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('dosen', 'kelasMahasiswa.mahasiswa')->get();
        return view('admin.list_kelas', compact('kelas'));
    }

    public function create()
    {
        $dosen = Dosen::with('user')->get();
        $mahasiswa = Mahasiswa::with('user')->get();
        $generated_token = strtoupper(Str::random(6));
        return view('admin.create_kelas', compact('dosen', 'mahasiswa', 'generated_token'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:45',
            'kode_kelas' => 'required|string|max:10|unique:kelas,kode_kelas',
            'kelas_label' => 'required|in:A,B,C',
            'dosen_nik' => 'required|exists:dosen,nik',
            'join_token' => 'required|string|max:10|unique:kelas,join_token',
            'mahasiswa' => 'nullable|array',
            'mahasiswa.*' => 'exists:mahasiswa,nrp',
        ]);

        DB::beginTransaction();
        try {
            // Buat kelas
            $kelas = Kelas::create([
                'nama_kelas' => $request->nama_kelas,
                'kode_kelas' => $request->kode_kelas,
                'kelas_label' => $request->kelas_label,
                'dosen_nik' => $request->dosen_nik,
                'join_token' => $request->join_token,
            ]);

            // Tambah mahasiswa ke kelas
            if ($request->mahasiswa) {
                foreach ($request->mahasiswa as $nrp) {
                    KelasMahasiswa::create([
                        'kelas_id' => $kelas->id_kelas,
                        'mahasiswa_nrp' => $nrp,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.list-kelas')->with('success', 'Kelas berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan kelas: '.$e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $kelas = Kelas::with('kelasMahasiswa')->findOrFail($id);
        $dosen = Dosen::with('user')->get();
        $mahasiswa = Mahasiswa::with('user')->get();
        return view('admin.edit_kelas', compact('kelas', 'dosen', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:45',
            'kode_kelas' => [
                'required',
                'string',
                'max:10',
                Rule::unique('kelas', 'kode_kelas')->ignore($id, 'id_kelas'),
            ],
            'kelas_label' => 'required|in:A,B,C',
            'dosen_nik' => 'required|exists:dosen,nik',
            'join_token' => [
                'required',
                'string',
                'max:10',
                Rule::unique('kelas', 'join_token')->ignore($id, 'id_kelas'),
            ],
            'mahasiswa' => 'nullable|array',
            'mahasiswa.*' => 'exists:mahasiswa,nrp',
        ]);

        DB::beginTransaction();
        try {
            $kelas->update([
                'nama_kelas' => $request->nama_kelas,
                'kode_kelas' => $request->kode_kelas,
                'kelas_label' => $request->kelas_label,
                'dosen_nik' => $request->dosen_nik,
                'join_token' => $request->join_token,
            ]);

            // Hapus mahasiswa lama
            KelasMahasiswa::where('kelas_id', $kelas->id_kelas)->delete();

            // Tambah mahasiswa baru
            if ($request->mahasiswa) {
                foreach ($request->mahasiswa as $nrp) {
                    KelasMahasiswa::create([
                        'kelas_id' => $kelas->id_kelas,
                        'mahasiswa_nrp' => $nrp,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.list-kelas')->with('success', 'Kelas berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal update kelas: '.$e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        DB::beginTransaction();
        try {
            KelasMahasiswa::where('kelas_id', $kelas->id_kelas)->delete();
            $kelas->delete();

            DB::commit();
            return redirect()->route('admin.list-kelas')->with('success', 'Kelas berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal hapus kelas: '.$e->getMessage()]);
        }
    }
}