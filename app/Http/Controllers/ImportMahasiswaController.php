<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\MahasiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportMahasiswaController extends Controller
{
    public function index()
    {
        return view('admin.import_mahasiswa');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $import = new MahasiswaImport;
            Excel::import($import, $request->file('file'));

            return redirect()->back()->with([
                'success' => 'Mahasiswa berhasil diimport dan akun dibuat!',
                'accounts' => $import->accounts
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}