<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;

class KelasMahasiswaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('users_id', $user->id)->firstOrFail();

        $kelasList = KelasMahasiswa::with(['kelas.dosen.user'])
            ->where('mahasiswa_nrp', $mahasiswa->nrp)
            ->get()
            ->pluck('kelas')
            ->filter();

        return view('mahasiswa.lihat_kelas', compact('kelasList'));
    }

    public function joinKelas(Request $request)
    {
        $request->validate([
            'join_token' => 'required|string',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('users_id', $user->id)->firstOrFail();

        $kelas = Kelas::where('join_token', $request->join_token)->first();

        if (!$kelas) {
            return back()->with('error', 'Token kelas tidak valid. Silakan periksa kembali token yang Anda masukkan.');
        }

        $sudahTerdaftar = KelasMahasiswa::where('kelas_id', $kelas->id_kelas)
            ->where('mahasiswa_nrp', $mahasiswa->nrp)
            ->exists();

        if ($sudahTerdaftar) {
            return back()->with('error', 'Anda sudah terdaftar di kelas ini.');
        }

        KelasMahasiswa::create([
            'kelas_id'      => $kelas->id_kelas,
            'mahasiswa_nrp' => $mahasiswa->nrp,
        ]);

        return back()->with('success', 'Berhasil bergabung ke kelas ' . $kelas->nama_kelas . '!');
    }

    public function show($id_kelas)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('users_id', $user->id)->firstOrFail();

        // Pastikan mahasiswa terdaftar di kelas ini
        $terdaftar = KelasMahasiswa::where('kelas_id', $id_kelas)
            ->where('mahasiswa_nrp', $mahasiswa->nrp)
            ->exists();

        if (!$terdaftar) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }

        $kelas = Kelas::with(['dosen.user', 'kelasMahasiswa.mahasiswa.user'])
            ->findOrFail($id_kelas);

        return view('mahasiswa.detail_kelas', compact('kelas', 'mahasiswa'));
    }
}