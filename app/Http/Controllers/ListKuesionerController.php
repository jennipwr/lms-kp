<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListKuesionerController extends Controller
{
    // Tampilkan semua list kuesioner
    public function index()
    {
        $list = DB::table('list_kuesioner')->get();
        return view('admin.list_kuesioner', compact('list'));
    }

    // Form tambah kuesioner baru
    public function create()
    {
        return view('admin.create_kuesioner');
    }

    // Simpan kuesioner baru
    public function store(Request $request)
    {
        $status = $request->action === 'publish' ? 'published' : 'draft';

        $id_list = DB::table('list_kuesioner')->insertGetId([
            'nama_kuesioner' => $request->nama_kuesioner,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if($request->pertanyaan){
            foreach($request->pertanyaan as $index => $pert){
                DB::table('kuesioner')->insert([
                    'id_list' => $id_list,
                    'pertanyaan' => $pert,
                    'dimensi' => $request->dimensi[$index] ?? 'profil',
                    'opsi_a' => $request->opsi_a[$index] ?? null,
                    'kutub_a' => $request->kutub_a[$index] ?? null,
                    'opsi_b' => $request->opsi_b[$index] ?? null,
                    'kutub_b' => $request->kutub_b[$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return redirect()->route('admin.list-kuesioner')->with('success','Kuesioner berhasil disimpan.');
    }

    // Form edit kuesioner + pertanyaan
    public function edit($id)
    {
        $list = DB::table('list_kuesioner')->where('id_list', $id)->first();
        $pertanyaan = DB::table('kuesioner')->where('id_list', $id)->get();

        return view('admin.edit_kuesioner', compact('list','pertanyaan'));
    }

    // Update kuesioner
    public function update(Request $request, $id)
    {
        DB::table('list_kuesioner')->where('id_list',$id)->update([
            'nama_kuesioner' => $request->nama_kuesioner,
            'status' => $request->status,
            'updated_at' => now()
        ]);
        // Handle existing + new pertanyaan from edit form
        $pertanyaan = $request->pertanyaan ?? [];
        $opsi_a = $request->opsi_a ?? [];
        $opsi_b = $request->opsi_b ?? [];
        $id_kuesioner = $request->id_kuesioner ?? [];

        $existingCount = count($id_kuesioner);

        // Update existing pertanyaan
        for($i = 0; $i < $existingCount; $i++){
            $id_quest = $id_kuesioner[$i];
            DB::table('kuesioner')->where('id_kuesioner', $id_quest)->update([
                'pertanyaan' => $pertanyaan[$i] ?? null,
                'dimensi' => $request->dimensi[$i] ?? null,
                'opsi_a' => $opsi_a[$i] ?? null,
                'kutub_a' => $request->kutub_a[$i] ?? null,
                'opsi_b' => $opsi_b[$i] ?? null,
                'kutub_b' => $request->kutub_b[$i] ?? null,
                'updated_at' => now()
            ]);
        }

        // Insert new pertanyaan (those after existingCount)
        $total = count($pertanyaan);
        for($i = $existingCount; $i < $total; $i++){
            // skip empty entries
            if(trim($pertanyaan[$i] ?? '') === '') continue;

            DB::table('kuesioner')->insert([
                'id_list' => $id,
                'pertanyaan' => $pertanyaan[$i] ?? null,
                'dimensi' => $request->dimensi[$i] ?? 'profil',
                'opsi_a' => $opsi_a[$i] ?? null,
                'kutub_a' => $request->kutub_a[$i] ?? null,
                'opsi_b' => $opsi_b[$i] ?? null,
                'kutub_b' => $request->kutub_b[$i] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect('/admin/list-kuesioner')->with('success','Kuesioner berhasil diupdate');
    }

    // Hapus kuesioner (beserta semua pertanyaannya karena FK ON DELETE CASCADE)
    public function destroy($id)
    {
        DB::table('list_kuesioner')->where('id_list',$id)->delete();
        return back()->with('success','Kuesioner berhasil dihapus');
    }

    // Hapus pertanyaan individual
    public function deletePertanyaan($id)
    {
        DB::table('kuesioner')->where('id_kuesioner',$id)->delete();
        return back()->with('success','Pertanyaan berhasil dihapus');
    }
}