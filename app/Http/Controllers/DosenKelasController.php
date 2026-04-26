<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\Dosen;

class DosenKelasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('users_id', $user->id)->firstOrFail();

        $kelasList = Kelas::with(['kelasMahasiswa'])
            ->where('dosen_nik', $dosen->nik)
            ->get();

        return view('dosen.lihat_kelas', compact('kelasList', 'dosen'));
    }

    public function show($id_kelas)
    {
        $user = Auth::user();
        $dosen = Dosen::where('users_id', $user->id)->firstOrFail();

        // Pastikan kelas ini milik dosen yang login
        $kelas = Kelas::with(['kelasMahasiswa.mahasiswa.user'])
            ->where('id_kelas', $id_kelas)
            ->where('dosen_nik', $dosen->nik)
            ->firstOrFail();

        return view('dosen.detail_kelas', compact('kelas', 'dosen'));
    }
}